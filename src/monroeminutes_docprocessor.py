import re
import uuid
import simplejson
import threading
import urllib2

import elasticsearch

from dler.dler import DLer
from unpdfer.unpdfer import UnPDFer

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

            #targeturls = self.geturlslist()
            #targeturl = urldata['targeturl']

            #if self.DEBUG:
            #    print "Target URL: '{0}'".format(targeturl)

            # before we start downloading and processing the pdf file we want to
            # make sure that the url is within the url list that is in the database
            if True: #targeturl in targeturls:
                
                if self.DEBUG:
                    print "Target URL within database, processing new document ..."
                    print "URL Data: \n    {0}".format(urldata)

                # 1. Download and save pdf
                pdffilename,success = self.downloadpdf(docurl)
               
                # 2. Convert PDF to Text
                created,pdftext,pdfhash,success = self.getpdftext(pdffilename)

                if success == False:
                    if self.DEBUG:
                        print "Document NOT processed successfully."
                else:

                    # Save text doc to file store
                    textfilename = "%s.txt" % pdffilename
                    self.savetext(textfilename,pdftext)

                    # Save doc to DB
                    #docid = self.savedoctodb(docurl,linktext,urldata['urlid'],scrapedatetime,pdfhash,textfilename,pdffilename)

                    # match the org.  If none is matched, then mark as a misfit.
                    orgs = urldata['orgs']
                    org,misfit = self.orgmatch(orgs,pdftext[:8192]) # pass in the first 8K of text

                    # build elastic search entry
                    docname = urllib2.unquote(docurl.split('/')[-1])
                    body = {'targeturl': urldata['targeturl'],
                            'docurl': docurl,
                            'docname': docname,
                            'linktext': linktext,
                            #'docid': docid,
                            'pdftext': pdftext,
                            'pdfhash': pdfhash,
                            'scrapedatetime': scrapedatetime,
                            'textfilename': textfilename,
                            'pdffilename': pdffilename,
                            'misfit': misfit,
                            'orgname': org['name'],
                            'orgid': org['orgid'],
                            'bodyid': org['bodyid']
                           }

                    self.sendtoelasticsearch(body)

                    if self.DEBUG:
                        print "New document processed successfully."
            else:
                print "Target URL not within the database, skipping."

    def orgmatch(self,orgs,pdftext):
        misfit = True
        org = {}
        org['name'] = ""
        org['orgid'] = 0
        org['bodyid'] = 0
        for o in orgs:
            if self.DEBUG:
                print "matchtext: %s" % o['matchtext']
            regexstr = "( +)?"
            for i in range(0,len(o['matchtext'])):
                if o['matchtext'][i] != ' ':
                    regexstr += "%s( +)?" % o['matchtext'][i]
            if self.DEBUG:
                print "regexstr: '%s'" % regexstr
            if re.search(regexstr.lower(),pdftext.lower()):
                if self.DEBUG:
                    print "Match found for '%s'" % o['name']
                    print "ORG: {0}".format(o)
                org = o
                misfit = False
                break

        return org,misfit

#    def geturlslist(self):
#        if self.DEBUG:
#            print "Getting list of URLs from the database."
#
#        urls = Urls()
#        _urls = urls.getall()
#        urllist = []
#        for url in _urls:
#            urllist.append(url[1])
#
#        if self.DEBUG:
#            print "URL List: {0}".format(urllist)
#
#        return urllist

    def downloadpdf(self,url):

        success = True

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
            #raise Exception("File Download Error.")
            print "Error downloading document."
            success = False
    
        if self.DEBUG:
            print "PDF document successfully downloaded to filestore."

        return filename,success

    def savetext(self,filename,text):

        if self.DEBUG:
            print "Saving document text to filestore ..."

        with open(filename,"w") as f:
            f.write(text)

        if self.DEBUG:
            print "Document text successfully written to file store."

    #def savedoctodb(self,docurl,linktext,targeturlid,scrapedatetime,pdfhash,textfilename,pdffilename):
    #    if self.DEBUG:
    #        print "Saving document to database ..."
    #        #print "    docurl: {0}".format(docurl)
    #        #print "    linktext: {0}".format(linktext)
    #        #print "    targeturlid: {0}".format(targeturlid)
    #        #print "    scrapedatetime: {0}".format(scrapedatetime)
    #        #print "    pdfhash: {0}".format(pdfhash)
    #        #print "    textfilename: {0}".format(textfilename)
    #        #print "    pdffilename: {0}".format(pdffilename)
    #
    #    docs = Docs()
    #    docid = docs.add(docurl,linktext,targeturlid,scrapedatetime,pdfhash,textfilename,pdffilename)
    #
    #    if self.DEBUG:
    #        print "Document successfully added to database."
    #
    #    return docid

    def getpdftext(self,filename,SCRUB=True):

        if self.DEBUG:
            print "Trying to convert document to text ..."

        unpdfer = UnPDFer(filename)
        created,pdftext,pdfhash,success = unpdfer.unpdf(filename,SCRUB=SCRUB)
        if success:
            retval = (created,pdftext,pdfhash,True)
        else:
            #raise Exception("Error in PDF->Text conversion")
            if self.DEBUG:
                print "Error in PDF->Text conversion"
            retval = (None,None,None,False)

        if self.DEBUG:
            print "Document successfully converted from PDF to Text."

        return retval

    ####
    #
    # Elastic Search Functions
    #
    ####

    def sendtoelasticsearch(self,body): #targeturl,docurl,docname,linktext,docid,pdftext,pdfhash,scrapedatetime,textfilename,pdffilename):

        print "Attempting to push document to ElasticSearch ..."

        es = elasticsearch.Elasticsearch() # TODO: implement server definition rather than just localhost

        es.index(
            index="monroeminutes",
            doc_type="pdfdoc",
            id=uuid.uuid4(),
            body=body,
        )

        print "Document successfully loaded into Elastic Search Indexer."


if __name__ == '__main__':

    print "Testing ..."

    dp = DocProcessor(DEBUG=True)

    org = {'id':0,
           'name': 'Town Board',
           'description': 'The Town Board',
           'creationdatetime': '2013-12-23 00:00:00 -0500',
           'matchtext': 'town board',
           'urlid': 0,
           'bodyid': 0,
          }

    pdftext = "The town board is meeting today at 5pm"
    #pdftext = "The town board is meeting is today."

    org, misfit = dp.orgmatch([org],pdftext)

    print "org: {0}, misfit: {1}".format(org,misfit)
