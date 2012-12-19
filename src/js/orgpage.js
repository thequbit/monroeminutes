
	function displaySubOrg(orgname)
	{
		
		var postData = {};
		postData.orgname = orgname;
		postData.orgtype = "suborg";
		
		// get json from api call
		$.getJSON("./api/orgapi.php",
			postData,
			function(data) {
			
				// init our html contents variable
				var resultsHtml = "";
				
				// clear out any html that may have been put there already.
				$("#orginfo").html("");
			
				resultsHtml += '<h3>' + data.name + '</h3><br><br>';
				resultsHtml += 'Belongs to: ' + data.organiztionname + '<br>';
				resultsHtml += 'Website: <a href="' + data.websiteurl + '">' + data.websiteurl + '</a><br>';
				resultsHtml += 'Documents Website: <a href="' + data.documentsurl + '">' + data.documentsurl + '</a><br>';
				resultsHtml += 'Number of Indexed Documents: ' + data.indexeddocs + '<br>';
				resultsHtml += '----<br>';
				resultsHtml += 'Script Name: ' + data.scriptname + '<br>';
				resultsHtml += 'DB Populated: ' + data.dbpopulated + '<br>';
				
			
				$("#orginfo").html(resultsHtml);
			
				// show our results to the user
				$('#orginfo').hide();
				$('#orginfo').show("slow");
			
			}
		);	

	}