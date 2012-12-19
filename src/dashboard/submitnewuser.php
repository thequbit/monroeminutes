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
		require_once("../tools/LoginTool.class.php");

		// decode our username and password from our POST event from our login form
		$displayname = $_POST['displayname'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		if( $_POST['canlogin'] == "on" ) {
			$canlogin = 1;
		}
		if( $_POST['enabled'] == "on" ) {
			$isadmin = 1;
		}
		if( $_POST['isadmin'] == "on" ) {
			$enabled = 1;
		}
		
		dprint("Display Name: " . $displayname);
		dprint("Username: " . $username);
		dprint("Password: " . $password);
		dprint("Can Login?: " . $canlogin);
		dprint("Is Admin?: " . $isadmin);
		dprint("Enabled?: " . $enabled);
		
		// create a login tool object to help us add the user to the db
		$loginTool = new LoginTool();

		dprint("Adding user to DB ...");

		// add the user to the database
		$success = $loginTool->CreateUser($displayName, $username, $password, $canlogin, $isadmin, $enabled);
		
		dprint("Success: " . $success);

	?>
	
</body>
</html>