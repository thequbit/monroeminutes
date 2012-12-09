<?php

	// start our session so we have access to our $_SESSION variables
	session_start();
	
	if( isset($_SESSION['username']) == false )
	{
		header("Location: login.php?redirecturl=" . urlencode("dashboard/request.php"));
	}

?>

<?php

	echo "You have made it to the request page!";

?>