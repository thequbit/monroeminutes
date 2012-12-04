<html>
<head>
<title>Monroe Minutes Administrator Dashboard</title>
</head>
<body>

	<div id="top" class="top">
	
		<a href="addorganization.php">Add Organization</a></br>
		<a href="addsuborganization.php">Add SubOrganization</a></br>
		<a href="addorganizationcategory.php">Add Organization Category</a></br>
		</br>
		</br>
	
	</div>

	<div id="organizations" class="organizations">
	
	
		Organizations:</br>

		<?
		
			/////////////////////////////////////////////////
			//
			// Select Organization PHP Code
			//
			/////////////////////////////////////////////////
			
			require_once("../OrganizationsTool.class.php");

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
	
	</div>

	<div id="suborganizations" class="suborganizations">
	
	
	
	</div>
	
	<div id="categrories" class="categrories">
	
	
	
	</div>

</body>
</html>

