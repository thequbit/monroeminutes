<?

	require_once("DatabaseTool.class.php");

	class DocumenttextsManager
	{
		function add($documentid,$documenttext)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'INSERT INTO documenttexts(documentid,documenttext) VALUES(?,?)';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("ss", $documentid,$documenttext);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('documenttextid' => $row['documenttextid'],'documentid' => $row['documentid'],'documenttext' => $row['documenttext']);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		
			return $retVal;
		}

		function get($documenttextid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'SELECT * FROM documenttexts WHERE documenttextid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $documenttextid);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('documenttextid' => $row['documenttextid'],'documentid' => $row['documentid'],'documenttext' => $row['documenttext']);
	
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
				$query = 'SELECT * FROM documenttexts';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$results = $db->Execute($stmt);
			
				$retArray = array();
				foreach( $results as $row )
				{
					$object = (object) array('documenttextid' => $row['documenttextid'],'documentid' => $row['documentid'],'documenttext' => $row['documenttext']);
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

		function del($documenttextid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'DELETE FROM documenttexts WHERE documenttextid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $documenttextid);
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
				$query = 'UPDATE documenttexts SET documentid = ?,documenttext = ? WHERE documenttextid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("sss", $documentid,$documenttext, $documenttextid);
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
