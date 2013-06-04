<?
	
	$docid = $_GET['docid'];
	$keyword = $_GET['keyword'];
	
	// default values
	$error = "Invalid document ID";
	$preview = "";
	
	if( is_numeric($docid) == True )
	{
		require_once("../tools/DocumenttextsManager.class.php");
		
		$dtmgr = new DocumenttextsManager();
		
		$doctext = $dtmgr->getbydocid($docid);
		
		if( $doctext != "" )
		{
			$error = "None";
			
			if( strlen($doctext) > 512 )
			{
				//if( strpos($doctext,$keyword) < )
			}
			else
			{
				$preview = $doctext;
			}
		}
	}

	
	echo '{""error" : "' . $error . '", "previewtext" : "' . $preview . '"}';

?>