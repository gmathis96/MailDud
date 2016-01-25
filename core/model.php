<?php
    // this class just links the model variable to the database instance from the connection that the controller already made
    // this model is built to be the model object that is extended by all other models, so only one database connection is made
    class model{
        
        public $model = "";
        
        function __construct() {
            $this->model = $GLOBALS["dbi"];
        }
        
        function prepare($stmt, $values){
            if(is_array($values)){
                foreach($values as $value){
                    $value = mysql_real_escape_string($value);
                    $pos = strpos($stmt,"?");
                    if ($pos !== false) {
                        $stmt = substr_replace($stmt,"'$value'",$pos,strlen("?"));
                    }
                }
            }else{
                $values = mysql_real_escape_string($values);
                $stmt = str_replace("?", "'$values'", $stmt);
            }
            return mysql_query($stmt);
        }
        
    }
?>