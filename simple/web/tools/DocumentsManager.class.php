<?

	require_once("DatabaseTool.class.php");

	class DocumentsManager
	{
		function add($suborganizationid,$organizationid,$sourceurl,$documentdate,$scrapedate,$name,$dochash,$orphaned)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'INSERT INTO documents(suborganizationid,organizationid,sourceurl,documentdate,scrapedate,name,dochash,orphaned) VALUES(?,?,?,?,?,?,?,?)';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("ssssssss", $suborganizationid,$organizationid,$sourceurl,$documentdate,$scrapedate,$name,$dochash,$orphaned);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('documentid' => $row['documentid'],'suborganizationid' => $row['suborganizationid'],'organizationid' => $row['organizationid'],'sourceurl' => $row['sourceurl'],'documentdate' => $row['documentdate'],'scrapedate' => $row['scrapedate'],'name' => $row['name'],'dochash' => $row['dochash'],'orphaned' => $row['orphaned']);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		
			return $retVal;
		}

		function get($documentid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'SELECT * FROM documents WHERE documentid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $documentid);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('documentid' => $row['documentid'],'suborganizationid' => $row['suborganizationid'],'organizationid' => $row['organizationid'],'sourceurl' => $row['sourceurl'],'documentdate' => $row['documentdate'],'scrapedate' => $row['scrapedate'],'name' => $row['name'],'dochash' => $row['dochash'],'orphaned' => $row['orphaned']);
	
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
				$query = 'SELECT * FROM documents';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$results = $db->Execute($stmt);
			
				$retArray = array();
				foreach( $results as $row )
				{
					$object = (object) array('documentid' => $row['documentid'],'suborganizationid' => $row['suborganizationid'],'organizationid' => $row['organizationid'],'sourceurl' => $row['sourceurl'],'documentdate' => $row['documentdate'],'scrapedate' => $row['scrapedate'],'name' => $row['name'],'dochash' => $row['dochash'],'orphaned' => $row['orphaned']);
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

		function del($documentid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'DELETE FROM documents WHERE documentid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $documentid);
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
				$query = 'UPDATE documents SET suborganizationid = ?,organizationid = ?,sourceurl = ?,documentdate = ?,scrapedate = ?,name = ?,dochash = ?,orphaned = ? WHERE documentid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("sssssssss", $suborganizationid,$organizationid,$sourceurl,$documentdate,$scrapedate,$name,$dochash,$orphaned, $documentid);
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
