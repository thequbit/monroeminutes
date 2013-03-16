<?php
	require_once("_header.php");
?>

	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="js/date.format.js"></script>
	<script src="js/search.js"></script>
	
	<script type="text/javascript">
	
		
	
		$(document).ready(function() {
		
			// setup searchstring text box to fire the performSearch() function when the enter key is hit
			$("#searchstring").keyup(function(event){
				if(event.keyCode == 13){
				
					// execute search with inputs
					performSearch();
				}
			});
			
			// Populate list of Bodies from API call
			//
			// TODO: that ^
			//
			
			if( searchOnPost == false && $('#searchstring').value != "" )
			{
				performSearch();
				searchOnPost = true;
			}

			setInterval(function(){searching()},250);
			$("#searchingdiv").hide();
			
		});
	
		var searchOnPost = false;
		
	</script>

	<div class="searchinput">
		<input type="text" id="searchstring" name="searchstring" size="80" value="<?php echo $_GET['searchstring']; ?>"> 
		<button id="searchbutton" onclick="performSearch()">Search</button>
	</div>
	
	<div>
	
		<div class="dropdowninput">
			Select a Specific Body<br>
			<!-- These populated via JQuery on document ready -->
			<select id="bodies" name="bodies">
				<option value="1">Henrietta</option>
				<option value="2">Brighton</option>
				<option value="3">Greece</option>
			</select>
		</div>
		
		<div class="dropdowninput">
			Select a Specific Organization<br>
			<!-- These populated via JQuery Body selection change -->
			<select id="organizations" name="organizations">
				<option value="0">All</option>
				<option value="1">Town Board</option>
				<option value="2">Zoning Board</option>
				<option value="3">Youth Board</option>
			</select>
		</div>
		<br>
		<div id="querytime"></div>
	
		<!-- haxzor ... -->
		<div class="clear"></div>
	
	</div>

	

	<div class="searchwrapper">

		<div id="searchingdiv" class="searchingdiv"></div>

		<div id="searchresults" class="searchresults">
		
			<!-- this is where the jquery search results will show up -->
			
			<!--
			<div class="searchresult">
				<a href="#">Henrietta - Town Board</a><br>
				<b>March 4th, 2012</b><br>
				"... today the town to a moment to remember how important <b>Kodak</b> has been in the acomplishments and ..."<br>
			</div>
		
			<div class="searchresult">
				<a href="#">Henrietta - Zoning Board</a><br>
				<b>April 11th, 2011</b><br>
				"... to a vote the purchasing of <b>Kodak</b> build number 7 for use by the town youth group ..."<br>
			</div>
			-->
			
		</div>

	</div>

<?php
	require_once("_footer.php");
?>