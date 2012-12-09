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
			// connect to DB
			$dbtool = new DatabaseTool();
			$chandle = $dbtool->Connect();
			
			// get the number of rows in the searches table
			$query = 'select count(*) from searches';
			$result = $dbtool->Query($query,$chandle);		
			
			// decode the result
			$r = mysql_fetch_assoc($result);
			$totalSearches = $r['count(*)'];
			
			// return the values
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
			
			// connect to DB
			$dbtool = new DatabaseTool();
			$chandle = $dbtool->Connect();
			
			// add new search to db
			$query = 'insert into searches (searchstring,date,querytime) values("' . $keyword . '", "' . $datetime . '", "' . $querytime . '")';
			$result = $dbtool->Query($query,$chandle);			
		}
	
		function SearchWithAddress($startdate, $enddate, $organizations, $searchstring, $address)
		{
			// parse address
			
			// get suborgs associated with address
			
			// call performSearch with array of suborgs
		}
	
		
	
		function SearchWithoutAddress($startdate, $enddate, $organizations, $searchstring)
		{
			dprint("Performing a search without using an address");
		
			// connect to DB
			$dbtool = new DatabaseTool();
			$chandle = $dbtool->Connect();
		
			// create a orgtool for use to decode the orgid from the name
			$orgTool = new OrganizationsTool();
		
			// create array to pass to the PerformSearch function
			$suborgids = array();
		
			dprint("running through all of the organizations submited by the user");
		
			if( count($organizations) == 0)
			{
				dprint("no orgs entered by user");
			}
			else
			{
		
				dprint("creating list of suborgs based on input orgs");
		
				// itterate through all of the organizations and get all sub orgs associated with it.
				foreach($organizations as $org)
				{
				
					// get the orgid from it's name that is passed in
					$orgid = $orgTool->OrgIdFromName($org);
				
					// gen query to get all suborgs from org
					$query = 'select * from suborganizations where organizationid=' . $orgid;
					$result = $dbtool->Query($query,$chandle);
				
					// itterate through organizations and get all sub orgs
					while($r = mysql_fetch_assoc($result))
					{
						dprint("Adding suborgid " . $r['suborganizationid'] . " to array");
					
						// add id to the array
						$suborgids[] = $r['suborganizationid'];
					}
					
				}
			
			}
			
			dprint("Number of suborgs found: " . count($suborgids));
			
			dprint("calling PerformSearch('" . $startdate . "', '" . $enddate . "', '" . $suborgids . "', '" . $searchstring . "')");
			
			// call performSearch with array of suborgs
			$results = $this->PerformSearch($startdate, $enddate, $suborgids, $searchstring);
			
			// return the results from the search
			return $results;
			
		}
	
		function GetResultsFromQuery($results)
		{
			dprint("processing results");
		
			$retVal = array();
		
			$orgTool = new OrganizationsTool();

			while($r = mysql_fetch_assoc($results)) {

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
				
				dprint("Sub Org Name: " . $searchResult->suborgname );
				dprint("Org Name: " . $searchResult->orgname );
				
				dprint("Adding doc to result array");
				
				// add the result to the list of results
				$retVal[] = $searchResult;
			}
			
			dprint("done processing results");
			
			return $retVal;
		}
	
		function DecodeDate($startdate, $enddate)
		{
		
			if( $startdate == "" && $enddate == "" )
			{
				$retVal = "";
			}
			elseif( $startdate != "" && $enddate == "" )
			{
				$retVal = " AND date >= '" . $startdate ."'";
			}
			elseif( $startdate != "" && $enddate != "" )
			{
				$retVal = " AND date >= '" . $startdate ."' AND date <= '" . $enddate . "'";
			}
			
			return $retVal;
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
		function PerformSearch($startdate, $enddate, $suborganizationids, $searchstring)
		{
		
			// connect to DB
			$dbtool = new DatabaseTool();
			$chandle = $dbtool->Connect();

			// use our database tool to sanitize inputs
			$dbTool = new DatabaseTool();
	
			$returnArray = array();
	
			// perform sanitize
			$startdate = $dbTool->SanitizeInput($startdate);
			$enddate = $dbTool->SanitizeInput($enddate);
			$searchstring = $dbTool->SanitizeInput($searchstring);

			// TODO: itterate through all suborgs and sanitize them
			//$suborganizations = $dbTool->SanitizeInput($suborganizations);

			//
			// SELECT * FROM searches  WHERE date >= '2012-01-01' and date <= '2013-01-01';
			//


			//
			// TODO: check to see if inputs are of expected data type and format
			//

			dprint("startdate: " . $startdate);
			dprint("enddate: " . $enddate);
			dprint("organizations: " . $organizations);
			dprint("searchstring: " . $searchstring);

			// test to see if there are any suborg id's passed in with the function call.  If there are none,
			// then we will process the search query as 
			if( $suborganizationids == null || count($suborganizationids) == 0)
			{
			
				// see if there is a space in the string, if there is then 
				$pos = strpos($searchstring, " ");
				
				// single keyword search
				if( $pos === false )
				{
					dprint("generating query based on no organization, single keyword search, but no suborg id's");
					
					// get all docs that have the keyword in it
					$datepart = $this->DecodeDate($startdate, $enddate);				
					$query = 'select * from documents, wordfrequency where wordfrequency.word="' . $searchstring . '" AND wordfrequency.documentid=documents.documentid' . $datepart;
					$result = $dbtool->Query($query,$chandle);
		
					$rowcount = mysql_num_rows($result);
					dprint("Parsing results for " . $rowcount . " rows");
						
					// parse output from query and add to return array
					$results=$this->GetResultsFromQuery($result);
					foreach($results as $r)
					{
						$returnArray[] = $r;
					}
	
				}
				// multiple keyword search
				else
				{
					dprint("generating querys based on no organization, multiple keyword search, but no suborg id's");
				
					// break the string apart into multiple keywords space-delimited
					$keywords = explode(" ",$searchstring);
				
					foreach($keywords as $keyword)
					{
						
						// perform database query
						$datepart = $this->DecodeDate($startdate, $enddate);				
						$query = 'select * from documents, wordfrequency where wordfrequency.word="' . $keyword . '" AND wordfrequency.documentid=documents.documentid' . $datepart;
						$result = $dbtool->Query($query,$chandle);						
			
						$rowcount = mysql_num_rows($result);
						dprint("Parsing results for " . $rowcount . " rows");
						
						// parse output from query and add to return array
						$results=$this->GetResultsFromQuery($result);
						foreach($results as $r)
						{
							$returnArray[] = $r;
						}
					}
					
				}
			
			}
			// need to itterate through all sub-org id's
			else
			{
				
				dprint("found " . count($suborganizationids) . " suborgid's");
				
				// get the id of the suborg
				$orgTool = new OrganizationsTool();
				
				// itterate through the list of suborg id's
				foreach($suborganizationids as $suborgid)
				{
					
					// see if there is more than one word
					$pos = strpos($searchstring, " ");
					
					// single keyword
					if( $pos === false )
					{
					
						dprint("Found single keyword, performing query");
						
						// single keyword	
						$datepart = $this->DecodeDate($startdate, $enddate);				
						$query = 'select * from documents, wordfrequency where wordfrequency.word="' . $searchstring . '" AND wordfrequency.documentid=documents.documentid AND suborganizationid=' . $suborgid . $datepart;
						$result = $dbtool->Query($query,$chandle);
						
						$rowcount = mysql_num_rows($result);
						dprint("Parsing results for " . $rowcount . " rows");
						
						// parse output from query and add to return array
						$results=$this->GetResultsFromQuery($result);
						foreach($results as $r)
						{
							$returnArray[] = $r;
						}
						
					}
					// multiple keywords
					else
					{
					
						dprint("Found multiple keywrods, running against all");
					
						// get the list of keywordss
						$keywords = explode(" ",$searchstring);
						
						// itterate through list of keywords that are space-delimited
						foreach($keywords as $keyword)
						{
							
							// query database with keyword and suborg id
							
							$datepart = $this->DecodeDate($startdate, $enddate);
							$query = 'select * from documents, wordfrequency where wordfrequency.word="' . $keyword . '" AND wordfrequency.documentid=documents.documentid AND suborganizationid=' . $id . $datepart;
							$result = $dbtool->Query($query,$chandle);
				
							$rowcount = mysql_num_rows($result);
							dprint("Parsing results for " . $rowcount . " rows");
							
							// parse output from query and add to return array
							$results=$this->GetResultsFromQuery($result);
							foreach($results as $r)
							{
								$returnArray[] = $r;
							}
				
						}
						
					}
					
				}
			}
			
			// return the array of found minutes
			return $returnArray;
		}
	}

?>