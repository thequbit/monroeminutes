<?php

	require_once("DatabaseTool.class.php");
	require_once("Document.class.php");

	class DocumentsTool
	{
		function GetDocumentByID($docID)
		{
		
			dprint( "GetDocumentByID() Start.");
		
			$document = new Document();
		
			try
			{
				// create an instance of our database tool
				$db = new DatabaseTool();

				// generate query
				$query = "SELECT documentid, scrapdt, publishdate, docname, organizationid, sourceurl, doctext, scrapurlid, hash, bodyid FROM Documents WHERE documentid = ?";
				
				// NOTE: no doctext.
				//$query = "SELECT documentid, scrapdt, publishdate, docname, organizationid, sourceurl, scrapurlid, hash, bodyid FROM Documents WHERE documentid = ?";
				
				//dprint("Executing Query = " . $query);
				
				// connect to db
				$mysqli = $db->Connect();
				
				// prepate the statement
				$stmt = $mysqli->prepare($query);
				
				// add the keyword as a string to the statement
				$stmt->bind_param("s", $docID);
				
				// execute the query
				$results = $db->Execute($stmt);
				
				//dprint("Processing Results ...");
			
				// pull the first row from the result ( should be the only row )
				$row = $results[0];
			
				// populate our document object with the row data
				$document->documentid 		= $row['documentid'];
				$document->scrapdt 			= $row['scrapdt'];
				$document->publishdate 		= $row['publishdate'];
				$document->docname 			= $row['docname'];
				$document->organizationid 	= $row['organizationid'];
				$document->sourceurl 		= $row['sourceurl'];
				$document->doctext 			= $row['doctext'];
				$document->scrapurlid 		= $row['scrapurlid'];
				$document->hash 			= $row['hash'];
				$document->bodyid 			= $row['bodyid'];
				
				// close our DB connection
				$db->Close($mysqli, $stmt);
			
			}
			catch (Exception $e)
			{
				dprint( "Caught exception: " . $e->getMessage() );
			}
			
			dprint("GetDocumentByID() Done.");
			
			// return our array of SearchResult objects
			return $document;
		}
		
	}

?>