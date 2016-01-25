<?php
    class messages extends model{
        
        function create($to, $from, $subject, $message){
            $this->model->query("INSERT INTO `messages`(`to`,`from`,`subject`,`message`)"
                    . " VALUES(?,?,?,?)", array($to, $from, $subject, $message));
            return $this->model->insert_id();
        }
        
        function get($box, $last){
            $this->model->query("SELECT * FROM `messages` WHERE `to`=? AND `id`>?", array($box, $last));
            return $this->model->fetch_all_kv();
        }
        
        function getInfo($id){
            $this->model->query("SELECT * FROM `messages` WHERE `id`=?", array($id));
            return $this->model->fetch_assoc();
        }
        
        function purge(){
            $this->model->query("DELETE FROM `messages` WHERE `timestamp`<?", array(date("Y-m-d H:i:s", strtotime("-10 minutes"))));
        }
        
    }
?>