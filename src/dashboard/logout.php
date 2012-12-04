<?php

	require_once("debug.php");

	// stat our session so we have access to our session variables
	session_start();
	
	// unset our session login information
	unset($_SESSION['username']);
	unset($_SESSION['isadmin']);
	
	// destroy the session so a new user can use it.
	session_destroy();
	
	dprint("session destroyed and user logged out.");	

	header("Location: ../index.php");

?>
