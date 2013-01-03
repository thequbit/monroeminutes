<?php

	// this tool will help you perform searches with documents
	
	require_once("debug.php");
	require_once("sqlcredentials.php");
	
	class DocumentTool
	{
	
		function GetTotalNumberOfDocuments()
		{
			// connect to DB
			$dbtool = new DatabaseTool();
			$chandle = $dbtool->Connect();
			
			$orgname = $dbtool->SanitizeInput($orgname);
			
			// see if the orgname already exists
			$query = 'SELECT count(*) FROM documents';
			$results = $dbtool->Query($query,$chandle);
			
			// get count
			$r = mysql_fetch_assoc($results);
			$retVal = $r["count(*)"];
			
			// return the number of documents
			return $retVal;
		}
	
		function GetTotalNumberOfWords()
		{
			// connect to DB
			$dbtool = new DatabaseTool();
			$chandle = $dbtool->Connect();
			
			$orgname = $dbtool->SanitizeInput($orgname);
			
			// see if the orgname already exists
			$query = 'SELECT count(*) FROM wordfrequency';
			$results = $dbtool->Query($query,$chandle);
			
			// get count
			$r = mysql_fetch_assoc($results);
			$retVal = $r["count(*)"];
			
			// return the number of documents
			return $retVal;
		}
	
		function GetAllDocumentIds()
		{
		
			$retVal = array();
			
			dprint("connecting to DB ...");
	
			// connect to the mysql database server.  Constants taken from sqlcredentials.php
			$chandle = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS)
				or die("Connection Failure to Database");				// TODO: something more elegant than this

			mysql_select_db(MYSQL_DATABASE, $chandle)
				or die (MYSQL_DATABASE . " Database not found. " . MYSQL_USER);	// TODO: something more elegant than this

			//dprint("Connected to DB.");
			
			$query = "SELECT * FROM documents";
			
			// pull from DB
			$result = mysql_db_query(MYSQL_DATABASE, $query)
				or die("Failed Query of " . $query);  			// TODO: something more elegant than this
			
			//dprint("itterating through db response and creating return array");
			
			// itterate through the results and populate an array
			while($r = mysql_fetch_assoc($result)) {
			
				// using the sub organizations id pull it's name from the database
				$docid = $r['documentid'];
			
				//dprint("Found: '" .$orgName ."'");
			
				// add the new name to the list of names
				$retVal[] = $docid;
			
			}
		
			// return the array
			return retVal;
		}
	
		// return an array of documentsid's based on org id
		function GetDocumentIdsBySubOrganizationID($suborgid)
		{
		
			$retVal = array();
			
			dprint("connecting to DB ...");
	
			// connect to the mysql database server.  Constants taken from sqlcredentials.php
			$chandle = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS)
				or die("Connection Failure to Database");				// TODO: something more elegant than this

			mysql_select_db(MYSQL_DATABASE, $chandle)
				or die (MYSQL_DATABASE . " Database not found. " . MYSQL_USER);	// TODO: something more elegant than this

			//dprint("Connected to DB.");
			
			$query = "SELECT * FROM documents WHERE suborganization=" . $suborgid;
			
			// pull from DB
			$result = mysql_db_query(MYSQL_DATABASE, $query)
				or die("Failed Query of " . $query);  			// TODO: something more elegant than this
			
			//dprint("itterating through db response and creating return array");
			
			// itterate through the results and populate an array
			while($r = mysql_fetch_assoc($result)) {
			
				// using the sub organizations id pull it's name from the database
				$docid = $r['documentid'];
			
				//dprint("Found: '" .$orgName ."'");
			
				// add the new name to the list of names
				$retVal[] = $docid;
			
			}
		
			// return the array
			return retVal;
		
		}
	
	}

?>