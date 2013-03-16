<?php

	class SummaryTool
	{
	
		function MakeSummary($length, $keyword, $doctext)
		{
			// find the word in the document text
			$keywordLocation = strpos(strtolower($doctext),strtolower ($keyword));
			
			if( $keywordLocation === false )
			{
				// um ...
				$summary = "sloppy";
			}
			else
			{
			
				// generate our start
				$start = $keywordLocation - ($length/2);
				
				if( $start < 0 )
				{
					$start = 0;
				}
				
				$front = substr($doctext,$start,$length/2);
				
				$keyword = substr($doctext,$keywordLocation,strlen($keyword));
				
				$back = substr($doctext,$keywordLocation + strlen($keyword),$length/2) ;
				
				
				$summary = $front . "<b>" . $keyword . "</b>" . $back;
				
			}
			
			return $summary;
		}
	}

?>