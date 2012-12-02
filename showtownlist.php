<html>
<head>
<title>Monroe Minutes</title>
</head>
<body>

<?

	require_once('townlisttool.class.php');
	
	$townlist = new TownListTool();
	
	$towns = $townlist->getList();
	
	dprint("town count = " . count ($towns));
	
	foreach($towns as $town)
	{
		if( $town->url == null)
		{
			echo "<b>" . $town->name . "</b></br>";
		}
		else
		{
			echo "<a href=" . $town->url ."><b>" . $town->name . "</b></a></br>";
		}
	}

?>

</body>
</html>