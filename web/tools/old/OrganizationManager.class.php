<?php

	require_once("DatabaseTool.class.php");

	class OrganizationManager
	{
		function GetAllOrganizations()
		{
			dprint( "GetAllOrganizations() Start." );
			
			try
			{
				$db = new DatabaseTool();
			
				$query = 'SELECT * from organizations';
				$mysqli = $db->Connect();
				$stmt = $mysqli->prepare($query);
				//$stmt->bind_param("s", $albumid);
				$results = $db->Execute($stmt);
			
				$organizations = array();
				foreach( $results as $row )
				{
					$organization = (object) array( 'id' => $row['organizationid'],
													'name' => $row['name'],
													'type' => $row['type']
												  );
											
					$organizations[] = $organization;
				}
	
				$db->Close($mysqli, $stmt);
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
			
			dprint("GetAllOrganizations() Done.");
			
			return $organizations;
		}
	
	}
	
?>