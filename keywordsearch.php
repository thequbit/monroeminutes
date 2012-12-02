<?

	// include our search tool
	include 'keywordsearchtool.class.php';
	
	// create a new instance of our tool to use
	$search = new KeyWordSearchTool();
	
	//$response = $search->keywordSearchWithTown("kodak", null);
	
	// allocate response
	$response = null;
	
	dprint("Keyword = " . $_GET["keyword"]);
	dprint("Town = " . $_GET["town"]);
	
	
	// decode the user input and perform search
	if( empty($_GET["keyword"]))
	{
		// report the error
		$arr = array('Error' => "Keyword Not Present");
		
		// encode as json
		$response = json_encode($arr);
	}
	else
	{
		$keyword = $_GET["keyword"];
		
		// see if we should use the town variable
		if (empty($_GET["town"]))
		{
			// perform the search on all minutes
			$response = $search->keywordSearch($keyword);
	
		}
		else
		{
			$town = $_GET["town"];
		
			// perform search wtih the town name as a qualifier
			$response = $search->keywordSearchWithTown($keyword,$town);
		
		}
		
	}
	
	// echo the json object
	echo "{\"Minutes\": " . $response . "}";

?>