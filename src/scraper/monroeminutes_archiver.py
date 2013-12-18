import pika
import simplejson

import threading

import elasticsearch

from dler.dler import DLer
from unpdfer.unpdfer import UnPDFer

from models import *

class Archiver(object):

    def __init__(self,address='localhost',exchange='barkingowl',downloaddir="./downloads",DEBUG=False):

        self.exchange = exchange
        self.downloaddir = downloaddir
        self.DEBUG = DEBUG
        
    
        #setup message bus
        self.reqcon = pika.BlockingConnection(pika.ConnectionParameters(host=address))
        self.reqchan = self.reqcon.channel()
        self.reqchan.exchange_declare(exchange=self.exchange,type='fanout')
        result = self.reqchan.queue_declare(exclusive=True)
        queue_name = result.method.queue
        self.reqchan.queue_bind(exchange=self.exchange,queue=queue_name)
        self.reqchan.basic_consume(self.reqcallback,queue=queue_name,no_ack=True)

    def start(self):
        print "Listening for new documents ..."
        self.reqchan.start_consuming()

    ####
    #
    # Message Bus handler
    #
    ####

    def reqcallback(self,ch,method,properties,body):
        response = simplejson.loads(body)

        if self.DEBUG:
            print "Archiver: Message Recieved ('{0}')".format(response['command'])

        #
        # This means that a pdf document has been found and 
        # broadcasted to the bus
        #
        if response['command'] == 'found_doc':
            
            if self.DEBUG:
                print "Processing new document ..."

            docurl = response['message']['docurl']
            linktext = response['message']['linktext']
            urldata = response['message']['urldata']
            scrapedatetime = response['message']['scrapedatetime']

            #
            # TODO: start thread to process the doc
            #

            processor = DocProcessor(downloaddir=self.downloaddir,DEBUG=self.DEBUG)
            processor.start()
            processor.processdoc(docurl,linktext,urldata,scrapedatetime)

            if self.DEBUG:
                print "Done with processing new document."

        elif response['command'] == 'global_shutdown':
            print "Global Shutdown Command Seen"
            raise Exception("Archiver Exiting.")


