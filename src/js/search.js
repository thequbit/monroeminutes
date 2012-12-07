
// this function is intended to be used with index.php and is called when the search button is pressed
function performSearch()
{

	// get values from the text boxes on the page
	var searchString = document.getElementById('searchstring').value;
	
	// get start time
	var startTime = new Date().getTime();
	
	//alert(searchString);
	
	// get json from api call
	$.getJSON("searchapi.php",
	{
		searchstring: searchString
	},
		function(data) {
			
			// init our html contents variable
			var resultsHtml = "";
			
			// hide info div since we don't need it any more
			$('#info').hide()
			
			// clear out any html that may have been put there already.
			$("div.results").html("");
			
			// take current time
			var endTime = new Date().getTime();
			
			// calculate how long it took for the search
			var timeTaken = endTime - startTime;
			
			// place the time it took to return the results as the first item in the div
			resultsHtml = "<p>Search took: <b>" + timeTaken + "</b> milliseconds</p></br>";	
			
			// itterate through the returned json array and add each document to the div
			$.each(data, 
				function(i,item){
					
					//alert(item.suborgname);
					
					resultsHtml += "<h3><a href=\"http://" + item.sourceurl + "\">" + item.orgname + " - " + item.suborgname + "</a></h3>\n";
					//resultsHtml += "<p><b>Suborganization Name:</b> " + item.suborgname + "</p>\n";
					//resultsHtml += "<p><b>Organization Name:</b> " + item.orgname + "</p>\n";
					//resultsHtml += "<p><b>Source URL:</b> " + item.sourceurl + "</p>\n";
					
					resultsHtml += "<p><b>Document Name:</b> " + item.name + "</p>\n";
					resultsHtml += "<p><b>Document Publication Date:</b> " + item.date + "</p>\n";
					//resultsHtml += "<p><b>Word:</b> " + item.word + "</p>\n";
					//resultsHtml += "<p><b>Frequency:</b> " + item.frequency + "</p></br></br>\n";
					
			});
			
			
			
			// set new html for div
			$("div.results").html(resultsHtml);
		
			// show our results to the user
			$('#results').hide();
			$('#results').show("slow");
	
		}
	);

}