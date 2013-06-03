<?

	require_once("DatabaseTool.class.php");

	class ActionsManager
	{
		function add($userid,$actiontype,$pagename,$description)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'INSERT INTO actions(userid,actiontype,pagename,description) VALUES(?,?,?,?)';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("ssss", $userid,$actiontype,$pagename,$description);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('actionid' => $row['actionid'],'userid' => $row['userid'],'actiontype' => $row['actiontype'],'pagename' => $row['pagename'],'description' => $row['description']);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		
			return $retVal;
		}

		function get($actionid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'SELECT * FROM actions WHERE actionid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $actionid);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('actionid' => $row['actionid'],'userid' => $row['userid'],'actiontype' => $row['actiontype'],'pagename' => $row['pagename'],'description' => $row['description']);
	
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
				$query = 'SELECT * FROM actions';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$results = $db->Execute($stmt);
			
				$retArray = array();
				foreach( $results as $row )
				{
					$object = (object) array('actionid' => $row['actionid'],'userid' => $row['userid'],'actiontype' => $row['actiontype'],'pagename' => $row['pagename'],'description' => $row['description']);
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

		function del($actionid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'DELETE FROM actions WHERE actionid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $actionid);
				$results = $db->Execute($stmt);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		}

		function update($userid,$actiontype,$pagename,$description)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'UPDATE actions SET userid = ?,actiontype = ?,pagename = ?,description = ? WHERE actionid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("sssss", $userid,$actiontype,$pagename,$description, $actionid);
				$results = $db->Execute($stmt);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		}

		///// Application Specific Functions

	}

?>
