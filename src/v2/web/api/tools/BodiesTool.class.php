<?php

	require_once("DatabaseTool.class.php");

	class BodiesTool
	{
		function GetBodiesDictionary()
		{
			dprint( "GetBodiesDictionary() Start." );
		
			// create dictionary
			$bodiesDictionary = array();
		
			try
			{
		
				$db = new DatabaseTool();
			
				// generate our query
				$query = 'SELECT bodyid, name FROM Bodies';
				
				// connect to db
				$mysqli = $db->Connect();
				
				// prepate the statement
				$stmt = $mysqli->prepare($query);
				
				// execute the query
				$results = $db->Execute($stmt);
				
				dprint( "Processing Results ..." );
				
				// iterate through the returned rows and decode them into a php class
				foreach( $results as $row )
				{
					$bodiesDictionary[$row['bodyid']] = $row['name'];
				}
				
				// close our DB connection
				$db->Close($mysqli, $stmt);
			
			}
			catch (Exception $e)
			{
				dprint( "Caught exception: " . $e->getMessage() );
			}
			
			dprint("GetBodiesDictionary() Done.");
			
			// return our array of SearchResult objects
			return $bodiesDictionary;
		}
	}

?>