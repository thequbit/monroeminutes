<?

	require_once("DatabaseTool.class.php");

	class RunsManager
	{
		function add($rundt,$scrapername,$successful,$organizationid,$suborganizationid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'INSERT INTO runs(rundt,scrapername,successful,organizationid,suborganizationid) VALUES(?,?,?,?,?)';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("sssss", $rundt,$scrapername,$successful,$organizationid,$suborganizationid);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('runid' => $row['runid'],'rundt' => $row['rundt'],'scrapername' => $row['scrapername'],'successful' => $row['successful'],'organizationid' => $row['organizationid'],'suborganizationid' => $row['suborganizationid']);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		
			return $retVal;
		}

		function get($runid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'SELECT * FROM runs WHERE runid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $runid);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('runid' => $row['runid'],'rundt' => $row['rundt'],'scrapername' => $row['scrapername'],'successful' => $row['successful'],'organizationid' => $row['organizationid'],'suborganizationid' => $row['suborganizationid']);
	
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
				$query = 'SELECT * FROM runs';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$results = $db->Execute($stmt);
			
				$retArray = array();
				foreach( $results as $row )
				{
					$object = (object) array('runid' => $row['runid'],'rundt' => $row['rundt'],'scrapername' => $row['scrapername'],'successful' => $row['successful'],'organizationid' => $row['organizationid'],'suborganizationid' => $row['suborganizationid']);
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

		function del($runid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'DELETE FROM runs WHERE runid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $runid);
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
				$query = 'UPDATE runs SET rundt = ?,scrapername = ?,successful = ?,organizationid = ?,suborganizationid = ? WHERE runid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("ssssss", $rundt,$scrapername,$successful,$organizationid,$suborganizationid, $runid);
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
