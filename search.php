<!DOCTYPE html>
<html lang="en-us">
<head>
	<meta charset="iso-8859-1">
	<title>Monroe Minutes - Search Results</title>
	<link REL="shortcut icon" HREF="media/favicon.png">
	<meta name="description" content="A searchable database of Meeting Minutes from Monroe County, NY towns and villages." >
	<meta name="keywords" lang="en" content="monroe, county, monroe county, minutes, monroeminutes, search" >
	<link rel="stylesheet" type="text/css" href="style.css">
	
	<script type="text/javascript"> 

	</script>

	<script type="text/javascript">

	</script>

</head>
<body>

	<div id="main" class="main">
		
		<a id="top"></a>
		
		<div id="header-wrapper" class="header-wrapper">
		
			<div id="header" class="header">
				
				<div id="logo" class="logo">
				
					<img border="0" src="/media/logo.png" alt="Monroe Minutes"/> 
				
				</div>
				
				<div id="nav-links" class="nav-links">
					<a href="/index.html">HOME</a> |
					<a href="/docs.html">DOCS</a> |
					<a href="/townlist.php">TOWNS</a> |
					<a href="/about.html">ABOUT</a>
				</div>
				
			</div>
		</div>
		
		<div id="search-results-wrapper" class="search-results-wrapper">
			
			<div id="search-results" class = "search-results">
					
				<h1>Search Results:</h1>
				<br>
				
				<div id="results-list" class="results-list">
					
					<?php

						//
						// This code will populate the results section with the results from the database
						// as well as the brief summary of the returned text
						//

						require_once('performsearch.php');
						
						$keyword = $_GET['keyword'];
						
						performSearch($keyword);

					?>
					
				</div>
				
			</div>
			
		</div>
		
	</div>
	
</body>
</html>