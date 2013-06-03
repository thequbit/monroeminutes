<?

	require_once("DatabaseTool.class.php");

	class PermissionsManager
	{
		function add($isadmin,$canlogin)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'INSERT INTO permissions(isadmin,canlogin) VALUES(?,?)';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("ss", $isadmin,$canlogin);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('permissionid' => $row['permissionid'],'isadmin' => $row['isadmin'],'canlogin' => $row['canlogin']);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		
			return $retVal;
		}

		function get($permissionid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'SELECT * FROM permissions WHERE permissionid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $permissionid);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('permissionid' => $row['permissionid'],'isadmin' => $row['isadmin'],'canlogin' => $row['canlogin']);
	
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
				$query = 'SELECT * FROM permissions';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$results = $db->Execute($stmt);
			
				$retArray = array();
				foreach( $results as $row )
				{
					$object = (object) array('permissionid' => $row['permissionid'],'isadmin' => $row['isadmin'],'canlogin' => $row['canlogin']);
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

		function del($permissionid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'DELETE FROM permissions WHERE permissionid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $permissionid);
				$results = $db->Execute($stmt);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		}

		function update($isadmin,$canlogin)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'UPDATE permissions SET isadmin = ?,canlogin = ? WHERE permissionid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("sss", $isadmin,$canlogin, $permissionid);
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
