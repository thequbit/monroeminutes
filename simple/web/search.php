<?php
	require_once("_header.php");
?>

	<script src="http://code.jquery.com/jquery-latest.js"></script>
	
	<script type="text/javascript">
	
		
	
	</script>

	<div>
		<form name="input" action="search.php" method="get">
		
			Enter a Search Term</br>
			<input type="text" id="searchterm" name="searchterm" size="80" value=""></br>
			
			Select a Specific Organization</br>
			<select id="organization" name="organization">
			
				<?php
				
					require_once("./tools/OrganizationManager.class.php");
					
					$orgmgr = new OrganizationManager();
					$orgs = $orgmgr->GetAllOrganizations();
					foreach($orgs as $org)
					{
						echo '<option value="' . $org->id . '">' . $org->name . '</option>\n';
					}
					
				?>
			
			</select>
			
			<div class="smallpadding">
			<input type="submit" id="search" value="Search"></br>
			</div>
			
		</form>
	</div>

	<div class="searchwrapper">

		<div id="searchresults" class="searchresults">
		
			<?php
			
				if( isset($_GET['searchterm']) && isset($_GET['organization']) )
				{
					
					// decode page number, if set
					if( isset($_GET['page']) )
					{
						$page = intval($_GET['page']);
						if( $page < 1 )
						{
							$page = 1;
						}
					}
					else
					{
						$page = 1;
					}
					
					// decode search term and org id
					$searchterm = $_GET['searchterm'];
					$organizationid = $_GET['organization'];
			
					//echo "Searching for '" . $searchterm . "' within org #" . $organizationid . ", returning page #" . $page . " ...</br>";
			
					require_once("./tools/SearchManager.class.php");
					
					// perform the search
					$searchmgr = new SearchManager();
					$documents = $searchmgr->PerformSearch($searchterm,$organizationid,$page);
				
					$totalcount = $searchmgr->GetSearchResultCount($searchterm, $organizationid);
				
					$start = intval( (($page-1) * 10 ) + 1 );
					
					if( $start + 9 > $totalcount)
						$end = $totalcount;
					else
						$end = $start + 9; // inclusive
				
					echo '<div class="righttext">';
					
					
					// deturmine plural
					if( $totalcount == 1 )
						echo "Displaying 1 total result.";
					else
						echo "Displaying " . $start . " to " . $end . ", of " . $totalcount . " total results.";
					
					echo '</div>';
				
					// print all of the results to the page
					foreach($documents as $doc)
					{
						echo '<div class="searchresult">';
						echo '<div class="srheader"><a href="' . $doc->sourceurl . '">' . $doc->docname . '</a></div>';
						echo '<div class="srsubheader"><a href="' . $doc->websiteurl . '">' . $doc->suborgname . '</a></div>';
						echo '</div>';
					}
				}
				else
				{
					//echo "Looks like there was an error, try reloading the page.";
				}
			?>
			
		</div>

	</div>

<?php
	require_once("_footer.php");
?>