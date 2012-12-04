<?php

	require_once("../debug.php");
	require_once("../sqlcredentials.php");
	require_once("Permissions.class.php");

	class LoginTool
	{
	
		// checks credentials of a login.  returns -1 for invalid login, or the 
		// permissions id of the user if it is valid
		function CheckCredentials($username, $password)
		{
		
			$retVal = -1;
		
			//
			// connect to db
			//
			
			dprint("connecting to DB ...");
	
			// connect to the mysql database server.  Constants taken from sqlcredentials.php
			$chandle = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS)
				or die("Connection Failure to Database");				// TODO: something more elegant than this

			mysql_select_db(MYSQL_DATABASE, $chandle)
				or die (MYSQL_DATABASE . " Database not found. " . MYSQL_USER);	// TODO: something more elegant than this
		
			//
			// check to see if username/pass combo exists
			//
			
			dprint("Connected to DB, checking if username/password is correct.");
			
			$passwordhash = md5($password);
			$query = 'SELECT permissionsid FROM users WHERE username="' . $username . '" AND passwordhash="' . $passwordhash . '"';
			
			dprint("Sending: " . $query);

			$result = mysql_db_query(MYSQL_DATABASE, $query)
				or die("Failed Query of " . $query);  			// TODO: something more elegant than this
			
			
			
			//
			// decode permisions id
			//
			
			// check to see if we got a response from the DB
			if( mysql_num_rows($result) > 0 )
			{
				// there was a response, pull out the premissionsid and return it
				
				// pull response from the array of responses
				$r = mysql_fetch_assoc($result);
				
				// set our return value to the permissionid of the user
				$retVal = $r['permissionsid'];
				
				dprint("User found, returning permissionsid: " . $retVal);
			}
			else
			{
				// return -1 since there was no response
				$retVal = -1;
				
				dprint("User not found.");
			}
			
			//
			// return permissions id
			//
			
			return $retVal;
		}
	
		// this function returns a Permissions object based on the permissionsid that it is sent
		function GetPermissionsByID($permissionsid)
		{	
	
			//
			// Connect to DB
			//
	
			dprint("connecting to DB ...");
	
			// connect to the mysql database server.  Constants taken from sqlcredentials.php
			$chandle = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS)
				or die("Connection Failure to Database");				// TODO: something more elegant than this

			mysql_select_db(MYSQL_DATABASE, $chandle)
				or die (MYSQL_DATABASE . " Database not found. " . MYSQL_USER);	// TODO: something more elegant than this

			dprint("Connected to DB.");
			
			//
			// pull permissions for the specified permissionsid from the DB
			//
			
			// TODO: check to see if $permissionsid is an int
			
			$query = 'SELECT * FROM permissions WHERE permissionsid=' . $permissionsid;
			
			dprint("Sending: " . $query);
			
			// pull from DB
			$result = mysql_db_query(MYSQL_DATABASE, $query)
				or die("Failed Query of " . $query);  			// TODO: something more elegant than this
			
			//
			// pupulate the Permissions object with the returned data
			//
			
			$retVal = new Permissions();
			
			// check to see if the permissionsid was valid
			if( mysql_num_rows($result) > 0 )
			{
				dprint("Valid permissionsid.");
				
				// get the first responce from the DB
				$r = mysql_fetch_assoc($result);
		
				// mark good permissionsid
				$retVal->validlogin = true;
		
				// using the sub organizations id pull it's name from the database
				$retVal->canlogin = $r['canlogin'];
				$retVal->isadmin = $r['isadmin'];
				$retVal->enabled = $r['enabled'];
			
				dprint("Can Login: " . $retVal->canlogin);
				$type = gettype($retVal->canlogin);
				dprint("type: " . $type);
				
				dprint("Is Admin: " . $retVal->isadmin);
				$type = gettype($retVal->isadmin);
				dprint("type: " . $type);
				
				dprint("Enabled: " . $retVal->enabled);
				$type = gettype($retVal->enabled);
				dprint("type: " . $type);
			}
			else
			{
				dprint("Invalid permissionsid.");
				
				// set bad id
				$retVal->validlogin = false;
			}
			
			//
			// return the Permissions object
			//
		
			// return the array
			return $retVal;
		}
	
	}

?>