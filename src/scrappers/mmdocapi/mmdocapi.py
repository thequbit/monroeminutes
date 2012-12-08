# monroeminutes document api

def AddDocumentToDatabase(infile, documenturl, datetime, suborgid):
	
	print "Processing " + infile + "..."
	
	if not infile[-4:]==".txt":
		continue
	
	#process the input file using the Natural Language Tool Kit
	with open(os.path.join(path,infile)) as file_:
		tokens = nltk.word_tokenize(file_.read(-1))
	fdist = nltk.FreqDist(word.lower() for word in tokens)
	
	#remove black-list words
	for token in [",","and","of","the","for","a","to","aye","nay","voting","town","supervisor","on","be","councilman", "councilmen","councilwoman","we","this","is","are","in","would","that","$","page","(",")","has","i","at","you","it","with","there","so","?","have","new","#","as",":","by","whereas","where","here","yes","no","now","from","but","not"]:
		if token in fdist:
			del fdist[token]

	#create new document within db, and get generated id back
	query="insert into documents(suborganizationid,sourceurl,date,name) values(\"{0}\",\"{1}\",\"{2}\",\"{3}\")".format(sys.argv[1],documenturl,datetime,infile)
	database.query(query)

	#get the id of the previous insert
	query="select documentid from documents where sourceurl=\"{0}\" and name=\"{1}\"".format(documenturl,infile)
	database.query(query)
	dbresult=database.store_result()
	
	#get doc id from results
	(docid,),=dbresult.fetch_row()

	print "Processing words for Doc ID: " + docid

	#enter the tokens into the database
	for token,frequency in fdist.items():
		query="insert into wordfrequency(documentid,word,frequency) values(\"{0}\",\"{1}\",\"{2}\")".format(docid,token,frequency)
		database.query(query)