import nltk
import os
import json
import sys

mydirectory=os.path.abspath(os.path.split(__file__)[0]) + "/"

#print mydirectory

listing = os.walk(mydirectory)

#print listing

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
	
		with open(infile + ".json", "w") as outfile:
			json.dump(data, outfile)



print "done."

#	print path,folder,infile

# vim: se ts=4
