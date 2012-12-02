<?php

	require_once("debug.php");
	require_once("sqlcredentials.php");

	class OrganizationsTool
	{
		function GetAllOrganizationNames()
		{
			$retVal = array();
			
			//dprint("Trying to connect to database ...");
			
			//dprint("test: " . $test);
			//dprint("host: " . MYSQL_HOST);
			//dprint("user: " . MYSQL_USER);
			//dprint("pass: " . MYSQL_PASS);
			//dprint("db: " . MYSQL_DATABASE);
			
			// connect to the mysql database server.  Constants taken from sqlcredentials.php
			$chandle = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS)
				or die("Connection Failure to Database");				// TODO: something more elegant than this

			mysql_select_db(MYSQL_DATABASE, $chandle)
				or die (MYSQL_DATABASE . " Database not found. " . MYSQL_USER);	// TODO: something more elegant than this

			//dprint("Connected to DB.");
			
			$query = "SELECT * FROM organizations";
			
			// pull from DB
			$result = mysql_db_query(MYSQL_DATABASE, $query)
				or die("Failed Query of " . $query);  			// TODO: something more elegant than this
			
			//dprint("itterating through db response and creating return array");
			
			// itterate through the results and populate an array
			while($r = mysql_fetch_assoc($result)) {
			
				// using the sub organizations id pull it's name from the database
				$orgName = $r['name'];
			
				//dprint("Found: '" .$orgName ."'");
			
				// add the new name to the list of names
				$retVal[] = $orgName;
			
			}
			
			return $retVal;
		}
	
		function GetAllSubOrganizationNames()
		{
			$retVal = array();
			
			// connect to the mysql database server.  Constants taken from sqlcredentials.php
			$chandle = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS)
				or die("Connection Failure to Database");				// TODO: something more elegant than this

			mysql_select_db(MYSQL_DATABASE, $chandle)
				or die (MYSQL_DATABASE . " Database not found. " . MYSQL_USER);	// TODO: something more elegant than this

			//dprint("Connected to DB.");
			
			$query = "SELECT * FROM suborganizations";
			
			// pull from DB
			$result = mysql_db_query(MYSQL_DATABASE, $query)
				or die("Failed Query of " . $query);  			// TODO: something more elegant than this
			
			//dprint("itterating through db response and creating return array");
			
			// itterate through the results and populate an array
			while($r = mysql_fetch_assoc($result)) {
			
				// using the sub organizations id pull it's name from the database
				$orgName = $r['name'];
			
				//dprint("Found: '" .$orgName ."'");
			
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