<?php

	require_once("sqlcredentials.php");
	require_once("minutes.class.php");
	require_once("townlisttool.class.php");
	require_once("debug.php");

	// this class is responciple for communicated with the mysql database and
	// performing all search queries.
	class KeyWordSearchTool
	{
	
		// this function takes in a keyword and will return a list of all of the
		// entrees in the database with that keyword in it. It returns an array 
		// of Minutes objects.
		public function keywordSearch($keyword)
		{
			dprint("keywordSearch()");
		
			$value = $this->keywordSearchWithTown($keyword, null);
		
			return $value;
		}

		// this function takes in a keyword and a town name and will return all
		// of the entrees within the database with the keyword in it that is 
		// associated with that town.  It returns an array of Minutes objects.
		function keywordSearchWithTown($keyword, $town)
		{
			dprint("keywordSearchWithTown()");
		
			// set our return error as None for default
			$response = null;
		
			dprint("Using Keyword = " . $keyword);
			dprint("Using Town = " . $town);
		
			dprint("Connecting to DB ...");
			
			dprint(" ... working ... ");
			
			// connect to the mysql database server.  Constants taken from 
			// sqlcredentials.php
			$chandle = mysql_connect($mysql_host, $mysql_user, $mysql_pass);  
			//	or die("Connection Failure to Database");				// TODO: something more elegant than this

			dprint("Connection made, selecting database ...");

			mysql_select_db($mysql_database, $chandle);  
			//	or die ($mysql_database . " Database not found. " . $mysql_user);	// TODO: something more elegant than this

			dprint("... Done.");

			// we need to create our query based on values passed in
			if( $town != null )
			{
				// we need to query the database to see if the town exists in the database
				$query = "SELECT * FROM Towns WHERE townname = '" . $town . "'";
				
				dprint("Performing town query");
				
				// perform the query
				$result = mysql_db_query($mysql_database, $query)
					or die("Failed Query of " . $query);  				// TODO: something more elegant than this
				
				// pull the townid from the result
				$townid = mysql_result($result,0,"townid");
				
				dprint("TownID = " . $townid);
				
				// test to see if the town name was valid, and if so then 
				// proceed to create the query for the keyword search
				if( !$townid )
				{
					dprint("Error: Town Not Valid");
				
					// the town is not valid, set the error and move on.
					$arr = array('error' => "Town Not Valid");
					$response = json_encode($arr);
				}
				else
				{
					// the town was valid, we now need to query for all items
					// that have the keyword and are in that town
					$query = "SELECT minutestext,townid,minutesdate,url FROM Minutes WHERE townid = " . $townid . " AND minutestext LIKE '%" . $keyword . "%'";
					
				}
			
			}
			else
			{
			
				dprint("Not Using Town for Query");
			
				// we need to query for all items that have the keyword, reguardless of
				// what town they are from
				$query = "SELECT minutestext,townid,minutesdate,url FROM Minutes WHERE minutestext LIKE '%" . $keyword . "%'";
			}
			
			dprint("Performing keyword Query");
			
			dprint($query);
					
			// perform query
			$result = mysql_db_query($mysql_database, $query)
				or die("Failed Query of " . $query);  			// TODO: something more elegant than this
			
			dprint("Encoding in array of Minutes");
			
			$response = array();
			
			while($r = mysql_fetch_assoc($result)) {
			
				// create our object to be populated with our DB result
				$minutes = new Minutes();
			
				// create an instance of our tool that will allow us to 
				$townListTool = new TownListTool();
				
				$townName = $townListTool->decodeTownID($r['townid']);
			
				$minutes->townName = $townName;
				$minutes->date = $r['minutesdate'];
				$minutes->url = $r['url'];
				
				$text = $r['minutestext'];
				$summary = $this->createSummary($keyword, $text, 256);
			
				$minutes->summary = $summary;
			
				// debug output
				dprint("Town Name = " . $minutes->townName);
				dprint("Date = " . $minutes->date);
				dprint("URL = " . $minutes->url);
				dprint("Summary = " . $minutes->summary);
			
				$response[] = $minutes;
			}
			
			dprint("Returning response array");

			// all done!
			return $response;
			
		}
		
		// this function is going to create a small blurb of text that is
		// the text surounding the keyword on each side (or one side of the
		// keyword is found at the start or end of the document).  The
		// $length input is the length of the summary - it will be generated
		// as 50% on each side of the keyword.
		private function createSummary($keyword, $text, $length)
		{
			// get the possition of the first instance of the keyword
			$pos = strpos(strtolower ($text), strtolower($keyword), 0);
			
			$start = ($pos - ($length/2));
			
			// check to see if the length desired is greated than the text
			// length, and if it is then just set the start to 0, and we
			// will return all of it.
			if( strlen($text) < $length )
			{
				$start = 0;
			}
			else
			{
				if( $pos < ($length/2) )
				{
					// we will just start at the beginning of the document
					// since there isn't enough roomfor $length/2
					$start = 0;
				}
				
				if( $pos > (strlen($text) - ($length/2)) )
				{
					// move the starting point back enough so we maintain
					// our designed length
					$start = $length - ( strlen($text) - $pos );
				}
				
			}
			
			// cut the part we want out of the string
			$summary = substr($text, $start, $length);
			
			// return our summary text
			return $summary;
		}
		
	
	}

?>
