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
	
	
		// This function pulls the total number of rows in the search table within
		// the database.  This number represents the total number of searches performed
		// by the search API.
		//
		// takes in:
		//		- none -
		//
		// returns
		// 		$totalSearches - total number of searches in the database.
		//
		function GetTotalSearchCount()
		{
			
			dprint("Trying to connect to database ...");
			
			// connect to the mysql database server.  Constants taken from sqlcredentials.php
			$chandle = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS)
				or die("Connection Failure to Database");				// TODO: something more elegant than this

			mysql_select_db(MYSQL_DATABASE, $chandle)
				or die (MYSQL_DATABASE . " Database not found. " . MYSQL_USER);	// TODO: something more elegant than this

			dprint("Connected to DB.");
			
			// add new search to db
			$query = 'select count(*) from searches';
					
			dprint("running: '" . $query . "'");
			
			$result = mysql_db_query(MYSQL_DATABASE, $query)
				or die("Failed Query of " . $query);  			// TODO: something more elegant than this
				
			$r = mysql_fetch_assoc($result);
			$totalSearches = $r['count(*)'];
			
			return $totalSearches;
			
		}
	
		// This function adds the search text and the date/time that the search was executed
		// by the API into the database.  This is used to see how many searches are being 
		// performed, and what keywords are being used.
		// 
		// takes in:
		// 		$keyword - the string the API used to execute the search
		// 		$date - the date and time of the search
		//
		// returns
		//		- nonthing - 
		//
		function AddSearchToDatabase($keyword, $datetime, $querytime)
		{
			
			dprint("Trying to connect to database ...");
			
			// connect to the mysql database server.  Constants taken from sqlcredentials.php
			$chandle = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS)
				or die("Connection Failure to Database");				// TODO: something more elegant than this

			mysql_select_db(MYSQL_DATABASE, $chandle)
				or die (MYSQL_DATABASE . " Database not found. " . MYSQL_USER);	// TODO: something more elegant than this

			dprint("Connected to DB.");
			
			// add new search to db
			$query = 'insert into searches (searchstring,date,querytime) values("' . $keyword . '", "' . $datetime . '", ' . $querytime . ')';
					
			dprint("running: '" . $query . "'");
			
			$result = mysql_db_query(MYSQL_DATABASE, $query)
				or die("Failed Query of " . $query);  			// TODO: something more elegant than this
			
		}
	
		// This function performs a search query based on a date range, an array of organizations to
		// look in, and a search string which is a set of keywords seperated by commas.
		//
		//	takes in:
		// 		$startdate - date to start searching for documents
		// 		$enddate - date to stop searching for docuemtns
		// 		$organizations - array of organizations to search with
		// 		$searchstring - the text entered by the user to search for within the minutes text
		//
		// returns:
		// 		$retVal - This returns an array of documents pulled from the database
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