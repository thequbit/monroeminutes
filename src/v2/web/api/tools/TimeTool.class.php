<?php

	class TimeTool
	{
		private $markTime;
		private $timeDifference;
		
		function __construct()
		{
			$markTime = 0;
			$timeDifference = 0;
		}
		
		function Mark()
		{
			$mtime = microtime(); 
			$mtime = explode(" ",$mtime); 
			$mtime = $mtime[1] + $mtime[0];
			$now = $mtime; 
		
			// calculate difference between marks
			$this->timeDifference = ($now - $this->markTime);
			
			// save new mark time
			$this->markTime = $now;
		}
	
		function TimeTaken()
		{
			// return the difference between marks
			return $this->timeDifference;
		}
	}

?>