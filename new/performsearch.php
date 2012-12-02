<?

	require_once("debug.php");
	require_once("Minutes.class.php");

	function performSearch($startdate, $enddate, $address, $organization, $searchstring)
	{
		dprint("Decoding Search Criteria ...");
		dprint("Date Range: '" . $startdate . " - " . $enddate . "'");
		dprint("Address: '" . $address . "'");
		dprint("Organization: '" . $address . "'");
		dprint("Search String: '" . $searchstring . "'");
	}

?>