class DocProcessor(threading.Thread):

    def __init__(self,downloaddir='./downloads',DEBUG=False):
        threading.Thread.__init__(self)

        self.downloaddir = downloaddir
        self.DEBUG = DEBUG

    def run(self):
        if self.DEBUG:
            print "Processor thread started."

    def processdoc(self,docurl,linktext,urldata,scrapedatetime):
           
            if self.DEBUG:
                print "Attemping to process document ..."

            targeturls = self.geturlslist()
            targeturl = urldata['targeturl']

            if self.DEBUG:
                print "Target URL: '{0}'".format(targeturl)

            # before we start downloading and processing the pdf file we want to
            # make sure that the url is within the url list that is in the database
            if targeturl in targeturls:
                
                if self.DEBUG:
                    print "Target URL within database, processing new document ..."
                    print "URL Data: \n    {0}".format(urldata)

                #
                # The flow of things:
                #
                # 1. Download PDF and save to file store
                # 2. Convert PDF -> Text
                # 3. Save Text to file store
                # 4. Report doc to database
                # 5. Push Text to Elastic Search
                #
                #

                # 1. Download and save pdf
                pdffilename = self.downloadpdf(docurl)
               
                # 2. Convert PDF to Text
                created,pdftext,pdfhash = self.getpdftext(pdffilename)

                # 3. Save text doc to file store
                textfilename = "%s.txt" % pdffilename
                self.savetext(textfilename,pdftext)

                # 3. Save doc to DB
                docid = self.savedoctodb(docurl,linktext,urldata['urlid'],scrapedatetime,pdfhash,textfilename,pdffilename)

                # 4. Push text to elastic search
                self.sendtoelasticsearch(urldata['targeturl'],docurl,linktext,docid,pdftext,pdfhash,scrapedatetime)

                if self.DEBUG:
                    print "New document processed successfully."
            else:
                print "Target URL not within the database, skipping."

    def geturlslist(self):
        if self.DEBUG:
            print "Getting list of URLs from the database."

        urls = Urls()
        _urls = urls.getall()
        urllist = []
        for url in _urls:
            urllist.append(url[1])

        if self.DEBUG:
            print "URL List: {0}".format(urllist)

        return urllist


    def downloadpdf(self,url):

        if self.DEBUG:
            print "Downloading document '{0}' to '{1}'".format(url,self.downloaddir)

        #try:
        dler = DLer()
        files,success = dler.dl([url],self.downloaddir) 
        #except:
        #    success = False
        #    if self.DEBUG:
        #        print "Exception raised while downloading document."
        if success:
            filename,dldatetime = files[0]
        else:
            raise Exception("File Download Error.")
    
        if self.DEBUG:
            print "PDF document successfully downloaded to filestore."

        return filename

    def savetext(self,filename,text):

        if self.DEBUG:
            print "Saving document text to filestore ..."

        with open(filename,"w") as f:
            f.write(text)

        if self.DEBUG:
            print "Document text successfully written to file store."

    def savedoctodb(self,docurl,linktext,targeturlid,scrapedatetime,pdfhash,textfilename,pdffilename):
        if self.DEBUG:
            print "Saving document to database ..."
            #print "    docurl: {0}".format(docurl)
            #print "    linktext: {0}".format(linktext)
            #print "    targeturlid: {0}".format(targeturlid)
            #print "    scrapedatetime: {0}".format(scrapedatetime)
            #print "    pdfhash: {0}".format(pdfhash)
            #print "    textfilename: {0}".format(textfilename)
            #print "    pdffilename: {0}".format(pdffilename)
 
        docs = Docs()
        docid = docs.add(docurl,linktext,targeturlid,scrapedatetime,pdfhash,textfilename,pdffilename)

        if self.DEBUG:
            print "Document successfully added to database."

        return docid

    def getpdftext(self,filename,SCRUB=True):

        if self.DEBUG:
            print "Trying to convert document to text ..."

        unpdfer = UnPDFer(filename)
        created,pdftext,pdfhash,success = unpdfer.unpdf(filename,SCRUB=SCRUB)
        if success:
            retval = (created,pdftext,pdfhash)
        else:
            raise Exception("Error in PDF->Text conversion")

        if self.DEBUG:
            print "Document successfully converted from PDF to Text."

        return retval

    ####
    #
    # Elastic Search Functions
    #
    ####

    def sendtoelasticsearch(self,targeturl,docurl,linktext,docid,pdftext,pdfhash,scrapedatetime):

        print "Attempting to push document to ElasticSearch ..."

        es = elasticsearch.Elasticsearch() # TODO: implement server definition rather than just localhost

        es.index(
            index="monroeminutes",
            doc_type="meeting_minutes",
            id=docid,
            body={
                'targeturl': targeturl,
                'docurl': docurl,
                'linktext': linktext,
                'docid': docid,
                'pdftext': pdftext,
                'pdfhash': pdfhash,
                'scrapedatetime': scrapedatetime
            }
        )

        print "Document successfully loaded into Elastic Search Indexer."

if __name__ == '__main__':

    print "Archiver Starting ..."

    downloaddir="/home/administrator/dev/monroeminutes/downloads/"

    standalone = False
    if standalone:
        docurl = "http://timduffy.me/poster.pdf"
        linktext = "resume"
        urldata = {
            'urlid': 4,                       # meta
            'targeturl': 'http://timduffy.me/',               # required
            'title': 'TimDuffy.Me',                       # required
            'description': "Tim Duffy's Personal Website",           # required
            'maxlinklevel': 2,         # required
            'creationdatetime': '2013-12-18 00:00:00 -0500', # required
            'doctype': 'application/pdf',                   # required
            'frequency': 1,               # required
           'organizationid': 3      # meta
        }
        scrapedatetime = "2013-01-01 00:00:00 -0500"    

        processor = DocProcessor(downloaddir=downloaddir,DEBUG=True)
        processor.start()
        processor.processdoc(docurl,linktext,urldata,scrapedatetime)
    else:

        archiver = Archiver(address='localhost',exchange='monroeminutes',downloaddir=downloaddir,DEBUG=True)
        #try:
        archiver.start()
        #except:
        #    print "Archiver Exiting."

