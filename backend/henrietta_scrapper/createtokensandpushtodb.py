import nltk
import os
import json
import sys
import _mysql as mysql

mydirectory=os.path.abspath(os.path.split(__file__)[0]) + "/"

#print mydirectory

listing = os.walk(mydirectory)

#print listing

#connect to db
host="lisa.duffnet.local"
username="mmuser"
password="password123%%%"
dbname="monroeminutes"
database=mysql.connect(host=host,user=username,passwd=password,db=dbname)

for path,folder,filelist in listing:

	#print path,folder,filelist

	for infile in filelist:
	
		#print infile

		if not infile[-4:]==".txt":
			continue
		with open(os.path.join(path,infile)) as file_:
			tokens = nltk.word_tokenize(file_.read(-1))
		fdist = nltk.FreqDist(word.lower() for word in tokens)
		for token in [",","and","of","the","for","a","to","aye","nay","voting","town","supervisor","on","be","councilman", "councilmen","councilwoman","we","this","is","are","in","would","that","$","page","(",")","has","i","at","you","it","with","there","so","?","have","new","#","as",":","by","whereas","where","here","yes","no","now","from","but","not"]:
			if token in fdist:
				del fdist[token]
	
		infile = infile[:-8]
		data=dict(suborgid=sys.argv[1], documenturl='/'.join([path[len(mydirectory):], infile]), frequency=fdist)
		documenturl='/'.join([path[len(mydirectory):], infile])
	
		#with open(infile + ".json", "w") as outfile:
		#	json.dump(data, outfile)

		#create new document within db, and get generated id back
		query="insert into documents(suborganizationid,sourceurl,date,name) values(\"{0}\",\"{1}\",\"{2}\",\"{3}\")".format(sys.argv[1],documenturl,"0000",infile)
		#print "Running: " + query
		database.query(query)

		#get the id of the previous insert
		query="select documentid from documents where sourceurl=\"{0}\" and name=\"{1}\"".format(documenturl,infile)
		#print "Running: " + query
		database.query(query)
		dbresult=database.store_result()
		(docid,),=dbresult.fetch_row()

		print "Processing words for Doc ID: " + docid

		for token,frequency in fdist.items():
			query="insert into wordfrequency(documentid,word,frequency) values(\"{0}\",\"{1}\",\"{2}\")".format(docid,token,frequency)
			database.query(query)




print "done."

#	print path,folder,infile

# vim: se ts=4
