monroeminutes.org

Overview
	monroeminutes.org is a document agrigator and indexer.  The tools scrape websites within Monroe County and pull meeting minutes and other important documents from various village, town, city, and county websites.  These are then indexed using a histogram method with keyword rejection (black list of words that are too common to include in search).

	The data is setup in the following way:
	
		|-> Organization 0
			|-> Sub Organization 0
				|-> Document 0
					|-> <token data>
				|-> Document 1
					|-> <token data>
				|-> Document 2
						|-> <token data>
		
			|-> Sub Organization 1
				|-> Document 0
					|-> <token data>
		
		|-> Organization 1
			|-> Sub Organization 0
				|-> Document 0
					|-> <token data>
					|-> <token data>
				|-> Document 1
					|-> <token data>
					
		Where an Organization would be a Town, and a sub organization may be that town's school district, town meetings, town zoning board meetings, etc.  The this second level of granularity was introduced to allow for power searches based on user address, and not just keyword.
		
		Token data is transparent to the user, and not accessable via API (yet).  Token data is simply just a histogram of words with their respective number of times they shown up in a the document.  This data can be used to sort the responces with respect to relavence.
		
http api's - 
		
	orgapi.php
		This allows you to pull down suborganization data by passing in the name of the suborg.
		
		Pass in the following:
		
			orgname
			
		And you will get back a json like this:
			"organiztionname":"Henrietta"
			"name":"Town of Henrietta"
			"websiteurl":"http:\/\/www.henrietta.org"
			"documentsurl":"http:\/\/www.henrietta.org\/doccenter\/cat_view\/3-town-board-documents\/150-2012-agenda-a-minutes.html"
			"scriptname":"simpledifscrapper.py"
			"dbpopulated":"true"
			"indexeddocs":"32"
		
		Example data:
			http://monroeminutes.org/api/orgapi.php?orgname=Town+of+Henrietta
			
				- returns -
			
			{"organiztionname":"Henrietta","name":"Town of Henrietta","websiteurl":"http:\/\/www.henrietta.org","documentsurl":"http:\/\/www.henrietta.org\/doccenter\/cat_view\/3-town-board-documents\/150-2012-agenda-a-minutes.html","scriptname":"simpledifscrapper.py","dbpopulated":"true","indexeddocs":"32"}
		
	searchapi.php
		Pass in one or more of the following:
		
			keyword
			startdate
			enddate
			organization (note: multiple organizations supported)

		The result will be a json object that will hold the objects that are a "Document".  A document is defined as:
		
			suborgname : 'Happy Town!'string
			orgname : 'Happy County'string
			sourceurl : 'www.henrietta.org/doccenter/doc_download/1987-sep-19-2012-tb-agenda-a-minutes.html'string
			docdate : '0000-00-00'string
			scrapedate : '0000-00-00'string
			name : '1987-sep-19-2012-tb-agenda-a-minutes.html'string
			word : 'test'string
			frequency : '23'

		Where:
			suborgname is the name of the sub organization that the document belongs to.
			orgname is the parrent name to the sub organization
			sourceurl is the location that the document was pulled from
			docdate is the date of the document (note: should be the date of publish, not the date of scrape)
			scrapedate is the date the tool pulled the doc from the net and indexed it
			name is the name of the document (note: may not be unique)
			word is the word that was used to generate the result (searches are split on " " and seperate searches are performed)
			frequency is the number of times the word showed up in the document

			The json object returned looks like this:
			
			{
				"status":"0",
				"errorText":"None",
				"queryTime":".00612",
				"resultCount":4,
				"results":[]
			}
		
			Where the [] is the array of json objects that are defined as the document structure above.
	
Search Modes
	There are two distinct search modes:
	
		1. Search with address
			This mode the user is providing an address and a series of on-the-fly look-up tables are used to deturmine what suborganizations are associated with this address.  We pull from the monroe county websites to get this data at search execution time.  Once the suborg list os populated the search continues just as it would without an address.  The address is only used to generate the list of suborgs.
			
		2. Search without address
			This mode the user is providing a keyword search, and/or an organization, and/or a date range and recieving back a list of documents.
		
Different input interpretation
	The user can provide 0 - n organizations
	The user can provide 1 - n keywords (seperated by spaces in the searchstring)
	
A very simple front-end is provided as index.php.  This is a simple webform that POST's to the searchapi.php api php script.
	