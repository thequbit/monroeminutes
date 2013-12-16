from time import strftime
from BarkingOwl.dispatcher.barkingowl_dispatcher import Dispatcher

if __name__ == '__main__':
    print "Starting Monroe Minutes Scraper ..."

    url = {'targeturl': "http://timduffy.me/",
           'urlid': 1, # meta data not used by barkingowl
           'title': "TimDuffy.Me",
           'description': "Tim Duffy's Personal Website",
           'maxlinklevel': 3,
           'creationdatetime': str(strftime("%Y-%m-%d %H:%M:%S")),
           'doctype': 'application/pdf',
           'frequency': 2,
          }

    urls = [url]
    dispatcher = Dispatcher(address='localhost',exchange='barkingowl')
    dispatcher.seturls(urls)
    dispatcher.start()
