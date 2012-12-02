<?

	require_once("sqlcredentials.php");
	require_once("town.class.php");
	require_once("debug.php");

	class TownListTool
	{
	
		public function getList()
		{
		
			dprint("Connecting to DB ...");
			
			$chandle = mysql_connect($mysql_host, $mysql_user, $mysql_pass) 
				or die("Connection Failure to Database");				// TODO: something more elegant than this
			mysql_select_db($mysql_database, $chandle) 
				or die ($mysql_database . " Database not found. " . $mysql_user);	// TODO: something more elegant than this
				
			dprint("... Done.");
				
			$query = "SELECT townname,towntype,townurl FROM Towns";
			
			dprint("Fetching town list");
			
			// perform query
			$result = mysql_db_query($mysql_database, $query)
				or die("Failed Query of " . $query);  			// TODO: something more elegant than this
				
			dprint("Parsing results, count = " . count($result));
			
			$towns = array();
			while($r = mysql_fetch_assoc($result)) {
				
				$town = new Town();
				
				$town->name = $r['townname'];
				$town->type = $r['towntype'];
				$town->url = $r['townurl'];
				
				
				//dprint("name: " . $town->name);
				//dprint("name: " . $town->url);
				
				$towns[] = $town;
			}

			dprint("Done.");

			return $towns;
		
		}
		
		public function decodeTownID($id)
		{
		
			dprint("decodeTownID()");
		
			// set our return error as None for default
			$response = null;
		
			dprint("Using TownID = " . $id);
		
			dprint("Connecting to DB ...");
			
			// connect to the mysql database server.  Constants taken from 
			// sqlcredentials.php
			$chandle = mysql_connect($mysql_host, $mysql_user, $mysql_pass) 
				or die("Connection Failure to Database");				// TODO: something more elegant than this
			mysql_select_db($mysql_database, $chandle) 
				or die ($mysql_database . " Database not found. " . $mysql_user);	// TODO: something more elegant than this

			dprint("... Done.");
			
			// create our query
			$query = "SELECT townname FROM Towns WHERE townid = " . $id;
			
			// perform query
			$result = mysql_db_query($mysql_database, $query)
				or die("Failed Query of " . $query);  			// TODO: something more elegant than this
			
			// get the response
			$townName = mysql_result($result,0);
			
			dprint("Town Name: " . $townName);
			
			return $townName;
			
		}
	
	}

?>
