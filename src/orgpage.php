<?php

	// start our session so we have access to our $_SESSION variables
	session_start();

?>

<html>
<head>
	<title>Monroe Minutes - <?php echo $_POST['suborgname']; ?></title>
	
	<link href="./css/main.css" rel="stylesheet" type="text/css">
	<link href="./css/orgpage.css" rel="stylesheet" type="text/css">
	
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="./js/orgpage.js"></script>
	
	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-34576505-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
	
	<script type="text/javascript">
	
		function initPage()
		{
			// do init stuff here
		}
	
	</script>
	
</head>
<body onload="javascript:initPage()">

	<div id="sitetop" class="sitetop">
	
		<div id="topwrapper" class="topwrapper">

			<div id="navbar" class="navbar">

				<div id="navlinks" class="navlinks">
						
					<?php
					
						// test to see if a user is logged in, if so display the logout option
						if( isset($_SESSION['username']) == false )
						{
							echo '<div id="loginlink" class="navlink">';
							echo '<a href="dashboard/login.php">Login</a>';
							echo '</div>';
						}
						else
						{
							echo '<div id="login" class="navlink">';
							echo '<a href="dashboard/dashboard.php">Dashboard</a>';
							echo '</div>';
						}
						
					?>
					
					<div id="backtosearch" class="navlink">
						<a href="index.php">Back To Search</a>
					</div>
				
				</div>

			</div>

			<div id="title" class="title">
			
				<img id="titleimg" class="titleimg" src="/media/title.png"></img>
			
			</div>

			<div id="contentwrapper" class="contentwrapper">

				<div id="orglist" class="orglist">
					
					<?
					
						//require_once("./tools/debug.php");
						require_once("./tools/OrganizationsTool.class.php");
					
						//dprint("creating org tool");
					
						// create an instance of our tool so we can pull the org and sub org information out of it
						$orgtool = new OrganizationsTool();
					
						dprint("getting org list");
					
						// get the list of all the organizations
						$orgnames = $orgtool->GetAllOrganizationNames();
					
						// iterate through the list of organizations and pull the sub orgs
						foreach($orgnames as $orgname)
						{
							// print the org name as our heading
							echo '<div id="' . $orgname . 'div" class="org">';
							echo '<b>' . $orgname . '</b><br>';
							
							// get all of the suborgs within this org
							$suborgnames = $orgtool->GetAllSubOrganizations($orgname);
							
							// iterate through the list of sub orgs for the org and display them
							foreach($suborgnames as $suborgname)
							{
								echo '|- <a id="' . $suborgname->name . '" class="suborglink" onclick="displaySubOrg(\'' . $suborgname->name . '\');">' . $suborgname->name . '</a>';
								
								// test to see if the suborg has been indexed (dbpopulated == true)
								$isIndexed = $orgtool->IsIndexed($suborgname->name);
								
								//echo "Is Indexed? = " . $isIndexed;
								
								if( $isIndexed == false )
								{
									echo '<font size="1" color="red"> (Not Indexed)</font>';
								}
								
								echo '<br>';
							}
							
							echo '</div>';
							
						}
						
						
					?>
				
					
				</div>
				
				<div id="orginfo" class="orginfo">
				
					Click on one of the organizations on the left to see additional information about it.
				
				</div>

			</div>

		</div>
		
	</div>

</body>
</html>