<?php

	// DB access layer
	require_once("DatabaseTool.class.php");
	
	// helper tools
	require_once("SummaryTool.class.php");
	require_once("OrganizationsTool.class.php");
	require_once("BodiesTool.class.php");
	require_once("WordsTool.class.php");
	require_once("DocumentsTool.class.php");
	
	// holder objects
	require_once("Document.class.php");
	require_once("SearchResult.class.php");
	
	class SearchTool
	{
		function PerformSearch($keyword)
		{
			// create an array to put our results in
			$searchResults = array();
			
			dprint("PerformSearch() Start.");
			
			try
			{
		
				dprint( "Creating Org and Body dictionaries ... " );
		
				// get the dictionary of Organizations
				$orgTool = new OrganizationsTool();
				$orgDict = $orgTool->GetOrganizationsDictionary();
		
				// get the dictionary of the Bodies
				$bodiesTool = new BodiesTool();
				$bodiesDict = $bodiesTool->GetBodiesDictionary();
		
				dprint( "Getting Document ID's that hold keyword ..." );
				
				// get all of the documents ID's with the keyword in them
				$wordTool = new WordsTool();
				$docIDs = $wordTool->DocIDsFromWord($keyword);
		
				// create an instance of our DocumentTool to pull the docs from the DB
				$docTool = new DocumentsTool();
		
				// create an instance of our SummaryTool to create summaries of the text
				// around our keyword
				$summaryTool = new SummaryTool();
		
				dprint( "Generating Search Result Array ... " );
		
				// iterate through the list of ID's and generate the array of SearchResults using the
				// document contents and org, body dictionaries
				foreach($docIDs as $docID)
				{
					// get the doc info based off it's ID
					$doc = $docTool->GetDocumentByID($docID);
					
					// do some sanity checking on our returned data ...
					if( $doc->documentid != null )
					{	
						// create a search result object to populate
						$searchResult = new SearchResult();
						
						// set the data for the search result object, pulling data from the doc,
						// the dictionaries, and the summary tool
						$searchResult->documentid 	= $doc->documentid;
						$searchResult->scrapdt 		= $doc->scrapdt;
						$searchResult->publishdate 	= $doc->publishdate;
						$searchResult->docname 		= $doc->docname;
						$searchResult->orgname 		= $orgDict[$doc->organizationid];
						$searchResult->sourceurl 	= $doc->sourceurl;
						$searchResult->bodyname 	= $bodiesDict[$doc->bodyid];
						
						$searchResult->summary 		= $summaryTool->MakeSummary(128, $keyword, $doc->doctext);
						
						// add the search result to the array of search results to be returned
						$searchResults[] = $searchResult;
						
					}
				}
		
				dprint( "Array Generation Complete." );
		
				$this->RecordSearch($keyword);
		
			}
			catch (Exception $e)
			{
				dprint( "Caught exception: " . $e->getMessage() );
			}
			
			dprint("PerformSearch() Done.");
			
			// return our array of SearchResult objects
			return $searchResults;
		}
		
		function RecordSearch($keyword)
		{
			dprint( "RecordSearch() Start." );
		
			$datetime = date("Y-m-d H:i:S");
			
			try
			{
		
				$db = new DatabaseTool();
			
				// generate our query
				$query = 'INSERT INTO Searches(searchstring,searchdt) VALUES(?,?)';
				
				// connect to db
				$mysqli = $db->Connect();
				
				// prepate the statement
				$stmt = $mysqli->prepare($query);
				
				// add the keyword as a string to the statement
				$stmt->bind_param("ss",$keyword,$datetime);
				
				// execute the query
				$results = $db->Execute($stmt);
				
				// close our DB connection
				//$db->Close($mysqli, $stmt);
				
			}
			catch (Exception $e)
			{
				dprint( "Caught exception: " . $e->getMessage() );
			}
			
			dprint("RecordSearch() Done.");
		}
	}

?>