<?

	require_once("DatabaseTool.class.php");

	class OrganizationsManager
	{
		function add($name,$type,$websiteurl)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'INSERT INTO organizations(name,type,websiteurl) VALUES(?,?,?)';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("sss", $name,$type,$websiteurl);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('organizationid' => $row['organizationid'],'name' => $row['name'],'type' => $row['type'],'websiteurl' => $row['websiteurl']);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		
			return $retVal;
		}

		function get($organizationid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'SELECT * FROM organizations WHERE organizationid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $organizationid);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('organizationid' => $row['organizationid'],'name' => $row['name'],'type' => $row['type'],'websiteurl' => $row['websiteurl']);
	
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
				$query = 'SELECT * FROM organizations';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$results = $db->Execute($stmt);
			
				$retArray = array();
				foreach( $results as $row )
				{
					$object = (object) array('organizationid' => $row['organizationid'],'name' => $row['name'],'type' => $row['type'],'websiteurl' => $row['websiteurl']);
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

		function del($organizationid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'DELETE FROM organizations WHERE organizationid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $organizationid);
				$results = $db->Execute($stmt);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		}

		function update($name,$type,$websiteurl)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'UPDATE organizations SET name = ?,type = ?,websiteurl = ? WHERE organizationid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("ssss", $name,$type,$websiteurl, $organizationid);
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
