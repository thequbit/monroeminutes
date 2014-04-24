from access import Access
import threading

class Processor(object):

    def __init__(self,DEBUG=False):

        self._stop = threading.Event()
        self._interval = .01 # 10 miliseconds

        self.DEBUG = DEBUG
        self.access = Access()

    def start(self):
        if self.DEBUG:
            print "Processor thread started."

        # get the entity list
        self.entities = self.access.getentities()

        # start a timer to see if we should be exiting
        threading.Timer(self._interval,self.processdoc).start()

    def stop(self):
        if self.DEBUG:
            print "Processor thread stopping."

        # set our stop flag
        self._stop.set()

    def getorgs(self,entityid):

        allorgs = self.access.getorgs()

        orgs = []
        for org in allorgs:
            if org['entityid'] == entityid:
                orgs.append(org)

        return orgs

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
                smalltext      = pdftext[:512]
              
                if self.DEBUG:
                    print "Working on DocID '{0}', with EntityID: '{1}', OrgID: '{2}'".format(str(doc['_id']),str(doc['entityid']),str(doc['orgid']))

                matchfound = False
                orgs = self.getorgs(entityid)
                for org in orgs:
                    #if self.DEBUG:
                    #    print "Working on {0}".format(org['matches'])
                    if all(x in smalltext for x in org['matches']):
                    
                        if self.DEBUG:
                            print "Match Found!"

                        # update the database
                        newdoc = self.setdocorg(doc,org) 

                        #print "doc: {0}, newdoc: {1}".format(doc,newdoc)

                        # push to elastic search
                        if str(newdoc['orgid']) != '':
                            self.access.indexdoc(newdoc)

                        # set to processed
                        self.setprocessed(doc['docurl'])

                        matchfound = True

                        break

                if matchfound == False:
                 
                    if self.DEBUG:
                        print "No matches found for document."

        #except:
        #    if self.DEBUG:
        #        print "An error has happeend while trying to process the document."


        # start a timer to see if we should be exiting
        threading.Timer(self._interval,self.processdoc).start()

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

    def setdocorg(self,doc,org):

        newdoc = self.access.setdocorg(doc,str(org['_id']))

        return newdoc

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

    processor = Processor(DEBUG=True)

    processor.start()
    
