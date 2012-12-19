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
	<title>Monroe Minutes - Create Suborganization</title>

	<link href="../css/main.css" rel="stylesheet" type="text/css">
	<link href="../css/dashboard.css" rel="stylesheet" type="text/css">
	
</head>
<body>

	<div id="sitetop" class="sitetop">

		<div id="topwrapper" class="topwrapper">
		
			<div id="adduser" class="loginbox">

				<div id="userinform" class="userinform">
					<p><b>Enter Info to add new Suborganization</b></p>
				</div>
			
				<form action="submitnewsuborg.php" method="post">

					<div id="suborgname" class="userinput">
					Suborganization Name:<br>
						<input type="text" name="suborgname" style="width=80%"><br>
					</div>
					
					<div id="orgname" class="userinput">
						
						Organization Name:
						<div id="organizationlist" class="userinput">
					
							<?php
							
								require_once("../tools/OrganizationsTool.class.php");

								// start of select multiple
								echo '<select id="organizations" name="orgname">'; // TODO: set this width to a set value
								
								// use our tool to get all of the organization names from the database
								$orgtool = new OrganizationsTool();	
								$orgnames = $orgtool->GetAllOrganizationNames();
								
								// create the select multiple object based on DB into
								foreach($orgnames as $name)
								{
									echo '<option value="' . $name . '">' . $name . '</option>';
								}
								
								// end of select multiple
								echo '</select><br>';

							?>
				
						</div>
					
					</div>
					
					<div id="weburl" class="userinput">
					Website URL:<br>
						<input type="text" name="weburl" style="width=80%"><br>
					</div>
					
					<div id="docurl" class="userinput">
					Documents URL:<br>
						<input type="text" name="docurl" style="width=80%"><br>
					</div>
					
					<!-- TODO: make this a pull down from a list of script names ... -->
					<div id="scriptname" class="userinput">
					Script Name:<br>
						<input type="text" name="scriptname" style="width=80%"><br>
					</div>
					
					<div id="submit" class="userinput">
						<input type="submit" text="Add Suborganization" id="addsuborg">
					</div>
				
				</form>
			
			</div>
		
		</div>
		
	</div>
	
</body>
</html>