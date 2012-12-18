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
			
				echo "<br><br>Welcome " . $_SESSION['username'] . "</br>";
	
				// all users
				echo '<a href="changepassword.php">Change Password</a></br>';
				echo '<a href="logout.php">Logout</a></br>';
				
				// admin-only users
				if( isset($_SESSION['isadmin']) && $_SESSION['isadmin'] == true )
				{
					echo "<br><br><br>-- Admin --<br>";
					echo '<a href="addentity.php">Add Organization/Suborganization/Category</a><br>';
					echo '<a href="status.php">Status</a><br>';
					echo '<a href="createuser.php">Create User</a?<br>';
				}

			?>
		
		</div>
		
	</div>

</body>
</html>
