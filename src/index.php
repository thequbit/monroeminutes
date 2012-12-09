<?php

	// start our session so we have access to our $_SESSION variables
	session_start();

?>

<html>
<head>
	<title>Monroe Minutes</title>

	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="js/search.js"></script>
	
	<meta name="description" content="Meeting Minute Agrigator and Search Engine for Monroe County, NY">
	<meta name="keywords" content="Monroe,Minutes,MonroeMinutes,Rochester,Meetings">

	<link href="css/main.css" rel="stylesheet" type="text/css">

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

	<script>
	
		function initPage()
		{
		
			// setup searchstring text box to fire the button click event if the user hits enter
			$("#searchstring").keyup(function(event){
				if(event.keyCode == 13){
					//$("#searchbutton").click();
					
					// execute search with inputs
					performSearch()
				}
			});
			
			// setup address text box to fire the button click event if the user hits enter
			$("#address").keyup(function(event){
				if(event.keyCode == 13){
					//$("#searchbutton").click();
					
					// execute search with inputs
					performSearch()
				}
			});
		
		}
		
	</script>

</head>
<body onload="javascript:initPage()">

	<div id="sitetop class="sitetop">
	
		<div id="topwrapper" class="topwrapper">

			<div id="navbar" class="navbar">

				<div id="navlinks" class="navlinks">
					
					<?php
					
						// test to see if a user is logged in, if so display the logout option
						if( isset($_SESSION['username']) == false )
						{
							echo '<div id="login" class="navlink">';
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
				
				</div>

				<div id="totalsearches" class="totalsearches">
				
					<?php
					
						// TODO: make this dynamicly update on search/page load ... add api maybe?
					
						require_once("SearchTool.class.php");
						
						$searchTool = new SearchTool();
						
						$totalSearches = $searchTool->GetTotalSearchCount();
						
						echo "Total number of searches to date: <b>" . $totalSearches . "</b>";
					
					?>
				
				</div>

			</div>
			
			<div id="title" class="title">
			
				<img id="titleimg" class="titleimg" src="/media/title.png"></img>
			
			</div>

			<div id="searchtop" class="searchtop">

				<div id="search" class="search">

					<div id="keywordsearch" class="userinput">
						Keyword Search:<br>
							<input type="text" id="searchstring" name="searchstring" size="30"><br>
					</div>
					
					<div id="address" class="userinput">
						Address:<br>
							<input type="text" id="addressinput" name="address" size="30"><br>
					</div>
					
					<div id="startdate" class="userinput">
						Start Date:<br>
							<input type="date" id="startdateinput" name="startdate" width="20"><br>
					</div>
					
					<div id="address" class="userinput">
						End Date:<br>
							<input type="date" id="enddateinput" name="enddate" width="20"><br>
					</div>
					
					<div id="organizationlist" class="userinput">
					
						<?
						
							require_once("OrganizationsTool.class.php");

							echo "Oranization(s):<br>";

							// start of select multiple
							echo '<select multiple="organization" id="organizations" name="organization[]">'; // TODO: set this width to a set value
							
							// use our tool to get all of the organization names from the database
							$orgtool = new OrganizationsTool();	
							$orgnames = $orgtool->GetAllOrganizationNames();
							
							// create the select multiple object based on DB into
							foreach($orgnames as $name)
							{
								echo '<option value="' . $name . '">' . $name . '</option>';
							}
							
							// end of select multiple
							echo '</select></br>';

						?>
				
					</div>
		
					<div id="searchbutton" class="userinput">
				
						<button id="searchbutton" onclick="performSearch()">Search</button>
						<br>
				
					</div>
				
					<div id="searchtime" class="searchtime">
					
					</div>
				
					<div id="submitrequest" class="submitrequest">
						<p>Don't see your organization? Submit a request to add it <a href="dashboard/request.php">here</a></p>
					</div>
				
				</div>
				
			</div>
			
			<!--
			<div id="info" class="info">
			
				<center>Your search results will show up here.</center>
			
			</div>
			-->
			
			<div id="results" class="results">
			
				<br>
				<p>Enter a search string, an addres, a date range, and/or an organization to search the database.</p><br>
			
			</div>
			
			<div id="footer" class="footer">
			
				<!-- Footer information here -->
			
			</div>
			
		</div>
	
	</div>
	
</body>
</html>

