<?

	require_once("DatabaseTool.class.php");

	class WordsManager
	{
		function add($documentid,$suborganizationid,$organizationid,$word,$frequency)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'INSERT INTO words(documentid,suborganizationid,organizationid,word,frequency) VALUES(?,?,?,?,?)';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("sssss", $documentid,$suborganizationid,$organizationid,$word,$frequency);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('wordid' => $row['wordid'],'documentid' => $row['documentid'],'suborganizationid' => $row['suborganizationid'],'organizationid' => $row['organizationid'],'word' => $row['word'],'frequency' => $row['frequency']);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		
			return $retVal;
		}

		function get($wordid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'SELECT * FROM words WHERE wordid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $wordid);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('wordid' => $row['wordid'],'documentid' => $row['documentid'],'suborganizationid' => $row['suborganizationid'],'organizationid' => $row['organizationid'],'word' => $row['word'],'frequency' => $row['frequency']);
	
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
				$query = 'SELECT * FROM words';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$results = $db->Execute($stmt);
			
				$retArray = array();
				foreach( $results as $row )
				{
					$object = (object) array('wordid' => $row['wordid'],'documentid' => $row['documentid'],'suborganizationid' => $row['suborganizationid'],'organizationid' => $row['organizationid'],'word' => $row['word'],'frequency' => $row['frequency']);
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

		function del($wordid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'DELETE FROM words WHERE wordid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $wordid);
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
				$query = 'UPDATE words SET documentid = ?,suborganizationid = ?,organizationid = ?,word = ?,frequency = ? WHERE wordid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("ssssss", $documentid,$suborganizationid,$organizationid,$word,$frequency, $wordid);
				$results = $db->Execute($stmt);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		}
		
		///// Application Specific Functions /////
		
		function getcount($orgid,$keyword,$page)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'SELECT count(wordid) as count FROM words WHERE organizationid = ? and word = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("ss",$organizationid, $keyword);
				$results = $db->Execute($stmt);
			
				$count = $row['count'];
				
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		
			return $count;
		}
		
		function search($orgid,$keyword,$page)
		{
		    try
			{
				// decode offset based on page assuming 10 items / page
				if( $page > 1 )
					$limit = intval( ($page-1) * 10 );
				else
					$limit = 0;
			
				$db = new DatabaseTool(); 
				$query = 'SELECT documentid,suborganizationid,frequency FROM words WHERE organizationid = ? and word = ? ORDER BY frequency LIMIT 10 OFFSET ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("ss",$organizationid, $keyword, $limit);
				$results = $db->Execute($stmt);
			
				$retArray = array();
				foreach( $results as $row )
				{
					$object = (object) array('wordid' => $row['wordid'],'documentid' => $row['documentid'],'suborganizationid' => $row['suborganizationid'],'organizationid' => $row['organizationid'],'word' => $row['word'],'frequency' => $row['frequency']);
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
	}

?>
