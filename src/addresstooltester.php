<?php

	require_once("debug.php");
	require_once("AddressTool.class.php");

	$addressTool = new AddressTool();
	
	// an example on how to use the SWISFromAddress() function of the AddressTool class
	$results = $addressTool->SWISFromAddress("79", "Harrogate", "", "");
	$schoolDistrict = $results[0];
	$swisID = $results[1];

	dprint('School District: "' . $schoolDistrict . '"');
	dprint('SWIS ID: "' . $swisID . '"');

	dprint("done.")

?>