<?php

	// start our session so we have access to our $_SESSION variables
	session_start();
	if( isset($_SESSION['username']) == false )
	{
		header("Location: login.php?redirecturl=" . urlencode("login.php"));
	}

?>

<?php
	
	require_once("../tools/LoginTool.class.php");
	require_once("../tools/debug.php");
	
	// pull old and new password from the list
	$oldpassword = $_POST['oldpassword'];
	$newpassword = $_POST['newpassword'];
	$newpasswordagain = $_POST['newpasswordagain'];
	$username = $_SESSION['username'];

	dprint("Old Password: " . $oldpassword);
	dprint("New Password: " . $newpassword);
	dprint("New Password Again: " . $newpasswordagain);
	dprint("Username: " . $username);

	// create a login tool to help us change the users password
	$loginTool = new LoginTool();
	
	// change the password
	$success = $loginTool->ChangePassword($username, $oldpassword, $newpassword, $newpasswordagain);

	// print html header data
	echo '<html>';
	echo '<head>';
	echo '<meta http-equiv="refresh" content="2; URL=dashboard.php">';
	echo '</head>';
	echo '<body>';

	// check for success
	if( $success == true )
	{
		echo "<br><br><br><center>Password change <b>SUCCESSFUL</b>.  Redirecting to the dashboard in 2 seconds.</center>";
	}
	else
	{
		echo "<br><br><br><center>Password change <b>FAILED</b>.  Redirecting to the dashboard in 2 seconds.</center>";
	}
	
	// print html footer
	echo '</body>';
	echo '</html>';
?>