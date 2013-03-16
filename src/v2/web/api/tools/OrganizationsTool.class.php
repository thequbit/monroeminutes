<?php

	require_once("DatabaseTool.class.php");

	class OrganizationsTool
	{
		function GetOrganizationsDictionary()
		{
		
			dprint( "GetOrganizationsDictionary() Start." );
		
			// create dictionary
			$orgDictionary = array();
		
			try
			{
		
				$db = new DatabaseTool();
			
				// generate our query
				$query = 'SELECT organizationid, name FROM Organizations';
				
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
					$orgDictionary[$row['organizationid']] = $row['name'];
				}
				
				// close our DB connection
				$db->Close($mysqli, $stmt);
			
			}
			catch (Exception $e)
			{
				dprint( "Caught exception: " . $e->getMessage() );
			}
			
			dprint("GetOrganizationsDictionary() Done.");
			
			// return our array of SearchResult objects
			return $orgDictionary;
		}
	}

?>