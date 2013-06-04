<?php
	require_once("_header.php");
?>

	<script src="http://code.jquery.com/jquery-latest.js"></script>
	
	<script type="text/javascript">
	</script>

	<div class="searchwrapper">
		<form name="input" action="search.php" method="get">

			<div class="searchbox">
				Enter a Search Term</br>
				<input type="text" id="keyword" name="keyword" size="80" value=""></br>
			</div>
			
			<div class="orgselectbox">
				Select a Specific Organization</br>
				<select id="organization" name="organization">
				
					<?php
					
						require_once("./tools/OrganizationsManager.class.php");
						
						$orgmgr = new OrganizationsManager();
						$orgs = $orgmgr->getall();
						foreach($orgs as $org)
						{
							echo '<option value="' . $org->organizationid . '">' . $org->name . '</option>\n';
						}
						
					?>
				
				</select>
			</div>
			
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
					require_once("./tools/DocumentsManager.class.php");
					require_once("./tools/SuborganizationsManager.class.php");
					
					$docmgr = new DocumentsManager();
					$wordsmgr = new WordsManager();
					$sorgmgr = new SuborganizationsManager();
					
					//echo "Organization ID = " . $organizationid . "<br>";
					//echo "Keyword = " . $keyword . "<br>";
					
					$retwords = $wordsmgr->search($organizationid,$keyword,$page);
					$wordcount = $wordsmgr->getcount($organizationid, $keyword);
				
					// get the docs
					$docs = array();
					foreach($retwords as $retword)
					{
						$doc = $docmgr->get($retword->documentid);
						$docs[] = $doc;
					}
				
					/*
					// create words array
					$words = array();
					foreach($retword as $word)
					{
						$words[$word->word] = (object) array('documentid' => $word->documentid, 'frequency' => $word->frequency);
					}
					*/
					
					// create suborg dictionary
					$suborgs = $sorgmgr->getall();
					$suborgdict = array();
					foreach($suborgs as $suborg)
					{
						$suborgdict[$suborg->suborganizationid] = (object) array( 'name' => $suborg->name, 'websiteurl' => $suborg->websiteurl );
					}
				
					//echo "Count = " . $wordcount . "<br>";
				
					//echo json_encode($retwords);
				
					
					
					$start = (($page - 1) * 10)+1;
					if( $start + 9 > $wordcount )
						$end = $wordcount;
					else
						$end = $start + 9;
					
					echo '<div class="srtop">';
					
					echo '<a href="' . $suborgdict[$doc->suborganizationid]->websiteurl . '">' . $suborgdict[$doc->suborganizationid]->name . '</a>';
					
					if( $wordcount == 1 )
						echo '<div class="srcounts"><div class="righttext">Displaying 1 total result.</div></div>';
					else
						echo '<div class="srcounts"><div class="righttext">Displaying ' . $start . ' to ' . $end . ' of ' . $wordcount . ' total results.</div></div>';

					echo "</div>\n";
				
					// print all of the results to the page
					foreach($docs as $doc)
					{
						echo '<div class="searchresult">';
						echo '<div class="srheader"><a href="' . $doc->sourceurl . '">' . $doc->name . '</a> - ' . $doc->documentdate . '</div>';
						echo '<div class="srurlheader">' . $doc->sourceurl . '</div></br>';
						echo '<div class="srpreviewtext">"... that the floor plan for this house is identical to the last one the Planning Board approved. He passed around a sample of the exterior color, which members agreed was aesthetically ..."</div>';
						//echo '<div class="srsubheader"><a href="' . $suborgdict[$doc->suborganizationid]->websiteurl . '">' . $suborgdict[$doc->suborganizationid]->name . '</a></div>';
						echo "</div>\n\n";
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