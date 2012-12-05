<html>
<head>
	<title>Monroe Minutes</title>

	<script src="http://code.jquery.com/jquery-latest.js"></script>

	<script>
	
		function performSearch()
		{
	
			// get values from the text boxes on the page
			var searchString = document.getElementById('searchstring').value;
			
			// get start time
			var startTime = new Date().getTime();
			
			//alert(searchString);
			
			// get json from api call
			$.getJSON("searchapi.php",
			{
				searchstring: searchString
			},
				function(data) {
					
					// clear out any html that may have been put there already.
					$("div.results").html(resultsHtml);
					
					// init our html contents variable
					var resultsHtml = "";
					
					// itterate through the returned json array and add each document to the div
					$.each(data, 
						function(i,item){
							
							//alert(item.suborgname);
							
							resultsHtml += "<h3><a href=\"http://" + item.sourceurl + "\">" + item.orgname + " - " + item.suborgname + "</a></h3>\n";
							//resultsHtml += "<p><b>Suborganization Name:</b> " + item.suborgname + "</p>\n";
							//resultsHtml += "<p><b>Organization Name:</b> " + item.orgname + "</p>\n";
							//resultsHtml += "<p><b>Source URL:</b> " + item.sourceurl + "</p>\n";
							
							resultsHtml += "<p><b>Document Name:</b> " + item.name + "</p>\n";
							resultsHtml += "<p><b>Document Publication Date:</b> " + item.date + "</p>\n";
							//resultsHtml += "<p><b>Word:</b> " + item.word + "</p>\n";
							//resultsHtml += "<p><b>Frequency:</b> " + item.frequency + "</p></br></br>\n";
							
					});
					
					// take current time
					var endTime = new Date().getTime();
					
					// calculate how long it took for the search
					var timeTaken = endTime - startTime;
					
					$("div.timetaken").html("<p>Search took: " + timeTaken + " milliseconds</p>");
					$("div.results").html(resultsHtml);
					
				}
			);
		
		}
		
	</script>

</head>
<body>

	<div class="top">

		<div class="searchbar"></div>

			<div class="header">
			
			<h3>Monroe Minutes</h3></br>
			
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

