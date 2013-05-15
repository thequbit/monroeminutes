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