<?

	require_once("DatabaseTool.class.php");

	class SearchesManager
	{
		function add($searchterm,$searchdt,$organizationid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'INSERT INTO searches(searchterm,searchdt,organizationid) VALUES(?,?,?)';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("sss", $searchterm,$searchdt,$organizationid);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('searchid' => $row['searchid'],'searchterm' => $row['searchterm'],'searchdt' => $row['searchdt'],'organizationid' => $row['organizationid']);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		
			return $retVal;
		}

		function get($searchid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'SELECT * FROM searches WHERE searchid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $searchid);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('searchid' => $row['searchid'],'searchterm' => $row['searchterm'],'searchdt' => $row['searchdt'],'organizationid' => $row['organizationid']);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		
			return $retVal;
		}

		function getall()
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'SELECT * FROM searches';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$results = $db->Execute($stmt);
			
				$retArray = array();
				foreach( $results as $row )
				{
					$object = (object) array('searchid' => $row['searchid'],'searchterm' => $row['searchterm'],'searchdt' => $row['searchdt'],'organizationid' => $row['organizationid']);
					$retArray[] = $object;
				}
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		
			return $retArray;
		}

		function del($searchid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'DELETE FROM searches WHERE searchid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $searchid);
				$results = $db->Execute($stmt);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		}

		function update(<!csv_key_column_names!>)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'UPDATE searches SET searchterm = ?,searchdt = ?,organizationid = ? WHERE searchid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("ssss", $searchterm,$searchdt,$organizationid, $searchid);
				$results = $db->Execute($stmt);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		}
	}

?>
