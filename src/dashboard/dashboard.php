<?php

	// start our session so we have access to our $_SESSION variables
	session_start();

	if( isset($_SESSION['username']) == false )
	{
		header("Location: login.php");
	}
	else
	{

		echo "Welcome " . $_SESSION['username'] . "</br>";

		echo '<a href="logout.php">Logout</a></br>';
		
		if( isset($_SESSION['isadmin']) && $_SESSION['isadmin'] == true )
		{
			echo '<a href="admin.php">Admin Page</a></br>';
		}
		
	}

?>