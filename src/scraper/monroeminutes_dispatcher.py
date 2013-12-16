from time import strftime
from BarkingOwl.dispatcher.barkingowl_dispatcher import Dispatcher

from models import Urls

def geturls():
    urls = Urls()
    urllist = urls.getall()
    return urllist

def packageurl(url):
    urlid,targeturl,title,description,maxlinklevel,creationdatetime,doctype,frequency,organizationid = url   
    pkg = {
        'urlid': urlid,                       # meta
        'targeturl': targeturl,               # required
        'title': title,                       # required
        'description': description,           # required
        'maxlinklevel': maxlinklevel,         # required
        'creationdatetime': creationdatetime, # required
        'doctype': doctype,                   # required
        'frequency': frequency,               # required
        'organizationid': organizationid      # meta
    }
    return pkg

if __name__ == '__main__':
    print "Monroe Minutes Dispatcher Starting ..."

    #url = {'targeturl': "http://timduffy.me/",
    #       'urlid': 1, # meta data not used by barkingowl
    #       'title': "TimDuffy.Me",
    #       'description': "Tim Duffy's Personal Website",
    #       'maxlinklevel': 3,
    #       'creationdatetime': str(strftime("%Y-%m-%d %H:%M:%S")),
    #       'doctype': 'application/pdf',
    #       'frequency': 2,
    #      }

    # create our BarkingOwl dispatcher
    dispatcher = Dispatcher(address='localhost',exchange='monroeminutes')
    
    # get the list of URLs to dispatch
    urls = []
    urllist = geturls()
    for url in urllist:
        urls.append(packageurl(url))

    if len(urls) == 0:
        print "WARNING: URL list is empty."

    # start the dispatcher with the URL list
    dispatcher.seturls(urls)
    dispatcher.start()

    print "Monre Minutes Dispatcher Exiting ..."
