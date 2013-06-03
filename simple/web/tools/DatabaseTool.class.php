<?php

	require_once("sqlcredentials.php");

	class DatabaseTool
	{
		
		function Connect()
		{
			$mysqli = null;
		
			try
			{
	
				$mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);
				
				if (mysqli_connect_errno())
				{
					error_log( "Connect failed: %s\n" . mysqli_connect_error() );
					die (MYSQL_DATABASE . " Database not found. " . MYSQL_USER);	// TODO: something more elegant than this
				}		
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
				
				$mysqli = null;
			}
			
			return $mysqli;
		}
		
		function Execute($stmt)
		{
		
			$parameters = array();  
			$results = array();  
		
			$stmt->execute();
		
			$meta = $stmt->result_metadata();  

			if( $meta == null || $meta == "" )
			{
				//error_log( "[DB_LAYER] No data returned." );
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
					}  
					
					$results[] = $x;  
				}  

			}
			return $results;  
		}
		
		function Close($mysqli)
		{

			try
			{
				$mysqli->close();
			}
			catch (Exception $e)
			{
				error_log( "Caught exception: " . $e->getMessage() );
			}
		}
	
	}

?>