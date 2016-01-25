<?php
    class messagePurge extends controller{
        
        function index(){
            $this->load->model("messages");
            $this->messages->purge();
        }
        
    }
?>