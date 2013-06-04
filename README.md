monroeminutes.org
=================

Tools and Technologies
----------------------

The site is built on Python scrapers, and PHP web back-end interfacing to a MySQL database.

The base classes for both the python and the PHP were generated from SQL via sql2api ( http://github.com/thequbit/sql2api ).


Overview
--------

MonroeMinutes, and thus monroeminutes.org, is a document agrigator and indexer.  The tools scrape websites within Monroe County and pull meeting minutes and other important documents from various village, town, city, and county websites.  These are then indexed using a histogram method with keyword rejection (black list of words that are too common to include in search).

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
				
