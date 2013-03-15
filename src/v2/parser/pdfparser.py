import sys

from random import randint

import urllib
import urllib2

import MySQLdb as mdb
import _mysql as mysql

from pdfminer.pdfinterp import PDFResourceManager, process_pdf
from pdfminer.converter import TextConverter
from pdfminer.layout import LAParams

from cStringIO import StringIO

import parsedatetime as pdt
import datetime

import nltk

import hashlib

def convert_pdf(path):

        try:
                rsrcmgr = PDFResourceManager()
                retstr = StringIO()
                codec = 'ascii'
                laparams = LAParams()
                laparams.all_texts = True
                device = TextConverter(rsrcmgr, retstr, codec=codec, laparams=laparams)
                #evice = TextConverter(rsrcmgr, retstr, laparams=laparams)

                with open(path, 'rb') as fp:
                        process_pdf(rsrcmgr, device, fp)
                device.close()

                # un-fuck the non-utf8 string ...
                txt = retstr.getvalue()

                retVal = (txt,True)
                retstr.close()

                #retVal = ""

                #pdf = pyPdf.PdfFileReader(open(path, "rb"))
                #for page in pdf.pages:
                #       retVal += page.extractText()
                #       print page.extractText()

        except:
                #print "\tERROR: PDF is not formatted correctly, aborting."
                retVal = ("", False)
                pass

        return retVal

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

def parse_org(text,orgs):

	retName = None
	retID = None
	success = False

	for org in orgs:
		orgName,orgID = org
		# check for orgname in pdftext ... note it is looked at reguardless of case because
		# it is often different between docs ....
		if orgName.lower() in text.lower():
			retName = orgName
			retID = orgID
			success = True
			break

	return (retName,retID,success)

def get_orgs(bodyid):

	orgNames = []

	auth = get_mysql_credentials()

	con = mdb.connect(auth[0].strip(), auth[2].strip(), auth[3].strip(), auth[1].strip());	

	with con:

		cur = con.cursor()
		query = "SELECT docparsename,organizationid FROM Organizations WHERE bodyid={0}".format(bodyid)
		cur.execute(query)
		rows = cur.fetchall()

		for row in rows:
			parseName,orgId, = row
			# and org may exist in the DB that does not yet have any minutes, so we don't
			# know what the parse string match will be
			if parseName != "":
				orgNames.append((parseName,orgId))
			
	return orgNames

def push_document(pdfText,docname,sourceURL,publishDate,organizationID,scrapurlid,textHash,bodyid):

	pdfText = pdfText.encode('ascii','ignore')
	sourceURL = sourceURL.encode('ascii','ignore')

	auth = get_mysql_credentials()

        con = mdb.connect(auth[0].strip(), auth[2].strip(), auth[3].strip(), auth[1].strip())

        with con:

                cur = con.cursor()
		query = 'INSERT INTO Documents(scrapdt,publishdate,docname,organizationid,sourceurl,doctext,scrapurlid,hash,bodyid) VALUES("{0}", "{1}", "{2}", {3}, "{4}", "{5}",{6},"{7}","{8}")'.format(datetime.datetime.now().isoformat(),publishDate,docname,organizationID,mysql.escape_string(sourceURL),mysql.escape_string(pdfText),scrapurlid,textHash,bodyid)
		cur.execute(query)

		query = 'SELECT documentid FROM Documents WHERE sourceurl="{0}" AND organizationid={1}'.format(sourceURL,organizationID)
		cur.execute(query)
		row = cur.fetchone()

	docid, = row

	return docid

def push_words(pdfText,docid):

	# get ignore list from the database

	# todo: this.

	# remove all punctuation from the text block
	pdfTextScrubbed = pdfText.replace(',','').replace('.','').replace('?','').replace('/',' ').replace(':','').replace(';','').replace('<','').replace('>','').replace('[','').replace(']','').replace('\\',' ').replace('"','').replace("'",'').replace('`','')

	# generate histogram
	tokens = nltk.word_tokenize(pdfTextScrubbed)
	fdist = nltk.FreqDist(word.lower() for word in tokens)

	#for token,frequency in fdist.items():
	#	if len(token) < 5:
	#		del fdist[token]
	#
	#	elif token.find(" ") or token.find("\n"):
	#		del fdist[token]
	#
	#	# todo: remove ignore list workds
		

	# push words to the database
	
	auth = get_mysql_credentials()

        con = mdb.connect(auth[0].strip(), auth[2].strip(), auth[3].strip(), auth[1].strip())

        with con:

                cur = con.cursor()
		
		for token,frequency in fdist.items():
			if len(token) > 3: # why 3? because fuck you thats why
		                query = 'INSERT INTO Words(word,documentid,frequency) VALUES("{0}","{1}","{2}")'.format(token,docid,frequency)
        		        cur.execute(query)

	print "\t\t\tPARSER: Pushed {0} Words to DB.".format(len(fdist.items()))

def check_url_exists(sourceURL):

	exists = False

	auth = get_mysql_credentials()

        con = mdb.connect(auth[0].strip(), auth[2].strip(), auth[3].strip(), auth[1].strip());

        with con:

                cur = con.cursor()
                query = 'SELECT count(documentid) as count FROM Documents WHERE sourceurl="{0}"'.format(sourceURL)
                cur.execute(query)
		row = cur.fetchone()
		
		count, = row

		if count == 0:
			exists = False
		else:
			exists = True

	return exists

