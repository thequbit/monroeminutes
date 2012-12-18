<?php

	// this file will return a json of the list of organizations and the
	// suborganizations under each of those organizations

	require_once("../tools/debug.php");
	require_once("../tools/OrganizationsTool.class.php");
	require_once("../tools/Organization.class.php");
	
	// create an OrganizationTool to pull information from the database
	$orgTool = new OrganizationsTool();
	
	$errorcode = "0";
	$errortext = "None";
	
	$results = array();
	
	// use the org tool to pull all the organization names from the db
	$orgNames = $orgTool->GetAllOrganizationNames();
	
	foreach($orgNames as $orgname)
	{
		// create new organization to populate
		$org = new Organization();
		
		// set the name of the org pulled from the db
		$org->name = $orgname;
		
		// use the org tool to pull all the organization names from the db
		$org->suborgs = $orgTool->GetAllSubOrganizations($orgname);
	
		// add to the array of orgs
		$results[] = $org;
	}

	// encode the results into a json object
	$jsonResult = json_encode($results);

	echo '{"error": "' . $errorcode . '","errortext": "' . $errortext . '","results": ' . $jsonResult . '}';
	
	//echo $jsonResult;

?>