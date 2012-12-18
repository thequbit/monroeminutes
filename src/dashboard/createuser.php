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
	<title>Monroe Minutes Dashboard</title>

	<link href="../css/main.css" rel="stylesheet" type="text/css">
	<link href="../css/dashboard.css" rel="stylesheet" type="text/css">
	
</head>
<body>

	<div id="sitetop" class="sitetop">

		<div id="topwrapper" class="topwrapper">
		
			<div id="adduser" class="loginbox">

				<div id="userinform" class="userinform">
					<p><b>Fillout the form below to add a new user</b></p>
				</div>
			
				<form action="submitnewuser.php" method="post">

					<div id="username" class="userinput">
					Display Name:<br>
						<input type="text" name="displayname" style="width=80%"><br>
					</div>

					<div id="username" class="userinput">
					Username:<br>
						<input type="text" name="username" style="width=80%"><br>
					</div>

					<div id="password" class="userinput">
					Password:<br>
						<input type="text" name="password" style="width=80%" value="password123%%%"><br>
					</div>
				
					<div id="canloginbox" class="userinput">
						<input type="checkbox" name="canlogin">Can Login?</input>
					</div>
					
					<div id="isadminbox" class="userinput">
						<input type="checkbox" name="isadmin">Is Admin?</input>
					</div>
					
					<div id="enabledbox" class="userinput">
						<input type="checkbox" name="enabled">Enabled?</input>
					</div>
					
					<div id="submit" class="userinput">
						<input type="submit" text="Add User" id="adduser">
					</div>
				
				</form>
			
			</div>
		
		</div>
		
	</div>
	
</body>
</html>