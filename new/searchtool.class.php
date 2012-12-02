<?

	require_once("debug.php");
	require_once("sqlcredentials.php");
	require_once("Minutes.class.php");
	require_once("OrganizationsTool.class.php");
	require_once("DocumentTool.class.php");

	// this class helps perform the search using the input values from the user
	class SearchTool
	{
		
		//
		// $startdate - date to start searching for documents
		// $enddate - date to stop searching for docuemtns
		// $organizations - array of organizations to search with
		// $searchstring - the text entered by the user to search for within the minutes text
		//
		// returns - This returns an array of metting minutes pulled from the database
		//
		function SearchWithoutAddress($startdate, $enddate, $organizations, $searchstring)
		{
			dprint("keywordSearchWithTown()");
			
			
			
			dprint("Trying to connect to database ...");
			
			// connect to the mysql database server.  Constants taken from sqlcredentials.php
			$chandle = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS)
				or die("Connection Failure to Database");				// TODO: something more elegant than this

			mysql_select_db(MYSQL_DATABASE, $chandle)
				or die (MYSQL_DATABASE . " Database not found. " . MYSQL_USER);	// TODO: something more elegant than this

			dprint("Connected to DB.");

			/*

			// first we need to deturmine if we are looking based on a date range
			if( $startdate == "" || $startdate == null || $enddate == "" || $enddate == null )
			{
				// no date range was specified, we will not be using that information
				$usedate = false;
			}
			else
			{
				// we will be using the date as it is a valid input
				//
				//	TODO: add aditional sanity checking here
				//
				$usedate = true;
			}

			// do we use organizations?
			if( $organizations == "" || $organizations == null )
			{
				$useorganizations = false;
			}
			else
			{
				$useorganizations = true;
			}

			// do we use the search string?
			if( $searchstring == "" || $searchstring == null )
			{
				$usesearchstring = false;
			}
			else
			{
				$usesearchstring = true;
			}

			*/

			// define our return array
			$response = array();
			
			$docTool = new DocumentTool();
			$documents = array();

			// get all of the documents that are associated with the organization(s)
			if( $organizations == "" || $organizations == null )
			{
			
				// there are more than one key word, we will be performing multiple searches
				
				$keywords = explode(" ",$searchstring);
		
				// see if there is more than one word
				$pos = strpos($searchstring, " ");
				if( $pos === false )
				{
					// single keyword
					
					$query = "select documents.* from documents, wordfrequency where wordfrequency.word=" . $keyword . " AND wordfrequency.documentid=documents.documentid"
						
					// pull from DB
					$result = mysql_db_query(MYSQL_DATABASE, $query)
						or die("Failed Query of " . $query);  			// TODO: something more elegant than this
						
					//
					//
					//		TODO: add this result to the array to be returned
					//
					//
					
				}
				else
				{
					foreach($keywords as $keyword)
					{
						
						$query = "select documents.* from documents, wordfrequency where wordfrequency.word=" . $keyword . " AND wordfrequency.documentid=documents.documentid"
						
						// pull from DB
						$result = mysql_db_query(MYSQL_DATABASE, $query)
							or die("Failed Query of " . $query);  			// TODO: something more elegant than this
						

						//
						//
						//		TODO: add this result to the array to be returned
						//
						//
			
					}
					
				}
			
			}
			else
			{
				
				// get the id of the suborg
				$orgTool = new OrganizationsTool();
				
				// itterate through the list of organizations adding their docs to the documents array
				foreach($organizations as $org)
				{
					$id = $orgTool->SubOrgIdFromName($orgName);
				
					// see if there is more than one word
					$pos = strpos($searchstring, " ");
					if( $pos === false )
					{
						
						// single keyword
					
						$query = "select documents.* from documents, wordfrequency where wordfrequency.word=" . $keyword . " AND wordfrequency.documentid=documents.documentid"
							
						// pull from DB
						$result = mysql_db_query(MYSQL_DATABASE, $query)
							or die("Failed Query of " . $query);  			// TODO: something more elegant than this
							
						//
						//
						//		TODO: add this result to the array to be returned
						//
						//
					
					}
					else
					{
					
						// get the list of keywordss
						$keywords = explode(" ",$searchstring);
		
						foreach($keywords as $keyword)
						{
							
							$query = "select documents.* from documents, wordfrequency where wordfrequency.word=" . $keyword . " AND wordfrequency.documentid=documents.documentid"
							
							// pull from DB
							$result = mysql_db_query(MYSQL_DATABASE, $query)
								or die("Failed Query of " . $query);  			// TODO: something more elegant than this
							

							//
							//
							//		TODO: add this result to the array to be returned
							//
							//
				
						}
						
					}
					
				}
				
			}


			/*
			
			// generate an array to put our responses in
			$response = array();
		
			// get responses and put them into an array of Minutes
			while($r = mysql_fetch_assoc($result)) {
			
				dprint("creating objects");
			
				// create our object to be populated with our DB result
				$minutes = new Minutes();
			
				// create an instance of our tool that will allow us to 
				$orgtool = new OrganizationsTool();
				
				//dprint("decoding suborg and org names from id's");
				
				// using the sub organizations id pull it's name from the database
				$orgName = $orgtool->OrgNameFromID($r['suborganizationid']);
				$subOrgName = $orgtool->SubOrgNameFromID($r['suborganizationid']);
			
				//dprint("populating minutes object");
			
				// populate the Minutes object with the returned data from the DB 
				$minutes->organizationname = $orgName;
				$minutes->suborganizationname = $subOrgName;
				$minutes->date = $r['date'];
				$minutes->minutesurl = $r['minutesurl'];
				$minutes->minutetext = $r['minutestext'];
			
				//dprint("done.");
			
				// debug output
				dprint("Organization Name = '" . $minutes->suborganizationname . "'");
				dprint("Suborganization Name = '" . $minutes->organizationname . "'");
				dprint("Date = '" . $minutes->date . "'");
				dprint("Minutes URL = '" . $minutes->minutesurl . "'");
				dprint("Minutes Text = '" . $minutes->minutetext . "'");
			
				// add this object to the array of Minutes
				$response[] = $minutes;
			}
	
			*/
	
			//dprint("Returning response array");

			// return the array of found minutes
			return $response;
		}
	}

?>