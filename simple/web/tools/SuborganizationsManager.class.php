<?

	require_once("DatabaseTool.class.php");

	class SuborganizationsManager
	{
		function add($organizationid,$name,$parsename,$websiteurl,$documentsurl,$scriptname,$dbpopulated)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'INSERT INTO suborganizations(organizationid,name,parsename,websiteurl,documentsurl,scriptname,dbpopulated) VALUES(?,?,?,?,?,?,?)';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("sssssss", $organizationid,$name,$parsename,$websiteurl,$documentsurl,$scriptname,$dbpopulated);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('suborganizationid' => $row['suborganizationid'],'organizationid' => $row['organizationid'],'name' => $row['name'],'parsename' => $row['parsename'],'websiteurl' => $row['websiteurl'],'documentsurl' => $row['documentsurl'],'scriptname' => $row['scriptname'],'dbpopulated' => $row['dbpopulated']);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		
			return $retVal;
		}

		function get($suborganizationid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'SELECT * FROM suborganizations WHERE suborganizationid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $suborganizationid);
				$results = $db->Execute($stmt);
			
				$row = $results[0];
				$retVal = (object) array('suborganizationid' => $row['suborganizationid'],'organizationid' => $row['organizationid'],'name' => $row['name'],'parsename' => $row['parsename'],'websiteurl' => $row['websiteurl'],'documentsurl' => $row['documentsurl'],'scriptname' => $row['scriptname'],'dbpopulated' => $row['dbpopulated']);
	
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
				$query = 'SELECT * FROM suborganizations';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$results = $db->Execute($stmt);
			
				$retArray = array();
				foreach( $results as $row )
				{
					$object = (object) array('suborganizationid' => $row['suborganizationid'],'organizationid' => $row['organizationid'],'name' => $row['name'],'parsename' => $row['parsename'],'websiteurl' => $row['websiteurl'],'documentsurl' => $row['documentsurl'],'scriptname' => $row['scriptname'],'dbpopulated' => $row['dbpopulated']);
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

		function del($suborganizationid)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'DELETE FROM suborganizations WHERE suborganizationid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("s", $suborganizationid);
				$results = $db->Execute($stmt);
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		}

		function update($organizationid,$name,$parsename,$websiteurl,$documentsurl,$scriptname,$dbpopulated)
		{
			try
			{
				$db = new DatabaseTool(); 
				$query = 'UPDATE suborganizations SET organizationid = ?,name = ?,parsename = ?,websiteurl = ?,documentsurl = ?,scriptname = ?,dbpopulated = ? WHERE suborganizationid = ?';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				$stmt->bind_param("ssssssss", $organizationid,$name,$parsename,$websiteurl,$documentsurl,$scriptname,$dbpopulated, $suborganizationid);
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