def check_hash_exists(pdfText,url):

	exists = False

	# get hash
	textHash = hashlib.md5(pdfText).hexdigest()

	auth = get_mysql_credentials()

        con = mdb.connect(auth[0].strip(), auth[2].strip(), auth[3].strip(), auth[1].strip());

        with con:

                cur = con.cursor()
                
		# see if the hash exists in the DB
		query = 'SELECT count(documentid) as count FROM Documents WHERE hash="{0}"'.format(textHash)
                cur.execute(query)
                row = cur.fetchone()

                count, = row

                if count == 0:
                        exists = False
                else:
                        exists = True

		query = 'SELECT count(documentid) as count FROM Documents WHERE hash="{0}" and sourceurl = "{1}"'.format(textHash,url)
                cur.execute(query)
                row = cur.fetchone()

                count, = row

                if count == 0:
                        samelink = False
                else:
                        samelink = True


	return (exists,samelink)

def parsepdf(sourceURL,docname,scrapurlid,bodyid):

	#f( len(argv) != 3 ):
	#print "Usage: {0} <pdf_file> <source_url>".format(argv[0])
	#return;

	#rint "Application Started."

	#dfFileName = argv[1];
	#ourceURL = argv[2];

	#print "Checking if Document Already Exists in DB"

	exists = check_url_exists(sourceURL)

	if exists == True:
		print "\t\t\tPARSER: Document Already Parsed, Ignoring."
		#print "\t\t\tPARSER: Done."
		return "Ignore"

	#print "Done."

	#print "\t\t\tPARSER: Downloading PDF ..."
	
	# get the filename off of the url, then set the local file to that + a random number .pdf, and download it
	urlfile = sourceURL[sourceURL.rfind("/")+1:]
	filename = "./pdfs/{0}_{1}.pdf".format(urlfile,randint(0,1000000))

	print "\t\t\tPARSER: Source URL = {0}".format(sourceURL)
	#print "URL File = {0}".format(urlfile)
	#print "Filename = {0}".format(filename)

	pdfFileName,headers = urllib.urlretrieve(sourceURL,filename)

	#print "Done."

	#print "Converting PDF ..."

	pdfText,success = convert_pdf(pdfFileName)

	if success == False:
		print "\t\t\tPARSER: Failed to Convert PDF."
		#print "Done."
		return "NonPDF"

	pdfText = pdfText.encode('ascii','ignore')

	#print "Done."

	#print "Testing to see if Hash exists in DB"

	exists,samelink = check_hash_exists(pdfText,sourceURL)

	if exists == True:
		print "\t\t\tPARSER: PDF Already Parsed, Ignoring."	
		if samelink == True:
			# this means that the pdf is linked on the page 
			return "DuplicateLink"
		else:
			# this means that it's the same pdf, but a different url.  I'm looking at you Henrietta ...
			return "Ignore"

	#print "Done."

	#print "Pulling Org List From DB"

	orgs = get_orgs(bodyid)

	#print "Done."

	#print "Pulling Out Header Information ..."

	# take first 1024 charictors of the pdf
	headerText = pdfText[:1024];

	# split the smaller portion into lines
	headerLines = headerText.split("\n")

	# setup date/time parser
	c = pdt.Constants();
	c.BirthdayEpoch = 80 # if parsed year value is less than this value set to 2000+ value
	p = pdt.Calendar(c)

	publishDate = None
	organizationName = None
	organizationID = None

	for line in headerLines:

		# make sure there is actually data in the line
		if line.strip() != "":

			print "Trying to Parse: '{0}'".format(line)	
		
			# 
			# Parse as Date
			#
			if( publishDate == None ):

				# attempt to parse the date ...
				try:
					dtResult = p.parseDate(line)
				except:
					try:
						dtResult = p.parseDateText(line)
					except:
						dtResult = None
		
				if dtResult != None:
					print "Date = {0}, Time = {1}".format( datetime.datetime( *dtResult[:6] ).date(),datetime.datetime( *dtResult[:6] ).time() )
					publishDate = datetime.datetime( *dtResult[:6] ).date().isoformat()

				#if retType = 0
				#	# no date found
				#	publishDate = None
				#elif retType in (1,2):
				#	publishDate = datetime.datetime( *dtResult[:6] )
				#elif retType == 3:	
				#	publishDate = dtResult.datetime
						
				# parsed as a time, not a date	
				if( publishDate != None ):
					print "\t\t\tPARSER: Date/Time Parsed as: {0}".format(publishDate)
				#else:
				#	print "\tNot a Date"
		
			#
			# Parse as Organization
			# 
			if organizationName == None:
				orgName,orgID,success = parse_org(line,orgs)
	
				if success == True:
					organizationName = orgName
					organizationID = orgID
					print "\t\t\tPARSER: Organization Name: {0}, ID: {1}".format(organizationName,organizationID)

			if publishDate != None and organizationName != None:
				#print "\t\t\tPARSER: Header Successfully Parsed."
				break


	#print ""
	#print "\tOrganization Name = {0}".format(organizationName)
	#print "\tOrganization ID = {0}".format(organizationID)
	#print "\tPublish Date = {0}".format(publishDate.date())
	#rint ""

	if organizationID == None or publishDate == None:
		print "\t\t\tPARSER: ERROR: Bad PDF Header Formating!!!"
		#print "Done."
		return "Error"

	#print "Done."

	#print "Pushing Document to Database"

	# get hash of the pdf text
	textHash = hashlib.md5(pdfText).hexdigest()

	# push the doc to the DB
	docid = push_document(pdfText, docname, sourceURL, publishDate, organizationID, scrapurlid, textHash, bodyid)

	#print "Done."

	#print "Pushing Document Word Frequency Histogram to DB."

	push_words(pdfText,docid)

	#print "Done."

	print "\t\t\tPARSER: Parse Successful."

	return "Successful"

