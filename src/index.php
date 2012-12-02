<html>
<head>
	<title>Monroe Minutes</title>

	<script src="http://code.jquery.com/jquery-latest.js"></script>

	<script>
		
	</script>

</head>
<body>

	<div class="top">

		<div class="searchbar"></div>

			<div class="header">
			
			<h3>Monroe Minutes</h3></br>
			
			</div>

			<form action="searchapi.php">

				Keyword Search:</br>
					<input type="text" name="keywordsearch" size="25"><br>

				Address:</br>
					<input type="text" name="address" size="25"><br>

				Date Range:</br>
					<input type="text" name="startdate" size="8"> - <input type="text" name="enddate" size="8"></br>

				Organizations:</br>

				<?
				
					/////////////////////////////////////////////////
					//
					// Select Organization PHP Code
					//
					/////////////////////////////////////////////////
					
					require_once("OrganizationsTool.class.php");

					// start of select multiple
					echo '<select multiple="organization" name="organization[]">';
					
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
			
			<input type="submit">
			
			</form>
		
		</div>
		
		<div class="results">
		
			
		
		</div>
		
	</div>
	
</body>
</html>

