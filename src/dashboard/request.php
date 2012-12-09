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

	<link href="../css/main.css" rel="stylesheet" type="text/css">
	<link href="../css/dashboard.css" rel="stylesheet" type="text/css">

</head>
<body>

	<div id="sitetop class="sitetop">
	
		<div id="topwrapper" class="topwrapper">
		
			<div id="requestinfo" class="requestinfo">
			
				test.
			
			</div>
		
		</div>
		
	</div>

</body>
</html>

