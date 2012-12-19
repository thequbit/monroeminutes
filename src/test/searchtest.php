<html>
<body>

<form action="searchapi.php">

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
				echo '<select multiple="organization" id="organizations" name="organization[]" style="width: 210px">'; // TODO: set this width to a set value
				
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
	
	</div>

	<input type="submit" >

</form>

</body>
</html>