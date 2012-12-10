<?php

	// start our session so we have access to our $_SESSION variables
	session_start();
	
	if( isset($_SESSION['username']) == false )
	{
		header("Location: login.php?redirecturl=" . urlencode("dashboard/request.php"));
	}

?>

<html>
<head>

	<title>Monroe Minutes - Request Page</title>

	<meta name="description" content="Meeting Minute Agrigator and Search Engine for Monroe County, NY">
	<meta name="keywords" content="Monroe,Minutes,MonroeMinutes,Rochester,Meetings">

	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="../js/orgs.js"></script>

	<link href="../css/main.css" rel="stylesheet" type="text/css">
	<link href="../css/request.css" rel="stylesheet" type="text/css">
	

</head>
<body onload="javascript:populateOrganizationList()">

	<div id="sitetop" class="sitetop">
	
		<div id="topwrapper" class="topwrapper">
		
			<br><br>
		
			<div id="requesttitle" class="requesttitle">
			
				<img id="titleimg" class="titleimg" src="/media/title.png"></img>
			
			</div>
		
			<div id="requestinfo" class="requestinfo">
			
				<div id="existingorgs" class="existingorgs">
				
				</div>
			
			</div>
		
		</div>
		
	</div>

</body>
</html>

