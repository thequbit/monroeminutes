<?php

	require_once("../tools/debug.php");
	require_once("../tools/OrganizationsTool.class.php");
	require_once("../tools/Suborganization.class.php");
	
	dprint("Creating org tool");
	
	// create an OrganizationTool to pull information from the database
	$orgTool = new OrganizationsTool();
	
	// get the org name from the post
	$orgname = $_GET['orgname'];
	
	// get the type = org or suborg
	$orgtype = $_GET['orgtype'];
	
	dprint("Org Name: " . $orgname);
	dprint("Org Type: " . $orgtype);
	
	// TODO: test input to ensure correct type
	
	$retorg = new Suborganization();
	
	// use the org tool to pull all the organization names from the db
	$retorg = $orgTool->SubOrgFromName($orgname);

	// encode the org into a json object
	$jsonResult = json_encode($retorg);

	echo $jsonResult;

?>