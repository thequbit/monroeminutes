
// this function is intended to be used with index.php and is called when the search button is pressed
function performSearch()
{

	// get values from the text boxes on the page
	var searchString = document.getElementById('searchstring').value;
	var startDate = document.getElementById('startdateinput').value;
	var endDate = document.getElementById('enddateinput').value;
	var address = document.getElementById('addressinput').value;
	var organizations = document.getElementById('organizations').value;
	
	// generate our data to be used in the http POST method to get back our json object
	var postData = {
						searchstring: searchString,
						startdate: startDate,
						enddate: endDate,
						address: address,
						organization: organizations
					};
	
	// get json from api call
	$.getJSON("searchapi.php",
		postData,
		function(data) {
			
			//
			// this is the format of the resturned json object
			//
			// {
			// 		"status":"0",
			//		"errorText":"None",
			//		"queryTime":"8",
			//		"resultCount":4,
			//		"results":[]
			// }
			
			// init our html contents variable
			var resultsHtml = "";
			
			// hide info div since we don't need it any more
			$('#info').hide()
			
			// clear out any html that may have been put there already.
			$("div.results").html("");
			
			// check to see if there was an error
			if( data.error != "0" )
			{
				// TODO: switch on error to see what we want to display to the user
				
				resultsHtml += "<br><p>There was an error processing your request, please try again later.  If the error persists, please contact the site administrator.</p><br>";
			}
			else
			{
			
				var rounderQueryTime = Math.round(data.queryTime*10000)/10000
			
				// place the time it took to return the results as the first item in the div
				$("#searchtime").html("<p>Search took: <b>" + rounderQueryTime + "</b> seconds</p></br>");	
			
				//alert(typeof data.results.length);
			
				if( data.results.length == 0 )
				{
					
					resultsHtml += "<br><p>Your search criteria returned zero results.  Please refine your search and try again.</p><br>";
				
				}
				else
				{
					// itterate through the returned json array and add each document to the div
					$.each(data.results, 
						function(i,item){
							
							//alert(item.suborgname);
							
							resultsHtml += '<div class="searchresult">\n';
							resultsHtml += '<h3><a href="http://' + item.sourceurl + '">' + item.orgname + ' - ' + item.suborgname + '</a></h3>\n';
							//resultsHtml += "<p><b>Suborganization Name:</b> " + item.suborgname + "</p>\n";
							//resultsHtml += "<p><b>Organization Name:</b> " + item.orgname + "</p>\n";
							//resultsHtml += "<p><b>Source URL:</b> " + item.sourceurl + "</p>\n";
							
							resultsHtml += "<p><b>Document Name:</b> " + item.name + "</p>\n";
							resultsHtml += "<p><b>Document Publication Date:</b> " + item.date + "</p>\n";
							//resultsHtml += "<p><b>Word:</b> " + item.word + "</p>\n";
							//resultsHtml += "<p><b>Frequency:</b> " + item.frequency + "</p></br></br>\n";
							
							resultsHtml += '</div><br>\n';
							
					});
				}
			}
			
			// set new html for div
			$("div.results").html(resultsHtml);
		
			// show our results to the user
			$('#results').hide();
			$('#results').show("slow");
	
		}
	);

}