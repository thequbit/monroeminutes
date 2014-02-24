from access import Access

class Processor(object):

    def __init__(self,DEBUG=False):

        self._stop = threading.Event()
        self._interval = 1 # 1 second

        self.DEBUG = DEBUG
        self.access = Access()

    def start(self):
        if self.DEBUG:
            print "Processor thread started."

        # start a timer to see if we should be exiting
        threading.Timer(self._interval,self.processdoc).start()

    def stop(self):
        if self.DEBUG:
            print "Processor thread stopping."

        # set our stop flag
        self._stop.set()

    def processdoc(self):

        #try:
        if True:

            if self.DEBUG:
                print "Checking for unprocessed documents ..."

            # get the next unprocessed doc
            doc = self.getunprocessed()

            if doc == None:

                # all documents have been processed
                pass

            else:

                if self.DEBUG:
                    print 'Found a document to process.'

                # decode fields
                pdftext        = doc['pdftext']
                entityid       = doc['entityid']

                if self.DEBUG:
                    print 'Converting PDF to text ...'

                # convert to text
                created,pdftext,pdfhash,success = self.getpdftext(pdffilename)

                if not success:

                    if self.DEBUG:
                        print "An error has occured while converting the PDF."

                else:

                    if self.DEBUG:
                        print "Updating the document in the database ..."

                    # Save text doc to file store
                    textfilename = "%s.txt" % pdffilename
                    self.savetext(textfilename,pdftext)

                    # decode the document name
                    docname = urllib2.unquote(docurl.split('/')[-1])

                    # reset the converting flag
                    self.setconverted(docurl)

                    # set the pdf data for the doc
                    self.setpdfdata(pdftext,pdfhash,created)

                    if self.DEBUG:
                        print "New document converted successfully."


        #except:
        #    if self.DEBUG:
        #        print "An error has happeend while trying to process the document."

        # see if there is a doc that needs to be processed

        # try and match the org
        

        # save the process data to the database

        # push to index

        

    def getunprocessed(self):
        doc = self.access.getunprocessed()
        return doc

    def setprocessed(self,docurl):
        self.access.setprocessed(docurl)
        return True

    def orgmatch(self,pdfheader):
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


    def indexdoc(self):

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
        }

        # send to elastic search
        success = access.index(
            body=body
        )

        return success

if __name__ == '__main__':

    print " -- Monroe Minutes Document Processor --"

    processor = Processor()

    processor.start()
    
