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
	//Display Data From Post
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

?>
</body>
</html>