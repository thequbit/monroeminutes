<html>
<head>
<title>Monroe Minutes Administrator Dashboard</title>
</head>
<body>

	<div id="top" class="top">
	
		
		</br>
		</br>
	
	</div>

	<div id="organizations" class="organizations">
	
		<h4>Organizations:</h4>

		<form action="">
	
			<select multiple="organization" name="organization[]">
		
			<?php
				require_once("../OrganizationsTool.class.php");
				
				// use our tool to get all of the organization names from the database
				$orgtool = new OrganizationsTool();	
				$orgnames = $orgtool->GetAllOrganizationNames();
				
				// create the select multiple object based on DB into
				foreach($orgnames as $name)
				{
					echo '<option value="' . $name . '">' . $name . '</option>';
				}
			?>
	
			</select></br>
	
		</form>

	
		<a href="addorganization.php">Add Organization</a></br>

	
	</div>

	<div id="suborganizations" class="suborganizations">
	
		<h4>Suborganizations:</h4>
	
		<form action="">
	
			<select multiple="suborganization" name="suborganization[]">
		
			<?php
			
				require_once("../OrganizationsTool.class.php");
				
				// use our tool to get all of the organization names from the database
				$orgtool = new OrganizationsTool();	
				$suborgnames = $orgtool->GetAllSubOrganizationNames();
				
				// create the select multiple object based on DB into
				foreach($suborgnames as $subname)
				{
					echo '<option value="' . $subname . '">' . $subname . '</option>';
				}
			?>
	
			</select></br>
	
		</form>

		<a href="addsuborganization.php">Add SubOrganization</a></br>
	
	</div>
	
	<div id="categories" class="categories">
	
		<h4>Categories:</h4>
	
		<form action="">
	
			<select multiple="categories" name="categories[]">
		
			<?php
				require_once("../OrganizationsTool.class.php");
				
				// use our tool to get all of the organization names from the database
				$orgtool = new OrganizationsTool();	
				$categories = $orgtool->GetAllCategoryNames();
				
				// create the select multiple object based on DB into
				foreach($categories as $cat)
				{
					echo '<option value="' . $cat . '">' . $cat . '</option>';
				}
			?>
	
			</select></br>
	
		</form>
	
		<a href="addorganizationcategory.php">Add Organization Category</a></br>
	
	</div>

</body>
</html>

