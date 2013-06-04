<?php

	require_once("DatabaseTool.class.php");
	
	class SearchManager
	{
	
		function GetSearchResultCount($searchterm, $organizationid)
		{
			dprint( "GetSearchResultCount() Start." );
			
			try
			{
				$db = new DatabaseTool();
			
				$query  = 'select count(documents.documentid) as count ';
				$query = $query . 'from documents ';
				$query = $query . 'inner join words on words.documentid = documents.documentid ';
				$query = $query . 'inner join organizations on documents.organizationid = organizations.organizationid ';
				$query = $query . 'inner join suborganizations on documents.suborganizationid = suborganizations.suborganizationid ';
				$query = $query . 'where documents.organizationid = ? and words.word = ?';
				
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("ss", $organizationid,$searchterm);
				$results = $db->Execute($stmt);
			
				$count = count($results);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
			
			dprint("GetSearchResultCount() Done.");
			
			return $count;
		}
	
		function PerformSearch($searchterm, $organizationid, $page)
		{
			dprint( "PerformSearch() Start." );
			
			try
			{
				$db = new DatabaseTool();
			
				$query  = 'select documents.name as docname, documents.scrapedate as scrapedate, documents.documentdate as documentdate, documents.sourceurl as sourceurl, ';
				$query = $query . 'organizations.name as orgname, ';
				$query = $query . 'suborganizations.name as suborgname, suborganizations.websiteurl as websiteurl ';
				$query = $query . 'from documents ';
				$query = $query . 'inner join words on words.documentid = documents.documentid ';
				$query = $query . 'inner join organizations on documents.organizationid = organizations.organizationid ';
				$query = $query . 'inner join suborganizations on documents.suborganizationid = suborganizations.suborganizationid ';
				$query = $query . 'where documents.organizationid = ? and words.word = ? order by documents.documentdate limit 10 offset ?';
				
				//echo $query;
				
				if( $page > 1 )
					$limit = "offset " . intval( ($page-1) * 10 );
				else
					$limit = 0;
					
				//echo "-- " . $limit . " --";
				
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("sss", $organizationid,$searchterm,$limit);
				$results = $db->Execute($stmt);
			
				$searchresults = array();
				foreach( $results as $row )
				{
					$searchresult = (object) array( 'docname' => $row['docname'],
													'scrapedate' => $row['scrapedate'],
													'documentdate' => $row['documentdate'],
													'sourceurl' => $row['sourceurl'],
													'orgname' => $row['orgname'],
													'suborgname' => $row['suborgname'],
													'websiteurl' => $row['websiteurl']
												  );
											
					$searchresults[] = $searchresult;
				}
	
				$this->RecordSearch($searchterm,$organizationid);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
			
			dprint("PerformSearch() Done.");
			
			return $searchresults;
		}
	
		function RecordSearch($searchterm,$organizationid)
		{
			dprint( "RecordSearch() Start." );
			
			try
			{
				$db = new DatabaseTool();
			
				$datetime = date("Y-m-d H:i:s");
			
				$query = 'INSERT INTO searches(searchterm,searchdt,organizationid) values(?,?,?)';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("sss", $searchterm,$datetime,$organizationid);
				$results = $db->Execute($stmt);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
			
			dprint("RecordSearch() Done.");
		}
	
	}

?>