import re
import uuid
import simplejson
import threading
import urllib2

import elasticsearch

#from dler.dler import DLer
from unpdfer import Unpdfer

from searchapi import Search
from access import Access

class Converter():

    def __init__(self,downloaddir='./downloads',DEBUG=False):
        #threading.Thread.__init__(self)

        self._stop = threading.Event()
        self._interval = 1 # 1 second

        self.downloaddir = downloaddir
        self.DEBUG = DEBUG

        self.unpdfer = Unpdfer()

        self.searchapi = Search()
        self.access = Access()

    def start(self):
        if self.DEBUG:
            print "Processor thread started."

        # start a timer to see if we should be exiting
        threading.Timer(self._interval,self.processdoc).start()

    def stop(self):

        # set our stop flag
        self._stop.set()

    def processdoc(self):
          
        #try: 
        if True: 
                
            if self.DEBUG:
                print 'Checking for unconverted documents ...'

            # get the next unconverted document
            doc = self.getunconverted()

            if doc == None:
 
                # All documents have been processed, nothing to do here.

                pass

            else:

                if self.DEBUG:
                    print 'Found a document to convert.'

                # decode fields
                pdffilename    = doc['docfilename']
                docurl         = doc['docurl']
                linktext       = doc['linktext']
                urldata        = doc['urldata']
                scrapedatetime = doc['scrapedatetime']

                if self.DEBUG:
                    print 'Converting PDF to text ...'

                # convert to text
                created,pdftext,pdfhash,success = self.getpdftext(pdffilename)

                if self.DEBUG:
                    print 'Saving PDF text to Elastic Search ...'

                # Save text doc to file store
                textfilename = "%s.txt" % pdffilename
                self.savetext(textfilename,pdftext)

                # decode the document name
                docname = urllib2.unquote(docurl.split('/')[-1])
                        
                # build elastic search entry
                body = {
                    'targeturl': urldata['targeturl'],
                    'docurl': docurl,
                    'docname': docname,
                    'linktext': linktext,
                    'pdftext': pdftext,
                    'pdfhash': pdfhash,
                    'scrapedatetime': scrapedatetime,
                    'textfilename': textfilename,
                    'pdffilename': pdffilename,
                    #'misfit': misfit,
                    #'orgname': org['name'],
                    #'orgid': org['orgid'],
                    #'bodyid': org['bodyid']
                }

                #
                # The document shouldn't be put into elastic search yet, since
                # we haven't processed the pdftext to see who the document 
                # belongs to.
                #
                # #index the document
                # self.sendtoelasticsearch(body)
                #

                # reset the converting flag
                self.setconverted(docurl)

                if self.DEBUG:
                    print "New document processed successfully."
        #except:
        #    if self.DEBUG:
        #        print "An error has happeend while trying to process the document."

        if not self._stop.isSet():
            # start a timer to see if we should be exiting
            threading.Timer(self._interval,self.processdoc).start()
        else:
            if self.DEBUG:
                print "Stop seen - not firing timer event."

    def getunconverted(self):
        doc = self.access.getunconverted()
        return doc

    def setconverted(self,docurl):
        self.access.setconverted(docurl)
        return True

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

    def savetext(self,filename,text):

        if self.DEBUG:
            print "Saving document text to filestore ..."

        with open(filename,"w") as f:
            f.write(text)

        if self.DEBUG:
            print "Document text successfully written to file store."

    def getpdftext(self,filename,SCRUB=True):

        if self.DEBUG:
            print "Trying to convert document to text ..."

        #unpdfer = Unpdfer(filename)
        created,pdftext,pdfhash,success = self.unpdfer.unpdf(filename,SCRUB=SCRUB,verbose=self.DEBUG)
        if success:
            retval = (created,pdftext,pdfhash,True)

            if self.DEBUG:
                print "Document successfully converted from PDF to Text."
        else:
            if self.DEBUG:
                print "Error in PDF->Text conversion:"
            retval = (None,None,None,False)

        return retval

    def sendtoelasticsearch(self,body):

        # push to es index
        success = self.searchapi.sendtoindex(body)

        if success:
           if self.DEBUG:
               print "Document added to the index successfully."
        else:
            if self.DEBUG:
               print "Document NOT added since it already exists."

if __name__ == '__main__':

    print "Launching Document Converter ..."

    dc = Converter(DEBUG=True)

    #print "starting ..."

    dc.start()

    #dc.stop()


    #org = {'id':0,
    #       'name': 'Town Board',
    #       'description': 'The Town Board',
    #       'creationdatetime': '2013-12-23 00:00:00 -0500',
    #       'matchtext': 'town board',
    #       'urlid': 0,
    #       'bodyid': 0,
    #      }
    #
    #pdftext = "The town board is meeting today at 5pm"
    #pdftext = "The town board is meeting is today."
    #
    #org, misfit = dp.orgmatch([org],pdftext)
    #
    #print "org: {0}, misfit: {1}".format(org,misfit)
