from BarkingOwl.scraper.scraperwrapper import *

if __name__ == '__main__':

    print "Monroe Minutes Scraper Starting ..."

    exchange = "monroeminutes"
    try:
        sw = ScraperWrapper(exchange=exchange)
        sw.start()
    except:
        print "Monroe Minutes Scraper Exiting ..."


