<?php
    class main extends controller{
        
        function index(){
            $this->load->view("main/index");
        }
        
        function mail(){
            $data["address"] = $_REQUEST["username"]."@maildud.com";
            $this->load->view("main/mail", $data);
        }
        
        function viewMessage(){
            $id = $_REQUEST["id"];
            $this->load->model("messages");
            $this->load->lib("mailparse");
            $message = $this->messages->getInfo($id);
            $mailparse = new mailparse($message["message"]);
            $data["subject"] = $mailparse->getSubject();
            $data["to"] = $mailparse->getTo()[0];
            $data["from"] = $message["from"];
            if($mailparse->getHTMLBody()){
                $data["message"] = $mailparse->getHTMLBody();
            }else{
                $data["message"] = str_replace("\n", "<br />", $mailparse->getPlainBody());
            }
            $this->load->view("main/viewMessage", $data);
        }
        
    }
?>