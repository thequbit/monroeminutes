from time import strftime

from barking_owl import Dispatcher

from access import Access

class Dispatch(object):

    def __init__(self,address='localhost',exchange='monroeminutes'):

        self.dispatcher = Dispatcher(address=address,exchange=exchange)
        self.access = Access()

        self.urls = []

    def _getentities(self):

        # get urls
        entities = self.access.getentities()

        # build urls
        urls = []
        for entity in entities:
            now = str(strftime("%Y-%m-%d %H:%M:%S"))
            pkg = {
                'targeturl':         entity['website'],     # url to scrape
                'title':             entity['name'],        # town/village/city name
                'description':       entity['description'], # town/village/city description
                'entityid':          str(entity['_id']),    # entityid
                'maxlinklevel':      4,                     # if its more than this, we're screwed ...
                'creationdatetime':  now,                   # current ISO date/time
                'doctype':           'application/pdf',     # pdf documents
                'frequency':         10080,                 # in minutes
            }
            urls.append(pkg)
        
        return urls

    def updateurls(self):

        # get the entities list
        urls = self._getentities()

        # set urls
        self.dispatcher.seturls(urls)

    def start(self):

        print "Starting Dispatcher ..."

        # start the dispatcher with the URL list
        #try:
        if True:
            self.dispatcher.start()
        #except:
        #    # all done!
        #    print "Exiting Dispatcher." 

if __name__ == '__main__':
    
    print " -- Monroe Minutes Dispatcher --"

    dispatch = Dispatch(address='localhost',exchange='monroeminutes')

    dispatch.updateurls()

    dispatch.start()


