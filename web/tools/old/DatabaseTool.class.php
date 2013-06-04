<?php

	require_once("debug.php");
	require_once("sqlcredentials.php");

	class DatabaseTool
	{
		//private $mysqli;
	
		/*
		function SanitizeInput($input)
		{
			try
			{
		
				// first ensure there are escape chars
				$retVal = mysql_real_escape_string($input);
			
			}
			catch (Exception $e)
			{
				dprint( "Caught exception: " . $e->getMessage() );
			
				$retVal = "";
			}
			
			// return the sanitized string
			return $retVal;
		}
		*/
		
		function Connect()
		{
			//dprint("[DB_LAYER] Trying to connect to database ...");
		
			$mysqli = null;
		
			try
			{
	
				$mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);
				
				if (mysqli_connect_errno())
				{
					dprint( "Connect failed: %s\n" . mysqli_connect_error() );
					die (MYSQL_DATABASE . " Database not found. " . MYSQL_USER);	// TODO: something more elegant than this
				}
				
				//dprint("[DB_LAYER] Connected to DB.");
			
			}
			catch (Exception $e)
			{
				dprint( "Caught exception: " . $e->getMessage() );
				
				$mysqli = null;
			}
			
			return $mysqli;
		}
		
		function Execute($stmt)
		{
		
			//dprint ("[DB_LAYER] Executing Prepared Statement ...");
		
			$parameters = array();  
			$results = array();  
		
			// execute the query
			$stmt->execute();
		
			dprint("here.");
		
			$meta = $stmt->result_metadata();  

			if( $meta == null || $meta == "" )
			{
				//dprint( "[DB_LAYER] No data returned." );
			}
			else
			{

				while ( $field = $meta->fetch_field() )
				{
					$parameters[] = &$row[$field->name];   
				}  

				call_user_func_array(array($stmt, 'bind_result'), $parameters);  

				while ( $stmt->fetch() )
				{
					$x = array();  
				
					foreach( $row as $key => $val )
					{  
						$x[$key] = $val;  
						//printf("%s\n<br>",$key);
					}  
					
					$results[] = $x;  
				}  

			}

			//dprint ("[DB_LAYER] Prepared Statement Executed.");

			return $results;  
		}
		
		function Close($mysqli)
		{

			try
			{
				$mysqli->close();
				//$stmt->close();
				
				//dprint( "[DB_LAYER] Database Connection Closed." );
			}
			catch (Exception $e)
			{
				dprint( "Caught exception: " . $e->getMessage() );
			}
		}
	
	}

?>