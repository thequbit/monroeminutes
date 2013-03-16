<?php  
  
function read()  
{  
   $parameters = array();  
   $results = array();  
  
	//$query = 'SELECT Documents.documentid AS documentid, Documents.scrapdt AS scrapdt, Documents.publishdate AS publishdate, Documents.docname AS docname, Organizations.name AS orgname, Documents.sourceurl AS sourceurl, Bodies.name AS bodyname from Documents INNER JOIN Words ON Words.documentid = Documents.documentid INNER JOIN Organizations ON Organizations.organizationid = Documents.organizationid INNER JOIN Bodies ON Bodies.bodyid = Documents.bodyid WHERE LOWER(Words.word)="kodak"';
  
	//$query = 'SELECT Documents.documentid AS documentid, Documents.scrapdt AS scrapdt, Documents.publishdate AS publishdate, Documents.docname AS docname, Organizations.name AS orgname, Documents.sourceurl AS sourceurl, Bodies.name AS bodyname from Documents INNER JOIN Words ON Words.documentid = Documents.documentid INNER JOIN Organizations ON Organizations.organizationid = Documents.organizationid INNER JOIN Bodies ON Bodies.bodyid = Documents.bodyid WHERE LOWER(Words.word)="kodak"';
  
	$query = "SELECT * FROM Bodies WHERE bodyid=?";
  
   $mysql = new mysqli("lisa.duffnet.local", "mmuser", "password123%%%", "monroeminutes2") or die('There was a problem connecting to the database');  
   $stmt = $mysql->prepare($query) or die('Problem preparing query');  
  
	$id = 2;
  
	$stmt->bind_param("s", $id);

	$stmt->execute();  
  
   $meta = $stmt->result_metadata();  
  
   while ( $field = $meta->fetch_field() ) {  
  
     $parameters[] = &$row[$field->name];   
   }  
  
   call_user_func_array(array($stmt, 'bind_result'), $parameters);  
  
   while ( $stmt->fetch() ) {  
      $x = array();  
      foreach( $row as $key => $val ) {  
         $x[$key] = $val;
		 //printf("%s\n<br>",$key);
      }  
      $results[] = $x;  
   }  
  
   return $results;  
}  
  
//$results = read();  
?>  
<!DOCTYPE html>  
  
<html lang="en">  
<head>  
   <meta charset="utf-8">  
   <title>untitled</title>  
</head>  
<body>  

<?php //foreach ($results as $row) : ?>  
  
	
   <p> <?php //echo $row['bodyid']; ?> </p>  
   <p> <?php //echo $row['name']; ?> </p>
   
 
<?php //endforeach; ?>  

<?php

	/*
	require_once("./tools/WordsTool.class.php");
	$wordsTool = new WordsTool();
	$jsonData = json_encode($wordsTool->DocIDsFromWord("kodak"));
	*/
	
	/*
	require_once("./tools/DocumentsTool.class.php");
	$docTool = new DocumentsTool();
	$jsonData = json_encode($docTool->GetDocumentByID(1000));
	*/
	
	/*
	require_once("./tools/BodiesTool.class.php");
	$bodyTool = new BodiesTool();
	$jsonData = json_encode($bodyTool->GetBodiesDictionary());
	*/
	
	/*
	require_once("./tools/OrganizationsTool.class.php");
	$orgTool = new OrganizationsTool();
	$jsonData = json_encode($orgTool->GetOrganizationsDictionary());
	*/
	
	
	require_once("./tools/SearchTool.class.php");
	$searchTool = new SearchTool();
	$jsonData = json_encode($searchTool->PerformSearch("kodak"));
	
	
	/*
	require_once("./tools/SearchTool.class.php");
	$searchTool = new SearchTool();
	$searchTool->RecordSearch("Hello.");
	*/
	
	echo $jsonData;

?>

</body>  
</html>  















<?php

/*

	$mysqli = new mysqli("lisa.duffnet.local", "mmuser", "password123%%%", "monroeminutes2");

	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	//GX45UA
	
	//$query = 'SELECT Documents.documentid, Documents.scrapdt, Documents.publishdate, Documents.docname, Organizations.name, Documents.sourceurl, Bodies.name FROM Documents INNER JOIN Words ON Words.documentid = Documents.documentid INNER JOIN Organizations ON Organizations.organizationid = Documents.organizationid INNER JOIN Bodies ON Bodies.bodyid = Documents.bodyid WHERE LOWER(Words.word) = LOWER(?)';
	
	//$query = 'SELECT documentid,scrapdt,publishdate,docname,organizationid,sourceurl,scrapurlid from Documents where documentid=1000';
	
	//$query = 'SELECT * FROM Documents WHERE documentid=1000';
	
	//$query = 'SELECT name FROM Bodies';
	
	// prepare statement
	//$stmt = $mysqli->prepare("SELECT name FROM Bodies WHERE bodyid=?");
	$stmt = $mysqli->prepare($query);
	
	//$keyword = "kodak";
	
	//$stmt->bind_param("s", $keyword);
	
	$stmt->execute();

	//bind variables to prepared statement 
	$stmt->bind_result($docid, $scrapdt, $pubdate, $docname, $orgname, $sourceurl, $bodyname);

	//fetch values 
	while ($stmt->fetch()) {
		printf("Document ID: %s\n<br>", $docid);
		printf("Scrap DT: %s\n<br>", $scrapdt);
		printf("Publish Date: %s\n<br>", $pubdate);
		printf("Document Name: %s\n<br>", $docname);
		printf("Organization Name: %s\n<br>", $orgname);
		printf("Source URL: %s\n<br>", $sourceurl);
		printf("Body Name: %s\n<br>", $bodyname);
		printf("<hr><br>");
	}

	//close statement
	$stmt->close();

	// close connection
	$mysqli->close();

*/
?>