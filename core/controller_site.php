<?php
    class controller_site{
        
        public $load = "";
        
        public function __construct() {
            $GLOBALS["controller"] = $this;
            $this->load = new load();
        }
        
    }
    
    /////////// REPLACED BY MAGIC CONTROLLER ///////////
    //class controller extends controller_site{ } // this is added for backwards compatibility as the old versions main controller class was simply "controller"
?>