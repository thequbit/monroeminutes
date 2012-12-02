<?php

	require_once("debug.php");
	require_once("searchtool.class.php");

	// this is the search api file where you can present it with POST data and
	// it will return back a json object with an array of minutes in it

	dprint("Running search and returning json with results ...");

	// get our POST information
	$searchstring = $_GET['keywordsearch'];
	$address = $_GET['address'];
	$startdate = $_GET['startdate'];
	$enddate = $_GET['enddate'];
	$organizations = $_GET['organizations'];
	
	dprint("Search String: '" . $searchstring . "'");
	dprint("Address: '" . $address . "'");
	dprint("Start Date: '" . $startdate . "'");
	dprint("End Date: '" . $enddate . "'");
	dprint("Organizations: '" . $organizations . "'");
	
	// create an instance of our search tool
	$searchTool = new SearchTool();
	
	// perform our search based on the two flows (address or no address)
	if( $address == "" )
	{
		dprint("Getting minutes list ...");
	
		// get minutes list
		$results = $searchTool->SearchWithoutAddress($startdate, $enddate, $organizations, $searchstring);
	}
	else
	{
		// TODO: do this.
		$results = null;
	}
	
	if( $results != null)
	{
		// we need to convert our php array of objects into a json object and echo it out as the 
		// contents of the searchapi.php page
		
		dprint("converting to json and printing ...");
		
		// convery array to json
		$json_result = json_encode($results);
		
		// print to the page
		echo $json_result;
	}
	else
	{
		echo "nope.";
	}
	
	
	
?>