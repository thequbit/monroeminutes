<?php

    function dprint($text)
    {
        $debug = false;
        
        if($debug)
        {
            $disptext = $text . "</br>";
            
            echo $disptext;
        }
    }

?>
