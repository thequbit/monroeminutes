<?
	// stat our session so we have access to our session variables
	session_start();
	
	// check to see if we are already logined in
	if( isset($_SESSION['username']) == true )
	{
	
		// there is already a user logined in, invalidate their login so we can log a new user in
		unset($_SESSION['username']);
		unset($_SESSION['isadmin']);
		
		dprint("user already loggedin, unset username and isadmin session variables.");
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

				<h4>Please Login</h4>
			
				<form action="validatelogin.php">

					<div id="username" class="userinput">
					Username:<br>
						<input type="text" name="username" style="width=80%"><br>
					</div>

					<div id="password" class="userinput">
					Password:<br>
						<input type="password" name="password" style="width=80%"><br>
					</div>
				
					<div id="submit" class="userinput">
						<input type="submit" text="Login" id="login">
					</div>
				
				</form>
			
			</div>
	
		</div>
	
	</div>
		
	
</body>
</html>