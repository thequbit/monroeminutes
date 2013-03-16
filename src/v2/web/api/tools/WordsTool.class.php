<?php

	require_once("DatabaseTool.class.php");

	class WordsTool
	{
		
		function DocIDsFromWord($keyword)
		{
		
			dprint( "DocIDsFromWord() Start.");
		
			$docIDs = array();
		
			try
			{
		
				$db = new DatabaseTool();
				//$summaryTool = new SummaryTool();
				
				// generate query NOTE: LIMIT 15
				$query = "SELECT documentid FROM Words WHERE word = ?";
				
				//dprint("Executing Query = " . $query);
				
				// connect to db
				$mysqli = $db->Connect();
				
				// prepate the statement
				$stmt = $mysqli->prepare($query);
				
				// add the keyword as a string to the statement
				$stmt->bind_param("s", $keyword);
				
				// execute the query
				$results = $db->Execute($stmt);
				
				dprint( count($results) . " doc's returned." );
				
				//dprint("Processing Results ...");
			
				// iterate through the returned rows and add the doc ids to the array
				foreach( $results as $row )
				{
					$docIDs[] = $row['documentid'];
				}
				
				// close our DB connection
				$db->Close($mysqli, $stmt);
			
			}
			catch (Exception $e)
			{
				dprint( "Caught exception: " . $e->getMessage() );
			}
			
			dprint( "DocIDsFromWord() Done.");
			
			// return our array of SearchResult objects
			return $docIDs;
		
		}
		
	}

?>