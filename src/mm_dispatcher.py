from time import strftime

from barking_owl import Dispatcher

from access import Access

if __name__ == '__main__':
    
    print " -- Monroe Minutes Dispatcher --"

    # create our BarkingOwl dispatcher and our db access layer
    dispatcher = Dispatcher(address='localhost',exchange='monroeminutes')
    access = Access()

    print "Getting list of URLs to work on ..."

    # get urls
    entities = access.getentities()

    # build urls
    urls = []
    for entity in entities:
        now = str(strftime("%Y-%m-%d %H:%M:%S"))
        pkg = {
            'targeturl':         entity['website'],     # url to scrape
            'title':             entity['name'],        # town/village/city name
            'description':       entity['description'], # town/village/city description
            'maxlinklevel':      4,                     # if its more than this, we're screwed ...
            'creationdatetime':  now,                   # current ISO date/time
            'doctype':           'application/pdf',     # pdf documents
            'frequency':         604800,                # in minutes
        }
        urls.append(pkg)
    
    # set urls
    dispatcher.seturls(urls)

    print "Starting Dispatcher ..."

    # start the dispatcher with the URL list
    try:
        dispatcher.seturls(urls)
        dispatcher.start()
    except:
        # all done!
        print "Exiting Dispatcher."

