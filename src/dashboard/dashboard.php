<?php

	// start our session so we have access to our $_SESSION variables
	session_start();
	if( isset($_SESSION['username']) == false )
	{
		header("Location: login.php");
	}
	
?>

<html>
<head>
	<title>Monroe Minutes Dashboard</title>

	<link href="../css/main.css" rel="stylesheet" type="text/css">
	<link href="../css/dashboard.css" rel="stylesheet" type="text/css">
	
</head>
<body>

	<div id="sitetop" class="sitetop">

		<div id="topwrapper" class="topwrapper">

			<?php
			
				echo "Welcome " . $_SESSION['username'] . "</br>";

				echo '<a href="logout.php">Logout</a></br>';
				
				if( isset($_SESSION['isadmin']) && $_SESSION['isadmin'] == true )
				{
					echo '<a href="admin.php">Admin Page</a></br>';
				}

			?>
		
		</div>
		
	</div>

</body>
</html>
