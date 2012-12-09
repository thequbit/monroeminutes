<?php

	require_once("debug.php");
	//require_once("sqlcredentials.php");
	require_once("DatabaseTool.class.php");

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
			
			// connect to DB
			$dbtool = new DatabaseTool();
			$chandle = $dbtool->Connect();
			
			// get the total number of organizations from the data base
			$query = "SELECT name FROM organizations";
			$result = $dbtool->Query($query,$chandle);
			
			dprint("itterating through db response and creating return array");
			
			// itterate through the results and populate an array
			while($r = mysql_fetch_assoc($result)) {
			
				// using the sub organizations id pull it's name from the database
				$orgName = $r['name'];
			
				// add the new name to the list of names
				$retVal[] = $orgName;			
			}
			
			// return the created array
			return $retVal;
		}
	
		function GetAllSubOrganizationNames()
		{
			$retVal = array();
			
			// connect to DB
			$dbtool = new DatabaseTool();
			$chandle = $dbtool->Connect();
			
			// select all suborganizations
			$query = "SELECT name FROM suborganizations";
			$result = $dbtool->Query($query,$chandle);
			
			dprint("itterating through db response and creating return array");
			
			// itterate through the results and populate an array
			while($r = mysql_fetch_assoc($result)) {
			
				// using the sub organizations id pull it's name from the database
				$orgName = $r['name'];
			
				// add the new name to the list of names
				$retVal[] = $orgName;
			
			}
			
			return $retVal;
		}
	
		function GetAllCategoryNames()
		{
		
			$retVal = array();
		
			/*
	
			// connect to the mysql database server.  Constants taken from sqlcredentials.php
			$chandle = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS)
				or die("Connection Failure to Database");				// TODO: something more elegant than this

			mysql_select_db(MYSQL_DATABASE, $chandle)
				or die (MYSQL_DATABASE . " Database not found. " . MYSQL_USER);	// TODO: something more elegant than this

			//dprint("Connected to DB.");
			
			$query = "SELECT * FROM suborganization";
			
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
			
			*/
			
			return $retVal;
		}
	
		function SubOrgIdFromName($name)
		{
			$retVal = -1;
			
			// connect to DB
			$dbtool = new DatabaseTool();
			$chandle = $dbtool->Connect();
			
			// get sub org id from name
			$query = "SELECT suborganizationid FROM suborganizations where name='" . $name . "'";
			$result = $dbtool->Query($query,$chandle);
			
			// pull the id from the result
			$retVal = mysql_result($result,0);
			
			return $retVal;
		}
		
		function OrgIdFromName($name)
		{
			$retVal = -1;
			
			// connect to DB
			$dbtool = new DatabaseTool();
			$chandle = $dbtool->Connect();
			
			// get the idea from the name via the database
			$query = "SELECT organizationid FROM organizations where name='" . $name . "'";
			$result = $dbtool->Query($query,$chandle);
			
			// pull the id from the result
			$retVal = mysql_result($result,0);
			
			return $retVal;
		}
	
		function SubOrgNameFromID($suborgid)
		{
		
			// connect to DB
			$dbtool = new DatabaseTool();
			$chandle = $dbtool->Connect();
		
			// get the name of the suborg from the id
			$query = "SELECT name FROM suborganizations where suborganizationid=" . $suborgid;
			$result = $dbtool->Query($query,$chandle);
			
			$r = mysql_fetch_row($result);
		
			$retVal = $r[0];
		
			// return the suborg name
			return $retVal;
		
		}
		
		function OrgNameFromID($orgid)
		{
		
			// connect to DB
			$dbtool = new DatabaseTool();
			$chandle = $dbtool->Connect();
		
			// get the name of the suborg from the id
			$query = "SELECT name FROM organizations where organizationid=" . $orgid;
			$result = $dbtool->Query($query,$chandle);
			
			$r = mysql_fetch_row($result);
		
			$retVal = $r[0];
		
			// return the suborg name
			return $retVal;
		
		}
		
		function OrgIdFromSubOrgId($suborgid)
		{
			// connect to DB
			$dbtool = new DatabaseTool();
			$chandle = $dbtool->Connect();
			
			// get the name of the suborg from the id
			$query = "SELECT organizationid FROM suborganizations where suborganizationid=" . $suborgid;
			$result = $dbtool->Query($query,$chandle);
			
			// get first (only) result
			$r = mysql_fetch_row($result);
		
			// pull name from result
			$retVal = $r[0];
		
			// return the suborg name
			return $retVal;
		}
		
		function OrgNameFromSubOrgID($suborgid)
		{
			
			// get org id from suborg id
			$orgid = $this->OrgIdFromSubOrgId($suborgid);
			
			// get org name from org id
			$retVal = $this->OrgNameFromID($orgid);
			
			// return orgname
			return $retVal;
		}

	}

?>