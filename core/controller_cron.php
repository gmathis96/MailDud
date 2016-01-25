<?php
    class controller_cron{
        
        public $load = "";
        
        public function __construct() {
            $GLOBALS["controller"] = $this;
            $this->load = new load();
        }
        
    }
?>