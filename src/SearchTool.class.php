<?

	require_once("debug.php");
	require_once("sqlcredentials.php");
	require_once("OrganizationsTool.class.php");
	//require_once("DocumentTool.class.php");
	require_once("SearchResult.class.php");
	require_once("DatabaseTool.class.php");

	// this class helps perform the search using the input values from the user
	class SearchTool
	{
	
		// this function simply just increments the number of searches performed by 1
		function IncrementNumberOfSearches()
		{
			
			dprint("Trying to connect to database ...");
			
			// connect to the mysql database server.  Constants taken from sqlcredentials.php
			$chandle = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS)
				or die("Connection Failure to Database");				// TODO: something more elegant than this

			mysql_select_db(MYSQL_DATABASE, $chandle)
				or die (MYSQL_DATABASE . " Database not found. " . MYSQL_USER);	// TODO: something more elegant than this

			dprint("Connected to DB.");
			
			$query = 'select totalsearches from searches';
					
			dprint("running: '" . $query . "'");
			
			// pull from DB
			$result = mysql_db_query(MYSQL_DATABASE, $query)
				or die("Failed Query of " . $query);  			// TODO: something more elegant than this
			
			$r = mysql_fetch_assoc($result);
			
			$totalSearches = $r['totalsearches'];
			
			$query = 'update searches set totalsearches=' . $totalSearches + 1;
					
			dprint("running: '" . $query . "'");
			
			$result = mysql_db_query(MYSQL_DATABASE, $query)
				or die("Failed Query of " . $query);  			// TODO: something more elegant than this
			
		}
	
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
			
			dprint("Trying to connect to database ...");
			
			// connect to the mysql database server.  Constants taken from sqlcredentials.php
			$chandle = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS)
				or die("Connection Failure to Database");				// TODO: something more elegant than this

			mysql_select_db(MYSQL_DATABASE, $chandle)
				or die (MYSQL_DATABASE . " Database not found. " . MYSQL_USER);	// TODO: something more elegant than this

			dprint("Connected to DB.");

			// use our database tool to sanitize inputs
			$dbTool = new DatabaseTool();
	
			// perform sanitize
			$startdate = $dbTool->SanitizeInput($startdate);
			$enddate = $dbTool->SanitizeInput($enddate);
			$organizations = $dbTool->SanitizeInput($organizations);
			$searchstring = $dbTool->SanitizeInput($searchstring);


			//
			// TODO: check to see if inputs are of expected data type and format
			//

			dprint("startdate: " . $startdate);
			dprint("enddate: " . $enddate);
			dprint("organizations: " . $organizations);
			dprint("searchstring: " . $searchstring);
			
			
			// define our return array
			$retVal = array();
			
			//$docTool = new DocumentTool();
			//$documents = array();

			// get all of the documents that are associated with the organization(s)
			if( $organizations == "" || $organizations == null )
			{
			
				// there are more than one key word, we will be performing multiple searches	
		
				// see if there is more than one word
				$pos = strpos($searchstring, " ");
				if( $pos === false )
				{
					dprint("generating query based on no organization, single keyword search");
					
					$query = 'select * from documents, wordfrequency where wordfrequency.word="' . $searchstring . '" AND wordfrequency.documentid=documents.documentid';
					
					dprint("running: '" . $query . "'");
					
					// pull from DB
					$result = mysql_db_query(MYSQL_DATABASE, $query)
						or die("Failed Query of " . $query);  			// TODO: something more elegant than this
		
					
				
					$orgTool = new OrganizationsTool();
		
					while($r = mysql_fetch_assoc($result)) {
			
						// create a contaniner to place our result in
						$searchResult = new SearchResult();
			
						// populate our result object with the returned DB info
						$searchResult->suborgname = $orgTool->SubOrgNameFromID($r['suborganizationid']);
						$searchResult->orgname = $orgTool->OrgNameFromSubOrgID($r['suborganizationid']);
						$searchResult->sourceurl = $r['sourceurl'];
						$searchResult->date = $r['date'];
						$searchResult->name = $r['name'];
						$searchResult->word = $r['word'];
						$searchResult->frequency = $r['frequency'];
						
						// add the result to the list of results
						$retVal[] = $searchResult;
					}
					
					
					
				}
				else
				{
					dprint("generating query based on no organization, multiple keyword search");
				
					$keywords = explode(" ",$searchstring);
				
					foreach($keywords as $keyword)
					{
						
						$query = 'select * from documents, wordfrequency where wordfrequency.word="' . $keyword . '" AND wordfrequency.documentid=documents.documentid';
						
						dprint("running: '" . $query . "'");
						
						// pull from DB
						$result = mysql_db_query(MYSQL_DATABASE, $query)
							or die("Failed Query of " . $query);  			// TODO: something more elegant than this
						
						
						
						$orgTool = new OrganizationsTool();

						while($r = mysql_fetch_assoc($result)) {
			
							// create a contaniner to place our result in
							$searchResult = new SearchResult();
				
							// populate our result object with the returned DB info
							$searchResult->suborgname = $orgTool->SubOrgNameFromID($r['suborganizationid']);
							$searchResult->orgname = $orgTool->OrgNameFromSubOrgID($r['suborganizationid']);
							$searchResult->sourceurl = $r['sourceurl'];
							$searchResult->date = $r['date'];
							$searchResult->name = $r['name'];
							$searchResult->word = $r['word'];
							$searchResult->frequency = $r['frequency'];
							
							// add the result to the list of results
							$retVal[] = $searchResult;
						}
			
						
			
					}
					
				}
			
			}
			else
			{
				
				dprint("generating query based on multiple orgs");
				
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
					
						$query = 'select * from documents, wordfrequency where wordfrequency.word="' . $searchstring . '" AND wordfrequency.documentid=documents.documentid AND suborganizationid=' . $id;
							
						dprint("running: '" . $query . "'");
							
						// pull from DB
						$result = mysql_db_query(MYSQL_DATABASE, $query)
							or die("Failed Query of " . $query);  			// TODO: something more elegant than this
							
						
							
						$orgTool = new OrganizationsTool();
							
						while($r = mysql_fetch_assoc($result)) {
			
							// create a contaniner to place our result in
							$searchResult = new SearchResult();
				
							// populate our result object with the returned DB info
							$searchResult->suborgname = $orgTool->SubOrgNameFromID($r['suborganizationid']);
							$searchResult->orgname = $orgTool->OrgNameFromSubOrgID($r['suborganizationid']);
							$searchResult->sourceurl = $r['sourceurl'];
							$searchResult->date = $r['date'];
							$searchResult->name = $r['name'];
							$searchResult->word = $r['word'];
							$searchResult->frequency = $r['frequency'];
								
							// add the result to the list of results
							$retVal[] = $searchResult;
						}
						
						
					
					}
					else
					{
					
						// get the list of keywordss
						$keywords = explode(" ",$searchstring);
		
						foreach($keywords as $keyword)
						{
							
							$query = 'select * from documents, wordfrequency where wordfrequency.word="' . $keyword . '" AND wordfrequency.documentid=documents.documentid AND suborganizationid=' . $id;
							
							dprint("running: '" . $query . "'");
							
							// pull from DB
							$result = mysql_db_query(MYSQL_DATABASE, $query)
								or die("Failed Query of " . $query);  			// TODO: something more elegant than this
							
							
							
							$orgTool = new OrganizationsTool();

							while($r = mysql_fetch_assoc($result)) {
			
								// create a contaniner to place our result in
								$searchResult = new SearchResult();
					
								// populate our result object with the returned DB info
								$searchResult->suborgname = $orgTool->SubOrgNameFromID($r['suborganizationid']);
								$searchResult->orgname = $orgTool->OrgNameFromSubOrgID($r['suborganizationid']);
								$searchResult->sourceurl = $r['sourceurl'];
								$searchResult->date = $r['date'];
								$searchResult->name = $r['name'];
								$searchResult->word = $r['word'];
								$searchResult->frequency = $r['frequency'];
								
								// add the result to the list of results
								$retVal[] = $searchResult;
							}
				
							
				
						}
						
					}
					
				}
				
				
				
			}
			
			// return the array of found minutes
			return $retVal;
		}
	}

?>