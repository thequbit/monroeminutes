<?php

    function dprint($text)
    {
        $debug = false;
        
        if($debug)
        {
            $disptext = "[DEBUG] " . $text . "<br>\n";
            
            echo $disptext;
        }
    }

?>
