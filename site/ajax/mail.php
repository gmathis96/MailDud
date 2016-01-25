<?php
    class mail extends controller{
        
        function getMessages(){
            $box = $_REQUEST["mailbox"];
            $last = $_REQUEST["last"];
            $this->load->model("messages");
            $messages = $this->messages->get($box, $last);
            $out = array();
            foreach($messages as $message){
                $message["action"] = "<a href='/main/viewMessage?id=$message[id]' target='_BLANK'>View</a>";
                $out[] = $message;
            }
            $this->out($out);
        }
        
    }
?>