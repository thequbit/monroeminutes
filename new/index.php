<html>
<head>
<title>Monroe Minutes</title>
</head>
<body>

	<?

	require_once("OrganizationsTool.class.php");

	// start of select multiple
	echo '<select multiple="multiple">';
	
	// use our tool to get all of the organization names from the database
	$orgtool = new OrganizationsTool();	
	$orgnames = $orgtool->GetAllOrganizationNames();
	
	// create the select multiple object based on DB into
	foreach($orgnames as $name)
	{
		echo '<option value="' . $name . '">' . $name . '</option>'
	}
	
	// end of select multiple
	echo '</select>'

	?>

</body>
</html>

