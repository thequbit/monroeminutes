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
		$suborgname = $_POST['suborgname'];
		$orgname = $_POST['orgname'];
		$weburl = $_POST['weburl'];
		$docurl = $_POST['docurl'];
		$scriptname = $_POST['scriptname'];
		
		echo "Suborg Name: " . $suborgname . "<br>";
		echo "Org Name: " . $orgname . "<br>";
		echo "Web URL: " . $weburl . "<br>";
		echo "Doc URL: " . $docurl . "<br>";
		echo "Script Name: " . $scriptname . "<br>";
		
		// create a login tool object to help us add the user to the db
		$orgtool = new OrganizationsTool();

		dprint("Adding user to DB ...");

		// add the user to the database
		$success = $orgtool->CreateSubOrganization($suborgname,$orgname,$weburl,$docurl,$scriptname);
		
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
