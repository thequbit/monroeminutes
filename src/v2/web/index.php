<?php
	require_once("_header.php");
?>

	<div class="spacer"></div>

	<div class="infoheader">
		Type in a keywords below to search documents from all over Monroe County, NY
	</div>
	
	<div class="spacer"></div>
	
	<form action="search.php" method="get">
	
		<div class="textinput">
			<input type="text" id="searchstring" name="searchstring" size="60">
		</div>
	
		<div class="buttoninput">
			<input type="submit" value="Search">
		</div>
	
	</form>
	
	<div class="spacer"></div>
	
<?php
	require_once("_footer.php");
?>