<?php

	require_once("debug.php");
	require_once("sqlcredentials.php");

	class DatabaseTool
	{
	
		function SanitizeInput($input)
		{
			// first ensure there are escape chars
			$retVal = mysql_real_escape_string($input);
			
			// return the sanitized string
			return $retVal;
		}
		
		function Connect()
		{
			dprint("Trying to connect to database ...");
			
			// connect to the mysql database server.  Constants taken from sqlcredentials.php
			$chandle = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS)
				or die("Connection Failure to Database");				// TODO: something more elegant than this

			mysql_select_db(MYSQL_DATABASE, $chandle)
				or die (MYSQL_DATABASE . " Database not found. " . MYSQL_USER);	// TODO: something more elegant than this

			dprint("Connected to DB.");
			
			return $chandle;
		}
		
		function Query($query, $chandle)
		{
			dprint("running: '" . $query . "'");
				
			// pull from DB
			$result = mysql_db_query(MYSQL_DATABASE, $query)
				or die("Failed Query of " . $query);  			// TODO: something more elegant than this
			
			return $result;
		}
	
	}

?>