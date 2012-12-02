<?php

    function dprint($text)
    {
        $debug = true;
        
        if($debug)
        {
            $disptext = $text . "</br>";
            
            echo $disptext;
        }
    }

?>
