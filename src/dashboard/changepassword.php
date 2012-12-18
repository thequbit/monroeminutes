<?php

	// start our session so we have access to our $_SESSION variables
	session_start();
	if( isset($_SESSION['username']) == false )
	{
		header("Location: login.php?redirecturl=" . urlencode("dashboard/changepassword.php"));
	}

?>

<html>
<head>
	<title>Monroe Minutes Admin Login</title>

	<meta name="description" content="Meeting Minute Agrigator and Search Engine for Monroe County, NY">
	<meta name="keywords" content="Monroe,Minutes,MonroeMinutes,Rochester,Meetings">

	<link href="../css/main.css" rel="stylesheet" type="text/css">
	<link href="../css/dashboard.css" rel="stylesheet" type="text/css">

</head>

<body>

	<div id="sitetop" class="sitetop">

		<div id="topwrapper" class="topwrapper">
	
			<div id="loginbox" class="loginbox">

				<div id="userinform" class="userinform">
					<p><b>Please fill out the following</b></p>
				</div>
			
				<form action="submitpasswordchange.php" method="post">

					<div id="oldpassworddiv" class="userinput">
					Old Password:<br>
						<input type="text" name="oldpassword" style="width=80%"><br>
					</div>

					<div id="mewpassworddiv" class="userinput">
					New Password:<br>
						<input type="password" name="newpassword" style="width=80%"><br>
					</div>
				
					<div id="newpasswordagain" class="userinput">
					New Password Again:<br>
						<input type="password" name="newpasswordagain" style="width=80%"><br>
					</div>
				
					<div id="submit" class="submit">
						<input type="submit" text="Change Password" id="changepassword">
					</div>
				
				</form>
			
			</div>
	
		</div>
	
	</div>
		
	
</body>
</html>