import sys

import MySQLdb as mdb

from bs4 import BeautifulSoup
import urllib
import urllib2

import pdfparser

def get_mysql_credentials():
        # read in credentials file
        lines = tuple(open('mysqlcreds.txt', 'r'))

        # return the tuple of the lines in the file
        #
        # host
        # dbname
        # username
        # password
        #
        return lines

def get_scrap_urls():

	scrapURLs = []

	auth = get_mysql_credentials()

	con = mdb.connect(auth[0].strip(), auth[2].strip(), auth[3].strip(), auth[1].strip());

        with con:

                cur = con.cursor()
                
		query = "SELECT url,scrapurlid FROM ScrapURLs"
		cur.execute(query)                
		rows = cur.fetchall()

		for row in rows:
			url,urlid = row
			scrapURLs.append((url,urlid))
			print "\tAdded '{0}'".format(url)

	return scrapURLs

def report_bad_linkurl(scrapurlid,linkurl):

	auth = get_mysql_credentials()

        con = mdb.connect(auth[0].strip(), auth[2].strip(), auth[3].strip(), auth[1].strip());

        with con:

                cur = con.cursor()
                query = 'INSERT INTO BadURLs(scrapurlid,linkurl) VALUES("{0}", "{1}")'.format(scrapurlid,linkurl)
                cur.execute(query)

def get_bad_linkurls():

	badlinkurls = []

	auth = get_mysql_credentials()

        con = mdb.connect(auth[0].strip(), auth[2].strip(), auth[3].strip(), auth[1].strip());

        with con:

                cur = con.cursor()
                query = 'SELECT linkurl FROM BadURLs'
                cur.execute(query)
		rows = cur.fetchall()
		
		for row in rows:
			badlink, = row
			badlinkurls.append(badlink)

	return badlinkurls

def main(argv):

	print "Application Started."

	print "Getting List of URLs to Scrap"

	urldatas = get_scrap_urls()

	print "Done."

	#print "Processing Link URLs."

	docCount = 0

	for urldata in urldatas:
		
		print "Processing Links on Page."

		url,scrapurlid = urldata

		baseurl = url[:url.rfind("/")+1]
		siteurl = url[:url.find("/",7)] # making the assumption the url starts with http:// ... although almost all url's will be longer thatn 7 chars.

		# pull down the doc and get all of the <a> tags with href links in them
		html = urllib2.urlopen(url)
		soup = BeautifulSoup(html)
		atags = soup.find_all('a', href=True)

		print "\tFound {0} Links on Page".format(len(atags))
		
		for tag in atags:

			# make sure the URL is formatted correctly
			if tag['href'][0:7].lower() == "http://":
                                linkurl = tag['href']
                        else:
				if tag['href'][0:1] == "/":
					# absolute link
					linkurl = siteurl + tag['href']
				else: 
					# relative link
                                	linkurl = baseurl + tag['href']

			# get the list of known bad linkurl's
			badlinkurls = get_bad_linkurls()			

			if linkurl in badlinkurls:
				print "\tIgnoring Link, Known Non-PDF file."
			else:
				# send it to be parsed
				success = pdfparser.parsepdf(linkurl)

				if success == True:
					docCount += 1
					print "\tParse Successful, Document Count = {0}".format(docCount)
				else:
					print "\tBad Link.  Adding to Bad URL List."
					report_bad_linkurl(scrapurlid,linkurl)
		print "Done."	


	print ""
	print "\t{0} Documents Processed.".format(docCount)
	print ""

	print "Done."

	print "Application Exiting."

if __name__ == '__main__': sys.exit(main(sys.argv)) 
