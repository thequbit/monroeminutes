<?

	require_once("debug.php");
	require_once("sqlcredentials.php");
	require_once("minutes.class.php");
	require_once("OrganizationsTool.class.php");

	// we need to deturmine what kind of search the user is doing.
	// there are two main types of searches:
	//
	//	1. Search for minutes based on Organization
	//		This will return all documents associated with the selected organizations
	//
	//	2. Search for minutes based on an Address
	//		This will return all docuemtns associated with the given address (based on taxlot id code)
	//
	//
	//	The two functions are SearchWithoutAddress() and SearchWithAddress respecively
	//
	//

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
			
			// define our return array
			$response = null;
			
			dprint("Trying to connect to database ...");
			
			// connect to the mysql database server.  Constants taken from sqlcredentials.php
			$chandle = mysql_connect($mysql_host, $mysql_user, $mysql_pass)
				or die("Connection Failure to Database");				// TODO: something more elegant than this

			mysql_select_db($mysql_database, $chandle)
				or die ($mysql_database . " Database not found. " . $mysql_user);	// TODO: something more elegant than this

			dprint("Connected to DB.");

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

			//
			// SQL query
			//
			
			$query = "SELECT * FROM minutes WHERE ";
			
			if( $useorganizations == true )
			{
			
				// TODO: support multiple organizations
			
			}
			
			if( $usesearchstring == true )
			{
			
				$query = $query + "minutestext LIKE '%" . $searchstring . "%'";
			
			}

			// pull from DB
			$result = mysql_db_query($mysql_database, $query)
				or die("Failed Query of " . $query);  			// TODO: something more elegant than this

			// generate an array to put our responses in
			$response = array();

			/*
			
			// from Minutes.class.php ...
			
			class Minutes
			{
				public $organizationname
				public $suborganizationname
				public $date
				public $minutesurl
				public $minutetext
			}
			*/

			while($r = mysql_fetch_assoc($result)) {
			
				// create our object to be populated with our DB result
				$minutes = new Minutes();
			
				// create an instance of our tool that will allow us to 
				$orgtool = new OrganizationsTool();
				
				// using the sub organizations id pull it's name from the database
				$subOrgName = $orgtool->SubOrgNameFromID($r['suborganizationid']);
				$orgName = $orgtool->OrgNameFromID($r['suborganizationid']);
			
				// populate the Minutes object with the returned data from the DB 
				$minutes->$suborganizationname = $subOrgName;
				$minutes->organizationname = $orgName;
				$minutes->date = $r['date'];
				$minutes->minutesurl = $r['minutesurl'];
				$minutes->$minutetext = $r['minutestext'];
			
				// debug output
				dprint("Organization Name = '" . $minutes->$suborganizationname . "'");
				dprint("Suborganization Name = '" . $minutes->organizationname . "'");
				dprint("Date = '" . $minutes->date . "'");
				dprint("Minutes URL = '" . $minutes->minutesurl . "'");
				dprint("Minutes Text = '" . $minutes->$minutetext . "'");
			
				// add this object to the array of Minutes
				$response[] = $minutes;
			}

			// return the array of found minutes
			return $response;
		}
	}

?>