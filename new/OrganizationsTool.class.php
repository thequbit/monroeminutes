<?

	class OrganizationsTool
	{
		function GetAllOrganizationNames()
		{
			$retVal = array();
			
			dprint("Trying to connect to database ...");
			
			// connect to the mysql database server.  Constants taken from sqlcredentials.php
			$chandle = mysql_connect($mysql_host, $mysql_user, $mysql_pass)
				or die("Connection Failure to Database");				// TODO: something more elegant than this

			mysql_select_db($mysql_database, $chandle)
				or die ($mysql_database . " Database not found. " . $mysql_user);	// TODO: something more elegant than this

			dprint("Connected to DB.");
			
			$query = "SELECT * FROM organizations";
			
			// pull from DB
			$result = mysql_db_query($mysql_database, $query)
				or die("Failed Query of " . $query);  			// TODO: something more elegant than this
			
			// itterate through the results and populate an array
			while($r = mysql_fetch_assoc($result)) {
			
				// create our object to be populated with our DB result
				$minutes = new Minutes();
			
				// create an instance of our tool that will allow us to 
				$orgtool = new OrganizationsTool();
				
				// using the sub organizations id pull it's name from the database
				$orgName = $r['name'];
			
				// add the new name to the list of names
				$retVal[] = $orgName;
			
			}
			
			return $retVal;
		}
	
		function SubOrgNameFromID($suborgid)
		{
		
			$retVal = "Happy Town!";
		
			// TODO: Pull suborg name from DB
		
			return $retVal;
		
		}
		
		function OrgNameFromID($suborgid)
		{
		
			$retVal = "Happy City!";
		
			// TODO: Pull org name from DB
		
			return $retVal;
		
		}
	}

?>