<?php

	require_once('keywordsearchtool.class.php');

	function str_insert($insertstring, $intostring, $offset)
	{
		
	   $part1 = substr($intostring, 0, $offset);
	   $part2 = substr($intostring, $offset);
	  
	   $part1 = $part1 . $insertstring;
	   $whole = $part1 . $part2;
	   
	   return $whole;
	}

	function performSearch($keyword)
	{
							
		// decode the user input and perform search
		if( !empty($keyword))
		{
			
			dprint("Processing request ...</br>");
			
			// decode input
			//$keyword = $_GET['keyword'];
		
			dprint("Keyword = " . $keyword);
		
			// create a new instance of our tool to use
			$search = new KeyWordSearchTool();
			
			dprint("Converting results");
			
			// perform the search
			$results = $search->keywordSearchWithTown($keyword, null);
			
			$count = count($results);
			
			if( $count )
			{
			
				dprint("Count = " . $count);
				
				// itterate through the search results pulling out the important 
				// information and generating the appropreate HTML to display it
				foreach($results as $r)
				{
					
					echo '<div class="result">';
					echo '<h2><a href="' . $r->url . '">' . $r->townName . '</a></h2>';
					echo '<h4>' . $r->date . '</h4>';
					echo '<div style="height:100px;">';
			
					$startdiv = '<b><font size="4">';
					$enddiv = '</font></b>';
			
					$firstPos = strpos(strtolower ($r->summary), strtolower($keyword), 0);
					$secondPos = strpos(strtolower ($r->summary), strtolower($keyword), 0) + strlen($startdiv) + strlen($keyword);
					
					$r->summary = str_insert($startdiv,$r->summary,$firstPos);
					$r->summary = str_insert($enddiv,$r->summary,$secondPos);
					
					echo $r->summary;
			
					echo '</div>';
					echo '<div style="clear:both;"></div>';
					echo '</div>';
					
				}
			
			}
			else
			{
				echo "No results were found for your keyword search.";
			}
			
		}
		else
		{
			// there is no keyword present, so we can't do anything.  we need to
			// report this and move on.
			
			//TODO: redirect to search page
			
			echo "No Search Criteria Present!";
		}

	}

?>