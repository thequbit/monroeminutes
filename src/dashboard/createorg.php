<?php

	// start our session so we have access to our $_SESSION variables
	session_start();
	if( isset($_SESSION['username']) == false )
	{
		header("Location: login.php?redirecturl=" . urlencode("dashboard.php"));
	}
	
?>

<html>
<head>
	<title>Monroe Minutes - Create Organization</title>

	<link href="../css/main.css" rel="stylesheet" type="text/css">
	<link href="../css/dashboard.css" rel="stylesheet" type="text/css">
	
</head>
<body>

	<div id="sitetop" class="sitetop">

		<div id="topwrapper" class="topwrapper">
		
			<div id="adduser" class="loginbox">

				<div id="userinform" class="userinform">
					<p><b>Enter Info to add new Organization</b></p>
				</div>
			
				<form action="submitneworg.php" method="post">

					<div id="orgname" class="userinput">
					Organization Name:<br>
						<input type="text" name="orgname" style="width=80%"><br>
					</div>
					
					<div id="submit" class="userinput">
						<input type="submit" text="Add User" id="addorg">
					</div>
				
				</form>
			
			</div>
		
		</div>
		
	</div>
	
</body>
</html>