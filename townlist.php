<!DOCTYPE html>
<html lang="en-us">
<head>
	<meta charset="iso-8859-1">
	<title>Monroe Minutes - Town List</title>
	<link REL="shortcut icon" HREF="media/favicon.png">
	<meta name="description" content="A searchable database of Meeting Minutes from Monroe County, NY towns and villages." >
	<meta name="keywords" lang="en" content="monroe, county, monroe county, minutes, monroeminutes, search" >
	<link rel="stylesheet" type="text/css" href="style.css">
	
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

		<div id="search-wrapper" class="search-wrapper">
		
			
			
			<h1>Towns, Villages, and Cities in Monroe County</h1>
			
			<br>
		
			<?php
			
				//
				// This code will pull from the database and display all the
				// towns that are being indexed
				//
			
				require_once('townlisttool.class.php');
	
				$townlist = new TownListTool();
				
				$towns = $townlist->getList();
				
				foreach($towns as $town)
				{
					
					if( $town->url != null )
						echo "<a href=" . $town->url . ">" . $town->name . "</a> ( " . $town->type . " ) <br>";
					else
						echo $town->name . "</a> ( " . $town->type . " ) <br>";
				
				}
			
			?>
		
		</div>

	</div>

</body>
</html>