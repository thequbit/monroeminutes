<?

	require_once("DatabaseTool.class.php");

	class IgnoreurlsManager
	{
		function add($url,$ignoredt,$scrapeurlid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'INSERT INTO ignoreurls(url,ignoredt,scrapeurlid) VALUES(?,?,?)';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("sss", $url,$ignoredt,$scrapeurlid);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('ignoreurlid' => $row['ignoreurlid'],'url' => $row['url'],'ignoredt' => $row['ignoredt'],'scrapeurlid' => $row['scrapeurlid']);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		
			return $retVal;
		}

		function get($ignoreurlid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'SELECT * FROM ignoreurls WHERE ignoreurlid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $ignoreurlid);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('ignoreurlid' => $row['ignoreurlid'],'url' => $row['url'],'ignoredt' => $row['ignoredt'],'scrapeurlid' => $row['scrapeurlid']);
	
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
				$query = 'SELECT * FROM ignoreurls';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$results = $db->Execute($stmt);
			
				$retArray = array();
				foreach( $results as $row )
				{
					$object = (object) array('ignoreurlid' => $row['ignoreurlid'],'url' => $row['url'],'ignoredt' => $row['ignoredt'],'scrapeurlid' => $row['scrapeurlid']);
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

		function del($ignoreurlid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'DELETE FROM ignoreurls WHERE ignoreurlid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $ignoreurlid);
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
				$query = 'UPDATE ignoreurls SET url = ?,ignoredt = ?,scrapeurlid = ? WHERE ignoreurlid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("ssss", $url,$ignoredt,$scrapeurlid, $ignoreurlid);
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
