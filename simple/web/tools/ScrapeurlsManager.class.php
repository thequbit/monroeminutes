<?

	require_once("DatabaseTool.class.php");

	class ScrapeurlsManager
	{
		function add($url,$name,$organizationid,$enabled)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'INSERT INTO scrapeurls(url,name,organizationid,enabled) VALUES(?,?,?,?)';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("ssss", $url,$name,$organizationid,$enabled);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('scrapeurlid' => $row['scrapeurlid'],'url' => $row['url'],'name' => $row['name'],'organizationid' => $row['organizationid'],'enabled' => $row['enabled']);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		
			return $retVal;
		}

		function get($scrapeurlid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'SELECT * FROM scrapeurls WHERE scrapeurlid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $scrapeurlid);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('scrapeurlid' => $row['scrapeurlid'],'url' => $row['url'],'name' => $row['name'],'organizationid' => $row['organizationid'],'enabled' => $row['enabled']);
	
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
				$query = 'SELECT * FROM scrapeurls';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$results = $db->Execute($stmt);
			
				$retArray = array();
				foreach( $results as $row )
				{
					$object = (object) array('scrapeurlid' => $row['scrapeurlid'],'url' => $row['url'],'name' => $row['name'],'organizationid' => $row['organizationid'],'enabled' => $row['enabled']);
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

		function del($scrapeurlid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'DELETE FROM scrapeurls WHERE scrapeurlid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $scrapeurlid);
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
				$query = 'UPDATE scrapeurls SET url = ?,name = ?,organizationid = ?,enabled = ? WHERE scrapeurlid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("sssss", $url,$name,$organizationid,$enabled, $scrapeurlid);
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
