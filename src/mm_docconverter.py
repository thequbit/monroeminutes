import re
import uuid
import simplejson
import threading
import urllib2

#import elasticsearch

#from dler.dler import DLer
from unpdfer import Unpdfer

from barking_owl import BusAccess

#from searchapi import Search
from access import Access

class Converter():

    def __init__(self,downloaddir='./downloads',entityid=None,DEBUG=False):
        #threading.Thread.__init__(self)

        self._stop = threading.Event()
        self._interval = .01 # 10 milisseconds

        self.downloaddir = downloaddir
        self.entityid = entityid
        self.DEBUG = DEBUG

        if self.DEBUG:
            if self.entityid != None:
                print "Using conditional EntityID."

        self.unpdfer = Unpdfer()

        #self.searchapi = Search()
        self.myid = str(uuid.uuid4())
        self.dbaccess = Access(DEBUG=True)

        # setup access layer
        #self.myid = str(uuid.uuid4())
        #self.busaccess = BusAccess(myid=self.myid,DEBUG=True)
        #self.busaccess.setcallback(self._callback)
        
        # start seperate thread with listener in it
        #self.listenthread = threading.Thread(target=self.busaccess.listen)
        #self.listenthread.start()

        if self.DEBUG:
            print "Converter INIT completed successfully."

    #def _callback(self,response):
    #
    #    if self.DEBUG:
    #        print "Callback called."
    #
    #    if response['command'] == 'global_shutdown':
    #        if self.DEBUG:
    #            print "Global Shutdown Recieved"
    #        self.busaccess.stoplistening()
    #        self.stop()

    def start(self):
        if self.DEBUG:
            print "Converter thread started."

        #self.busaccess.sendmsg(
        #    command='mm_converter_online',
        #    destinationid='broadcast',
        #    message={
        #        'datetime': str(strftime("%Y-%m-%d %H:%M:%S"))
        #    },
        #)

        # start a timer to see if we should be exiting
        threading.Timer(self._interval,self.convertdoc).start()

        if self.DEBUG:
            print "start() exiting."

    def stop(self):
        if self.DEBUG:
            print "Converter thread is stopping."

        #self.busaccess.sendmsg(
        #    command='mm_converter_offline',
        #    destinationid='broadcast',
        #    message={
        #        'datetime': str(strftime("%Y-%m-%d %H:%M:%S"))
        #    },
        #)

        self.listenthread.stop()

        # set our stop flag
        self._stop.set()

    def convertdoc(self):

        if self.DEBUG:
            print "Entering convertdoc() ..."

        #print "exiting convertdoc() because of debug."
        #return
          
        #try: 
        if True: 
                
            if self.DEBUG:
                print 'Checking for unconverted documents ...'

            # get the next unconverted document
            doc = self.getunconverted(self.entityid)

            if doc == None:
 
                # All documents have been converted, nothing to do here.

                if self.DEBUG:
                    print "No documents to process."

                pass

            else:

                #if self.entityid != None:
                #    if self.DEBUG:
                #        print "Doc EntityID: '{0}', Conditional EntityID: '{1}'".format(doc['entityid'], self.entityid)
                #    if doc['entityid'] != self.entityid:
                #        if self.DEBUG:
                #            print "Specific EntityID specified, but no match found.  Skipping document."
                #        pass
                #
                #else:

                if True:

                    if self.DEBUG:
                        print 'Found a document to convert.'

                    print doc

                    # decode fields
                    pdffilename    = doc['docname']
                    docurl         = doc['docurl']
                    linktext       = doc['linktext']
                    urldata        = doc['urldata']
                    scrapedatetime = doc['scrapedatetime']

                    if self.DEBUG:
                        print 'Converting PDF to text ...'

                    # convert to text
                    created,pdftext,pdfhash,success = self.getpdftext(pdffilename)

                    if not success:
 
                        if self.DEBUG:
                            print "An error has occured while converting the PDF."

                    else:

                        if self.DEBUG:
                            print "Saving document text to file store ..."

                        # Save text doc to file store
                        textfilename = "%s.txt" % pdffilename
                        self.savetext(textfilename,pdftext)

                        # decode the document name
                        docname = urllib2.unquote(docurl.split('/')[-1])

                        if self.DEBUG:
                            print "Document saved: {0}".format(textfilename)

                        #raise Exception('debug')

                        if self.DEBUG:
                            print "Placing document text into database ..."

                        # reset the converting flag
                        self.setconverted(docurl)

                        # set the pdf data for the doc
                        self.setconvertdata(docurl,pdftext,pdfhash,created)

                        if self.DEBUG:
                            print "New document converted successfully."
        #except:
        #    if self.DEBUG:
        #        print "An error has happeend while trying to convert the document."

        if not self._stop.isSet():
            # start a timer to see if we should be exiting
            threading.Timer(self._interval,self.convertdoc).start()
        else:
            if self.DEBUG:
                print "Stop seen - not firing timer event."

    def getunconverted(self,entityid):
        doc = self.dbaccess.getunconverted(entityid)
        return doc

    def setconverted(self,docurl):
        doc = self.dbaccess.setconverted(docurl)
        return doc

    def setconvertdata(self,docurl,pdftext,pdfhash,created):
        doc = self.dbaccess.setconvertdata(docurl,pdftext,pdfhash,created)
        print "Document Converted: {0}".format(doc)
        return doc

    def savetext(self,filename,text):

        if self.DEBUG:
            print "Saving document text to filestore ..."
            print "Document length: {0}".format(len(text))

        # note: will overwrite any existing file
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

if __name__ == '__main__':

    print " -- Monroe Minutes Document Converter --"

    hen_id = '530c00586c5bc4160b2e913c'

    dc = Converter(DEBUG=True) #, entityid=hen_id)

    try:
        dc.start()
    except:
        print "Exiting."
        pass

