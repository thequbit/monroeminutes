
	var searchingStep = 0;

	function searching()
	{
		switch(searchingStep)
		{
			case 0:
				$("#searchingdiv").html("Searching   ");
				break;
				
			case 1:
				$("#searchingdiv").html("Searching .  ");
				break;
				
			case 2:
				$("#searchingdiv").html("Searching ..  ");
				break;
				
			case 3:
				$("#searchingdiv").html("Searching ....  ");
			case 4:
			default:
				$("#searchingdiv").html("Searching ...  ");
				break;
		}
		
		
	
		if( searchingStep == 4 )
		{
			searchingStep = 0;
		}
		else
		{
			searchingStep++;
		}
	}

	function performSearch()
	{
		var searchString = document.getElementById('searchstring').value;
	
		if( searchString != "" )
		{
			$('#searchresults').html("");
			$('#searchresults').hide();
			$("#searchingdiv").show();
		
			var postData = {};
			
			//alert(searchString);
			
			postData.keyword = searchString;
			
			$.getJSON("http://mycodespace.net/projects/monroeminutes/api/searchapi.php",
			postData,
				function(data) {
			
					//
					// {
					//		"errorCode" : "0",
					//		"errorText" : "Success.",
					//		"apiVersion" : "0.0.1",
					//		"queryTime" : "0.0042359828948975",
					//		"documentCount" : "7",
					//		"searchResults" :
					//		[
					//			{
					//				"documentid":843,
					//				"scrapdt":"2013-03-08 00:00:00",
					//				"publishdate":null,
					//				"docname":"",
					//				"orgname":"Henrietta Town Board",
					//				"sourceurl":"http:\/\/www.henrietta.org\/boards\/townboard\/tbfiles\/doc_download\/1826-apr-18-2012-tb-agenda-a-minutes.html",
					//				"summary":null,
					//				"bodyname":"Town of Henrietta"
					//			}
					//		]
					//	}
					//

					//alert("DAta!");

					// init our html contents variable
					var resultsHtml = "";
			
					//$("#searchresults").hide();
			
					// clear out any html that may have been put there already.
					$("#searchresults").html("");
					
					if( data.errorCode != 0 )
					{
						// report error ...
					}
					else
					{
						var rounderQueryTime = Math.round(data.queryTime*10000)/10000;
						
						$("#querytime").html("<p>Search took: <b>" + rounderQueryTime + "</b> seconds</p>")
						
						// itterate through the returned json array and add each document to the div
						$.each(data.searchResults, 
							function(i,item){

								//alert(item.publishdate);
								
								var publishDate = new Date(item.publishdate);
								
								resultsHtml += '<div class="searchresult">';
								resultsHtml += '<a href="' + item.sourceurl + '">' + item.bodyname + ' - ' + item.orgname + '</a><br>';
								resultsHtml += '<b>' + dateFormat(publishDate,"mmmm dS, yyyy") + '</b><br>';
								resultsHtml += '" ...' + item.summary + '..."';
								resultsHtml += '</div>';
								
								/*
								<div class="searchresult">
									<a href="#">Henrietta - Town Board</a><br>
									<b>March 4th, 2012</b><br>
									"... today the town to a moment to remember how important <b>Kodak</b> has been in the acomplishments and ..."<br>
								</div>
								*/
							}
						);
						
						window.clearInterval();
						$("#searchingdiv").hide();
						
						$("#searchresults").html(resultsHtml);
						
						$('#searchresults').hide();
						$('#searchresults').show("slow");
					}
				
				}
			);
		}
		else
		{
			// do nothing
		}
	}