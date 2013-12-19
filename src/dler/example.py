import time
from dler import DLer
from pprint import pprint

def main():

    links = []
    links.append(('http://www.google.com/',"Google"))
    links.append(('http://www.yahoo.com/?s=https',"Yahoo"))
    links.append(('https://www.facebook.com/',"Facebook"))

    start_time = time.time()
    
    dler = DLer()
    files,success = dler.dl(links,'./downloads')

    end_time = time.time()

    pprint(files)

    print ""
    print "---- STATS ----"
    print ""
    print "URL Count: {0}".format(len(links))
    print "File Count: {0}".format(len(files))
    print "Success: {0}".format(success)
    print "Total Time: {0}".format(end_time-start_time)
    print ""


main()
