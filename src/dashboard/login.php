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
</head>

<body>

	<div id="loginbox" class="loginbox">

		<h4>Please Login</h4>
	
		<form action="validatelogin.php">

			Username:</br>
				<input type="text" name="username" size="25"><br>

			Password:</br>
				<input type="password" name="password" size="25"><br>
		
			<input type="submit">
		
		</form>
	
	</div>

</body>
</html>