from bs4 import BeautifulSoup
import urllib
import urllib2
import sys
import re

from pdfminer.pdfinterp import PDFResourceManager, process_pdf
from pdfminer.converter import TextConverter
from pdfminer.layout import LAParams
from cStringIO import StringIO

import nltk

def convert_pdf(path):

	rsrcmgr = PDFResourceManager()
	retstr = StringIO()
	codec = 'utf-8'
	laparams = LAParams()
	device = TextConverter(rsrcmgr, retstr, codec=codec, laparams=laparams)

	with open(path, 'rb') as fp:
		process_pdf(rsrcmgr, device, fp)
	device.close()

	str = retstr.getvalue()
	retstr.close()
	return str

def push_to_database(pdftext, fileurl, puplicationdate, fulltext)
	# TODO: Implement

def main(argv):

	#
	textpatern = argv[1] + "*";
	repatern = re.compile(textpatern);

	# get the url to scrape
	url = argv[2]
	
	# get the base url to append to the links
	baseurl = argv[3]
	
	# debug
	print "URL = " + url
	print "Patern = " + textpatern
	print "Append = " + baseurl

	# pull down our url source
	html = urllib2.urlopen(url)

	# create our bs object with the url source
	soup = BeautifulSoup(html)

	# get all a tags with an href
	atags = soup.find_all('a', href=True)

	# run through the tags and process the pdfs
	for tag in atags:
		if re.match(repatern, tag['href']):

			# generate some url and file names
			linkurl = baseurl + tag['href']
			filename = "./pdfs/" + tag['href'] + ".pdf"

			# pull down the pdf file
			print "Getting '" + linkurl + "' ..."
			#filename,headers = urllib.urlretrieve(linkurl,filename)
			print "Done."

			# get the text of the pdf file
			print "Converting PDF to text ..."
			pdftext = convert_pdf(filename)
			print "Done."

			# get all of the tokens for the file via the nltk
			print "Generating tokens from pdf text ..."
			tokens = nltk.word_tokenize(pdftext)
			fdist = nltk.FreqDist(word.lower() for word in tokens)
	                for token in [",","and","of","the","for","a","to","aye","nay","voting","town","supervisor","on","be","councilman", "councilmen","councilwoman","we","this","is","are","in","would","that","$","page","(",")","has","i","at","you","it","with","there","so","?","have","new","#","as",":","by","whereas","where","here","yes","no","now","from","but","not"]:
				if token in fdist:
					del fdist[token]			
			print "Done."

			# get publication date from pdf text
			print "Pulling publication date from PDF ..."
			# TODO: Implement
			print "Done."

			# push the data to the database
			print "Pushing document and token to database ..."
			# TODO: Implement
			push_to_database()
			print "Done."
			
if __name__ == '__main__': sys.exit(main(sys.argv))
