from bs4 import BeautifulSoup
import urllib
import urllib2
import sys
import re

from pdfminer.pdfinterp import PDFResourceManager, process_pdf
from pdfminer.converter import TextConverter
from pdfminer.layout import LAParams
from cStringIO import StringIO

#import pyPdf

import nltk

import _mysql as mysql

import datetime

from random import randint

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

def convert_pdf(path):

	try:
		rsrcmgr = PDFResourceManager()
		retstr = StringIO()
		codec = 'utf-8'
		laparams = LAParams()
		laparams.all_texts = True
		device = TextConverter(rsrcmgr, retstr, codec=codec, laparams=laparams)
		#evice = TextConverter(rsrcmgr, retstr, laparams=laparams)
	
		with open(path, 'rb') as fp:
			process_pdf(rsrcmgr, device, fp)
		device.close()
	
		retVal = (retstr.getvalue(),True)
		retstr.close()
		
		#retVal = ""
	
		#pdf = pyPdf.PdfFileReader(open(path, "rb"))
		#for page in pdf.pages:
		#	retVal += page.extractText()
		#	print page.extractText()

	except:
		print "PDF is not formatted correctly, aborting."
		retVal = ("", False)
		pass

	return retVal

def push_to_database(suborgid, database, fdist, sourceurl, publicationdate, docname, pdftext):
	
	print "\t\tPushing document to DB ..."
	
	# get current date
	now = datetime.datetime.now()
	str_now = now.date().isoformat()

	# insert the doc into the database
	query = 'insert into documents (suborganizationid,sourceurl,documentdate,scrapedate,name,documenttext) values({0},"{1}","{2}","{3}","{4}","{5}")'.format(suborgid,mysql.escape_string(sourceurl),publicationdate,str_now,mysql.escape_string(docname),mysql.escape_string(pdftext))
	database.query(query)

	# get the id of the doc we just inserted
	query = 'select documentid from documents where sourceurl="%s"' % sourceurl
	database.query(query)
	dbresult=database.store_result()
	(documentid,),=dbresult.fetch_row()

	print "\t\tDone."

	print "\t\tPushing %i tokens to DB ..." % len(fdist)
	
	# insert all of the words into the database
	for token,frequency in fdist.items():	
		query = 'insert into wordfrequency (documentid,word,frequency) values("{0}","{1}","{2}")'.format(documentid,mysql.escape_string(token),frequency)
		database.query(query)

	print "\t\tDone."

	# all done
	return

def main(argv):

	if len(argv) != 5:
		print "Usage:"
		print "\tgetpdfs.py <suborgid> <textpatern> <url> <baseurl>\n"
		print "\t<suborgid>     This is the Suborganization ID within the database for this suborg"
		print "\t<textpatern>   This is the patern to look for in the URL.  An example may be a file extension."
		print "\t<url>>         This is the URL to look at for documents.  This is the page with all of the PDF links on it."
		print "\t<baseurl>      This is the base URL to append to any relative path links."

		return 0

	# get the suborgid
	suborgid = argv[1]

	# get the patern that we are looking for in the URL
	textpatern = argv[2]

	# get the url to scrape
	url = argv[3]
	
	# get the base url to append to the links
	baseurl = argv[4]
	
	# debug
	print "SubOrgID = " + suborgid
	print "URL = " + url
	print "Patern = " + textpatern
	print "Append = " + baseurl

	# pull down our url source
	html = urllib2.urlopen(url)

	# create our bs object with the url source
	soup = BeautifulSoup(html)

	# get all a tags with an href
	atags = soup.find_all('a', href=True)

	print "Found %i links on page" % len(atags)

	# connect to DB
	print "Connecting to DB ..."
	
	# get our db info from our local file
        dbcreds = get_mysql_credentials()

        # decode responce
        host = dbcreds[0].rstrip()
        dbname = dbcreds[1].rstrip()
        username = dbcreds[2].rstrip()
        password = dbcreds[3].rstrip()
	
	# connect to db	
	database=mysql.connect(host=host,user=username,passwd=password,db=dbname)

	print "Done."

	# run through the tags and process the pdfs
	for tag in atags:
		#print "Testing URL = %s .." % tag['href']

		if textpatern in tag['href']:

			print "\tDocument Found!"

			# generate some url and file names
			linkurl = baseurl + tag['href']
			urlfile = tag['href'][tag['href'].rfind("/")+1:]
			filename = "./pdfs/{0}_{1}.pdf".format(urlfile,randint(0,1000000))

			# pull down the pdf file
			print "\tGetting '" + linkurl + "' ..."
			filename,headers = urllib.urlretrieve(linkurl,filename)
			print "\tDone."

			# get the document name from the pdf text
			print "\tPulling document date from PDF ..."
			docname = tag.get_text()
			print "\tDone."


			# get the text of the pdf file
			print "\tConverting PDF to text ..."
			pdftext,pdfsuccess = convert_pdf(filename)
			print "\tDone."

			if pdfsuccess == False:

				print "\t!!! PDF Formated badly, excluding !!!"

			else:

				# get all of the tokens for the file via the nltk
				print "\tGenerating tokens from pdf text ..."
				tokens = nltk.word_tokenize(pdftext)
				fdist = nltk.FreqDist(word.lower() for word in tokens)
	                
				for token in [",","and","of","the","for","a","to","aye","nay","voting","town","supervisor","on","be","councilman", "councilmen","councilwoman","we","this","is","are","in","would","that","$","page","(",")","has","i","at","you","it","with","there","so","?","have","new","#","as",":","by","whereas","where","here","yes","no","now","from","but","not"]:
					if token in fdist:
						del fdist[token]			
			
				for token,frequency in fdist.items():
					if len(token) < 4:
						del fdist[token]
	
				print "\tDone."
	
				# get publication date from pdf text
				print "\tPulling publication date from PDF ..."
				publicationdate = "1970-01-01";
				print "\tDone."

				# push the data to the database
				print "\tPushing document and token to database ..."
				#suborgid, database, fdist, sourceurl, puplicationdate, docname, pdftext
				push_to_database(suborgid,database,fdist,linkurl,publicationdate,docname,pdftext)
				print "\tDone."
			
if __name__ == '__main__': sys.exit(main(sys.argv))
