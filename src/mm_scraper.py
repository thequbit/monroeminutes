#from BarkingOwl.scraper.scraperwrapper import *

from barking_owl import ScraperWrapper

if __name__ == '__main__':

    print "Monroe Minutes Scraper Starting ..."

    exchange = "monroeminutes"
    try:
        sw = ScraperWrapper(exchange=exchange,DEBUG=True)
        sw.start()
    except:
        print "Monroe Minutes Scraper Exiting ..."


