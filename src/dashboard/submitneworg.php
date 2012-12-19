<?php

	// start our session so we have access to our $_SESSION variables
	session_start();
	if( isset($_SESSION['username']) == false )
	{
		header("Location: login.php?redirecturl=" . urlencode("dashboard/dashboard.php"));
	}
	
?>

<html>
<head>
	<meta http-equiv="refresh" content="2; URL=dashboard.php">
</head>
<body>

	<?php

		require_once("../tools/debug.php");
		require_once("../tools/OrganizationsTool.class.php");

		// decode our username and password from our POST event from our login form
		$orgname = $_POST['orgname'];
		
		dprint("Display Name: " . $orgname);
		
		// create a login tool object to help us add the user to the db
		$orgtool = new OrganizationsTool();

		dprint("Adding user to DB ...");

		// add the user to the database
		$success = $orgtool->CreateOrganization($orgname);
		
		dprint("Success: " . $success);
		
		// check for success
		if( $success == true )
		{
			echo "<br><br><br><center>Organization submittion <b>SUCCESSFUL</b>.  Redirecting to the dashboard in 2 seconds.</center>";
		}
		else
		{
			echo "<br><br><br><center>Organization submittion <b>FAILED</b>.  Redirecting to the dashboard in 2 seconds.</center>";
		}

	?>

</body>
</html>
