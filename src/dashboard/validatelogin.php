<?php

	session_start();
	
	require_once("../tools/LoginTool.class.php");
	require_once("../tools/Permissions.class.php");
	require_once("../tools/debug.php");

	// check to see if we are already logined in
	if( isset($_SESSION['username']) == true )
	{
	
		// there is already a user logined in, invalidate their login so we can log a new user in
		unset($_SESSION['username']);
		unset($_SESSION['isadmin']);
		
		dprint("user already loggedin, unset username and isadmin session variables.");
	}
	
	// we are not loged in, we need to validate the credentials posted to us

	// decode our username and password from our POST event from our login form
	$username = $_POST['username'];
	$password = $_POST['password'];
	$redirecturl = $_POST['redirecturl'];
	
	dprint("username: " . $username);
	dprint("password: " . $password);
	dprint("hash: " . md5($password));
	
	// create an instance of the LoginTool to help with authentication and permissions
	$loginTool = new LoginTool();
	
	// create a permissions object to hold our result
	$permissions = new Permissions();
	
	// get permissionsid of credentials
	//$passwordhash = md5($password);
	$permissionsid = $loginTool->CheckCredentials($username,$password);
	
	// check credentials and pull permissions
	if( $permissionsid != -1 )
	{
		dprint("permissionsid came back good, grabbing permissions data from DB");
	
		// login was valid, pull permissions			
		$permissions = $loginTool->GetPermissionsByID($permissionsid);
	}
	else
	{
		dprint("permissionsid came back bad.");
	
		// bad login
		$permissions->validlogin = false;
	}
	
	dprint("Can Login: " . $permissions->canlogin);
	$type = gettype($permissions->canlogin);
	dprint("type: " . $type);
	
	dprint("Is Admin: " . $permissions->isadmin);
	$type = gettype($permissions->isadmin);
	dprint("type: " . $type);
	
	dprint("Enabled: " . $permissions->enabled);
	$type = gettype($permissions->enabled);
	dprint("type: " . $type);
	
	// decode permissions tree
	if( $permissions->validlogin == "1" )
	{
		if( $permissions->enabled == "1" )
		{
			if( $permissions->canlogin == "1" )
			{
				// The login is valid, and the user is enabled ... log them in
				
				// register session variables for later use
				$_SESSION['username'] = $username;
				if( $permissions->isadmin == "1" )
				{
					$_SESSION['isadmin'] = true;
				}
				else
				{
					$_SESSION['isadmin'] = false;
				}

				dprint("Username: " . $_SESSION['username']);
				dprint("IsAdmin: " . $_SESSION['isadmin']);
				
				dprint("Login accepted, forward to dashboard page");
				
				//echo "redirecturl: " . $_GET['redirecturl'];
				
				
				
				// check to see where we are supposed to redirect to
				if( $redirecturl == "" )
				{
					// if there is no redirecturl, then go to our index page
					header("Location: ../index.php");
					//echo "index.php";
				}
				else
				{
					// if there is, then redirect there
					header("Location: ../" . urldecode($redirecturl));
					//echo $redirecturl;
				}
			}
			else
			{
				// the user is not permitted to login.
				
				echo "Login has been disabled for your username.  Please contact your site administrator.";
			}
		}
		else
		{
			// the login is valid, but the user is not enabled or can not login
			
			echo "Your username has been disabled.  Please contact your site administrator.";
		}
	}
	else
	{
		// bad login
		
		echo "Your username and/or password was not correct, please try again.";
	}

?>