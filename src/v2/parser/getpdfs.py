import sys

import MySQLdb as mdb

from bs4 import BeautifulSoup
import urllib
import urllib2

import pdfparser

import datetime

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
                
		#query = "select Organizations.bodyid as bodyid, ScrapURLs.url as url, ScrapURLs.scrapurlid as scrapurlid from Organizations, ScrapURLs where Organizations.organizationid = ScrapURLs.organizationid and ScrapURLs.enabled=1"
		
		query = "SELECT bodyid,url,scrapurlid FROM ScrapURLs WHERE enabled=1"
		cur.execute(query)                
		rows = cur.fetchall()

		for row in rows:
			bodyid,url,urlid = row
			scrapURLs.append((bodyid,url,urlid))
			print "\tAdded '{0}'".format(url)

	return scrapURLs

def report_bad_linkurl(scrapurlid,linkurl,bodyid,validpdf):

	auth = get_mysql_credentials()

        con = mdb.connect(auth[0].strip(), auth[2].strip(), auth[3].strip(), auth[1].strip());

        with con:

                cur = con.cursor()
                query = 'INSERT INTO BadURLs(scrapurlid,linkurl,bodyid,validpdf) VALUES({0}, "{1}", {2}, {3})'.format(scrapurlid,linkurl,bodyid,int(validpdf))
                cur.execute(query)

def get_bad_linkurls(bodyid):

	badlinkurls = []

	auth = get_mysql_credentials()

        con = mdb.connect(auth[0].strip(), auth[2].strip(), auth[3].strip(), auth[1].strip());

        with con:

                cur = con.cursor()
                query = 'SELECT linkurl FROM BadURLs WHERE bodyid={0}'.format(bodyid)
                cur.execute(query)
		rows = cur.fetchall()
		
		for row in rows:
			badlink, = row
			badlinkurls.append(badlink)

	return badlinkurls

def report_orphan(sourceurl,scrapurlid,bodyid):

	auth = get_mysql_credentials()

        con = mdb.connect(auth[0].strip(), auth[2].strip(), auth[3].strip(), auth[1].strip());

        with con:

                cur = con.cursor()
                query = 'INSERT INTO Orphans(scrapdt,sourceurl,scrapurlid,bodyid) VALUES("{0}","{1}",{2},{3})'.format(datetime.datetime.now().date().isoformat(),sourceurl,scrapurlid,bodyid)
                cur.execute(query)


def get_orphans(bodyid):

	orphanlinkurls = []

        auth = get_mysql_credentials()

        con = mdb.connect(auth[0].strip(), auth[2].strip(), auth[3].strip(), auth[1].strip());

        with con:

                cur = con.cursor()
                query = 'SELECT sourceurl FROM Orphans WHERE bodyid={0}'.format(bodyid)
                cur.execute(query)
                rows = cur.fetchall()

                for row in rows:
                        orphanlink, = row
                        orphanlinkurls.append(orphanlink)

        return orphanlinkurls

def main(argv):

	print "Application Started."

	print "Getting List of URLs to Scrap"

	urldatas = get_scrap_urls()

	print "Done."

	#print "Processing Link URLs."

	docCount = 0

	for urldata in urldatas:
		
		print "Processing Links on Page."

		bodyid,url,scrapurlid = urldata

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
			badlinkurls = get_bad_linkurls(bodyid)

			# get the list of known orphans
			orphanlinkurls = get_orphans(bodyid)

			if not (linkurl in badlinkurls) and not (linkurl in orphanlinkurls):
				#print "\tIgnoring Link - Known 'Bad URL'."
				
			#else:
				# send it to be parsed
				success = pdfparser.parsepdf(linkurl,scrapurlid,bodyid)

				if success == "Successful":
					docCount += 1
					print "\t\tINFO: Parse Successful, Document Count = {0}".format(docCount)
				elif success == "Ignore":
					print "\t\tINFO: Valid PDF, but duplicate hash.  Adding to Bad URL List."
					report_bad_linkurl(scrapurlid,linkurl,bodyid,True)
				elif success == "NonPDF":
					print "\t\tINFO: Bad Link.  Adding to Bad URL List."
					report_bad_linkurl(scrapurlid,linkurl,bodyid,False)
				elif success == "Error":
					print "\t\tINFO: An Orphin has been entered in the Database."
					report_orphan(linkurl,scrapurlid,bodyid)
				else:
					print "\t\tINFO: !!! PDFParser Returned something weird ..."
					
		print "Done."	


	print ""
	print "\t{0} Documents Processed.".format(docCount)
	print ""

	print "Done."

	print "Application Exiting."

if __name__ == '__main__': sys.exit(main(sys.argv)) 
