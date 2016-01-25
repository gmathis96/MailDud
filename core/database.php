<?php
    class database{
        
        function __construct($host, $user, $pass, $db) {
            mysql_connect($host, $user, $pass);
            mysql_select_db($db);
            $GLOBALS["db"] = $this;
        }
        
    }
?>