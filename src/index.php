<html>
<head>
	<title>Monroe Minutes</title>

	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="js/search.js"></script>

	<script>
	
		
		
	</script>

</head>
<body>

	<div class="top">

		<div class="searchbar"></div>

			<div id="header "class="header">
			
			<h3>Monroe Minutes</h3></br>
			
			</div>

			<div id="totalsearches" class="totalsearches">
			
			<?php
			
				require_once("SearchTool.class.php");
				
				$searchTool = new SearchTool();
				
				$totalSearches = $searchTool->GetTotalSearchCount();
				
				echo "Total number of searches to date: <b>" . $totalSearches . "</b></br></br>";
			
			?>
			
			</div>

			<!--<form action="searchapi.php">-->

				Keyword Search:</br>
					<input type="text" id="searchstring" name="searchstring" size="25"><br>

				Address:</br>
					<input type="text" id="address" name="address" size="25"><br>

				Date Range:</br>
					<input type="text" id="startdate" name="startdate" size="8"> - <input type="text" name="enddate" size="8"></br>

				Organizations:</br>
				<?
				
					/////////////////////////////////////////////////
					//
					// Select Organization PHP Code
					//
					/////////////////////////////////////////////////
					
					require_once("OrganizationsTool.class.php");

					// start of select multiple
					echo '<select multiple="organization" id="organizations" name="organization[]">';
					
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
			
			<!-- <input type="submit" > -->
			
			<button onclick="performSearch()">Search</button>
			
			<!--</form>-->
		
		</div>
		
		<div id="links" class="links">
		
			<a href="dashboard/dashboard.php">Dashboard</a>
		
		</div>
		
		<div id="timetaken" class="timetaken">
		
		</div>
		
		<div id="results" class="results">
		
		</div>
		
	</div>
	
</body>
</html>

