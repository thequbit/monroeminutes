<?php
	require_once("_header.php");
?>

	<script src="http://code.jquery.com/jquery-latest.js"></script>
	
	<script type="text/javascript">
	
		
	
	</script>

	<div>
		<form name="input" action="search.php" method="get">
		
			Enter a Search Term</br>
			<input type="text" id="keyword" name="keyword" size="80" value=""></br>
			
			Select a Specific Organization</br>
			<select id="organization" name="organization">
			
				<?php
				
					require_once("./tools/OrganizationsManager.class.php");
					
					$orgmgr = new OrganizationsManager();
					$orgs = $orgmgr->getall();
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
			
				// see if we actually are performing a search
				if( isset($_GET['keyword']) && isset($_GET['organization']) )
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
					$keyword = $_GET['keyword'];
					$organizationid = $_GET['organization'];
			
					//echo "Searching for '" . $keyword . "' within org #" . $organizationid . ", returning page #" . $page . " ...</br>";
			
					require_once("./tools/WordsManager.class.php");
					
					// perform the search
					$wordsmgr = new WordsManager();
					
					$retwords = $wordsmgr->search($organizationid,$keyword,$page);
					$totalcount = $wordsmgr->getcount($organizationid, $keyword);
				
					echo json_encode($retwords);
				
					/*
				
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
					
					*/
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