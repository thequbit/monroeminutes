<?

	require_once("debug.php");

	class AddressTool
	{
	
		function ParseAddressString($inputString)
		{
			
		}
	
		// this function will pull data from the monroe county database for SWIS codes
		function SWISFromAddress($streetNumber, $streetName, $extension, $zip)
		{
			
			// create our variables that will go into creating the data to be sent to their API
			$swis = "SWIS Code";
			$parcelID = "undefined";
			$tax = "undefined";
			$num = $streetNumber;
			$street = $streetName;
			$ext =$extension;
			$zip = $zip;
			
			// check to make sure our inputs to their API are what we *think* they want
			if ($num == null || $num == "")
			{
				$num = "Number";
			}
			if ($street == null || $street == "")
			{
				$street = "Street Name";
			}
			if ($zip == null || $zip == "")
			{
				$zip = "Zip";
			}
			if ($ext == null || $ext == "")
			{
				$ext = "undefined";
			}
			
			// create payload to be sent to API
			$url= "http://www.monroecounty.gov/swf/php/property/prop_search_2.php";
			$info_base = "SWIS	parcelID	tax	num	street	zip	ext;";
			$info_data = "$swis\t$parcelID\t$tax\t$num\t$street\t$zip\t$ext";
			$info = $info_base . $info_data;
			$siteType = "R";
			$searchtype = "taxes";
			$action = "prop_search";
			
			// Format POST Data in to format that can be sent. 
			$prep = array(	'info' => $info,
				'siteType' => $siteType,
				'searchtype' => $searchtype,
				'action' => $action);
			$data = http_build_query($prep);
			
			// Form up the POST Request 
			$opts = array('http' =>
				array(
					'method'  => 'POST',
					'header'  => 'Content-type: application/x-www-form-urlencoded',
					'content' => $data
				)
			);
			
			// Make Post Request and Save Data to  Resutl
			$context  = stream_context_create($opts);
			$result = file_get_contents($url, false, $context);
			
			// parse the results from the API
			$mainBody = explode(';',$result);
			$DataValues = explode("\t",$mainBody[1]);
			
			// debug
			//dprint("SCHOOL DIST = $DataValues[6]");
			//dprint("SWIS =$DataValues[9]");
			
			// create our return array with the returned data of interest
			$retVal = array();
			$retVal[] = $DataValues[6];
			$retVal[] = $DataValues[9];
			
			return $retVal;
			
		}
	
	}

?>