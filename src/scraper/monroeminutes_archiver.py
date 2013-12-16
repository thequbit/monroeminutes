import pika
import simplejson

from dler.dler import DLer
from unpdfer.unpdfer import UnPDFer

class Archiver():

    def __init__(self,address='localhost',exchange='barkingowl'):

        #setup message bus
        self.reqcon = pika.BlockingConnection(pika.ConnectionParameters(host=address))
        self.reqchan = self.reqcon.channel()
        self.reqchan.exchange_declare(exchange=exchange,type='fanout')
        result = self.reqchan.queue_declare(exclusive=True)
        queue_name = result.method.queue
        self.reqchan.queue_bind(exchange=exchange,queue=queue_name)
        self.reqchan.basic_consume(self.reqcallback,queue=queue_name,no_ack=True)

    def start(self):
        print "Listening for new documents ..."
        self.reqchan.start_consuming()

    ####
    #
    # DB access functions 
    #
    ####

     def geturlslist(self):
        urls = []

        # TODO: pull list of URLs from the database

        return urls

    ####
    #
    # Message Bus handler
    #
    ####

    def reqcallback(self,ch,method,properties,body):
        response = simplejson.loads(body)

        #
        # This means that a pdf document has been found and 
        # broadcasted to the bus
        #
        if response['command'] == 'found_doc':
            docurl = response['message']['docurl']
            linktext = response['message']['linktext']
            urldata = response['message']['urldata']
            scrapedatetime = response['message']['scraperdatetime']

            self.processdoc(docurl,linktext,urldata,scrapedatetime)

    ####
    #
    # PDF/Doc Functions
    #
    ####

    def processdoc(self,docurl,linktext,urldata,scrapedatetime):
            
            # before we start downloading and processing the pdf file we want to
            # make sure that the url is within the url list that is in the database
            if urldata['targeturl'] in self.geturllist():
                
                #
                # The flow of things:
                #
                # 1. Download PDF and save to file store
                # 2. Convert PDF -> Text
                # 3. Report doc to database
                # 4. Push Text to Elastic Search
                # 5. Save Text to file store
                #

                # 1. Download and save pdf
                self.filename = downloadpdf(docurl)
               
                # 2. COnvert PDF to Text
                created,pdftext,pdfhash = self.getpdftext(filename)

                # 3. 

                # 4. Push text to elastic search
                self.sendtoelasticsearch(docurl,urldata['targeturl'],pdftext,pdfhash,scrapedatetime)

    def downloadpdf(self,url):
        dler = DLer()
        files,success = dler.dl([url],'./downloads') # TODO: modify this to be a configurable filestore
        if success:
            filename = files[0]
        else:
            raise Exception("File Download Error.")

        return filename

    def savedoctodb(self,docurl,linktext,targeturlid,scraperdatetime):
        docid = 0
        return docid

    def getpdftext(self,filename,SCRUB=True):
        unpdfer = UnPDFer(filename,SCRUB=SCRUB)
        created,pdftext,pdfhash,success = unpdfer.unpdf(filename,SCRUB=True)
        if success:
            retval = (created,pdftext,pdfhash)
        else:
            raise Exception("Error in PDF->Text conversion")
        return retval

    ####
    #
    # Elastic Search Functions
    #
    ####

    def sendtoelasticsearch(self,targeturl,docurl,pdftext,pdfhash,scrapedatetime):
        es = elasticsearch.Elasticsearch() # TODO: implement server definition rather than just localhost

        es.index(
            index="monroeminutes",
            doc_type="pdf",
            

if __name__ == '__main__':

    print "Archiver Starting ..."

    archiver = Archiver(address='localhost',exchange='monroeminutes')
    try:
        archiver.start()
    except:
        print "Archiver Exiting."
main()

