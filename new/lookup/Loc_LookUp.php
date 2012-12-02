<html>
<head>
<title>Monroe County - Location Lookup</title>
</head>
<body>
<?php

$debug = 1;

if ($debug == 1)
{
	echo "<h3>Monroe County - Location Lookup</h3><br>";
}
// Pull out incoming Data From POST
$swis = $_POST['swis'];
$parcelID = $_POST['parcelID'];
$tax = $_POST['tax'];
$num = $_POST['num'];
$street = $_POST['street'];
$zip = $_POST['zip'];
$ext = $_POST['ext'];

if ($debug == 1)
{
	//Display Data From Post
	echo "--- --- --- --- ---<br>";
	echo "Submited Info: <br>";
	echo "swis Code: $swis<br>";
	echo "parcelID: $parcelID<br>";
	echo "tax: $tax<br>";
	echo "num: $num<br>";
	echo "street: $street<br>";
	echo "zip: $zip<br>";
	echo "ext: $ext<br>";
	echo "--- --- --- --- ---<br>";
}

// Check to input to see waht info we have 
// clean up for unknow data
if ($swis == "")
{
	$swis = "SWIS Code";
}
if ($parcelID == "")
{
	$parcelID = "undefined";
}
if ($tax == "")
{
	$tax = "undefined";
}
if ($num == "")
{
	$num = "Number";
}
if ($street == "")
{
	$street = "Street Name";
}
if ($zip == "")
{
	$zip = "Zip";
}
if ($ext == "")
{
	$ext = "undefined";
}


if ($debug == 1)
{
	//Display Data After Cleanup
	echo "Post Cleanup Info: <br>";
	echo "swis Code: $swis<br>";
	echo "parcelID: $parcelID<br>";
	echo "tax: $tax<br>";
	echo "num: $num<br>";
	echo "street: $street<br>";
	echo "zip: $zip<br>";
	echo "ext: $ext<br>";
	echo "--- --- --- --- ---<br>";
}

// Setup Stings For External Lookup

$url= "http://www.monroecounty.gov/swf/php/property/prop_search_2.php";
$info_base = "SWIS	parcelID	tax	num	street	zip	ext;";
$info_data = "$swis\t$parcelID\t$tax\t$num\t$street\t$zip\t$ext";
$info = $info_base . $info_data;
$siteType = "R";
$searchtype = "taxes";
$action = "prop_search";

if ($debug == 1)
{
	//Display Data Ready For Post To Ext
	echo "Ext Post Data: <br>";
	echo "info: $info<br>";
	echo "siteType: $siteType<br>";
	echo "searchtype: $searchtype<br>";
	echo "action: $action<br>";
	echo "--- --- --- --- ---<br>";
}

$prep = array(	'info' => $info,
				'siteType' => $siteType,
				'searchtype' => $searchtype,
				'action' => $action);
				
// Format POST Data in to format that can be sent. 
$data = http_build_query($prep);


if ($debug == 1)
{
	echo "url = $url<br>";
	echo "data = $data<br>";
}

// Form up the POST Request 
$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $data
    )
);

$context  = stream_context_create($opts);

// Make Post Request and Save Data to  Resutl
$result = file_get_contents($url, false, $context);

if ($debug == 1)
{
	echo "RESP:<br>";
	echo $result;
}

?>
</body>
</html>