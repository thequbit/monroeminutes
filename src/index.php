<html>
<head>
	<title>Monroe Minutes</title>

	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="js/search.js"></script>
	
	<meta name="description" content="Meeting Minute Agrigator and Search Engine for Monroe County, NY">
	<meta name="keywords" content="Monroe,Minutes,MonroeMinutes,Rochester,Meetings">

	<LINK href="css/style.css" rel="stylesheet" type="text/css">

	<script>
	
		function initPage()
		{
		
			// hide the results div since it isn't populated yet
			$('#results').hide();
		
			// hide search button
			$('#searchbutton').hide();
		
			// setup searchstring text box to fire the button click event if the user hits enter
			$("#searchstring").keyup(function(event){
				if(event.keyCode == 13){
					$("#searchbutton").click();
				}
			});
			
			// setup address text box to fire the button click event if the user hits enter
			$("#address").keyup(function(event){
				if(event.keyCode == 13){
					$("#searchbutton").click();
				}
			});
		
		}
		
	</script>

</head>
<body onload="javascript:initPage()">

	<div id="sitetop class="sitetop">

		<div id="topwrapper" class="topwrapper">

			<div id="totalsearches" class="totalsearches">
				
				<?php
				
					// TODO: make this dynamicly update on search/page load ... add api maybe?
				
					require_once("SearchTool.class.php");
					
					$searchTool = new SearchTool();
					
					$totalSearches = $searchTool->GetTotalSearchCount();
					
					echo "Total number of searches to date: <b>" . $totalSearches . "</b>";
				
				?>
			
				</br></br>
			
			</div>

			<div id="searchtop" class="searchtop">

				<div id="header "class="header">
				
				<!--<h4>Monroe Minutes</h4>-->
				
					<!--
					<div id="links" class="links">
				
						<a href="dashboard/dashboard.php">Dashboard</a></br>
				
					</div>
					-->
				
				</div>

				

				<div id="search" class="search">

					<br>

					Keyword Search:<br>
						<input type="text" id="searchstring" name="searchstring" size="40"><br><br>

					- or -<br><br>

					Address:</br>
						<input type="text" id="address" name="address" size="40"><br>

					<!--
					Date Range:</br>
						<input type="text" id="startdate" name="startdate" size="16"> - <input type="text" name="enddate" size="16"></br>

					Organizations:</br>
					-->
					
					<?
					
						/*
					
						require_once("OrganizationsTool.class.php");

						// start of select multiple
						echo '<select multiple="organization" id="organizations" name="organization[]" width: 100px>'; // TODO: set this width to a set value
						
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

						*/

					?>
				
					</br>
					
					<div id="requestnote" class="requestnote">
					
						Enter in a search string, or your address to search all of Monroe County meeting minutes.<br>
						<br>
						Don't see the organization you are looking for?  Put in a request <a href="request.php">here</a></br>
				
					</div>
				
					</br>
					<button id="searchbutton" onclick="performSearch()">Search</button>
					</br>
				
				</div>
				
			</div>
			
			<!--
			<div id="info" class="info">
			
				<center>Your search results will show up here.</center>
			
			</div>
			-->
			
			<div id="results" class="results">
			
			</div>
			
		</div>
	
	</div>
	
</body>
</html>

