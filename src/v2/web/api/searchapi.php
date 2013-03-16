<?php

	// decode passed in keyword
	$keyword = $_GET['keyword'];

	if( $keyword == "" )
	{
		// no valid data passed in
		$jsonResults = json_encode( array() );
		
		$errorCode = 1;
		$errorText = "Not a valid keyword.";
	}
	else
	{
		
		require_once("./tools/SearchTool.class.php");
		require_once("./tools/SearchResult.class.php");
		require_once("./tools/TimeTool.class.php");
		
		
		// helper tools
		$searchTool = new SearchTool();
		$time = new TimeTool();

		// perform query
		$time->Mark();
		$searchResults = $searchTool->PerformSearch($keyword);
		$time->Mark();
		
		// generate values to be return in our json object
		$timeTaken = $time->TimeTaken();
		$documentCount = count($searchResults);
		$jsonResults = json_encode($searchResults);
		
		$errorCode = 0;
		$errorText = "Success.";

	}

	echo '{';
	echo   '"errorCode" : ' . $errorCode . ',';
	echo   '"errorText" : "' . $errorText . '",';
	echo   '"apiVersion" : "' . '0.0.1' . '",';
	echo   '"queryTime" : "' . $timeTaken . '",';
	echo   '"documentCount" : "' . $documentCount . '",';
	echo   '"searchResults" : ' . $jsonResults;
	echo '}';

?>