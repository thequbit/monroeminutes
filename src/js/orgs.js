
function populateOrganizationList()
{
	var postData = {};
	
	postData.action = "orgs";
	postData.orgname = "";

	// get json from api call
	$.getJSON("../orgapi.php",
		postData,
		function(data) {
		
			var resultsHtml = "";
		
			$("div.existingorgs").html("");
		
			if( data.error != "0" )
			{
				// TODO: switch on error to see what we want to display to the user
				
				resultsHtml += "<br><p>There was an error processing your request, please try again later.  If the error persists, please contact the site administrator.</p><br>";
			}
			else
			{
				if( data.results.length == 0 )
				{
					// no results were returned ... there is no orgs in the system
					resultsHtml += '<p>There are no organizations entered into the system yet.  Enter a request <a href="request.php>here</a> to have more organizations added to the list.</p><br>';
				}
				else
				{
					
					// this is what the json returned looks like:
					/*
					{
						"error": "0",
						"errortext": "None",
						"results": [{
										"name":"Henrietta",
										"suborgs":[{
														"name":"Town of Henrietta",
														"url":"http:\/\/www.henrietta.org"
													}]
									},
									{
										"name":"Fairport",
										"suborgs":[{
														"name":"Village of Fairport",
														"url":"http:\/\/www.village.fairport.ny.us\/"
													}]
									}]
					}
					*/
					
					var orgnum=0;
					var suborgnum=0;
					
					// first layer of foreach is the organizations
					$.each(data.results, 
						function(i,item){
					
							resultsHtml += "<div id=\"org" + orgnum + "\" class=\"orgdiv\">";
							resultsHtml += "<p>" + item.name + "</p>";
							
							orgnum++;
							
							// second layer of foreach is the suborganizations
							$.each(item.suborgs, 
								function(i,item){
							
								resultsHtml += "<div id=\"suborg" + suborgnum + "\" class=\"suborgdiv\">";
								resultsHtml += "<p> |- <a href=\"" + item.url + "\">" + item.name + "</a></p>";
								resultsHtml += "</div>";
								
								suborgnum++;
							});
							
							
							
							resultsHtml += "</div>";
					});
					
					
				}
			}
		
			// set html within the div tag
			$("div.existingorgs").html(resultsHtml);
			
		}
		
		
	);

}