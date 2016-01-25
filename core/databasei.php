<?php

class databasei {

    private $obj;
    private $result = null;
    public $current_field = "";
    public $lengths = "";
    public $num_rows = "";

    function __construct($host, $username, $password, $database = null) {
        if (is_null($database)) {
            $this->obj = new mysqli($host, $username, $password);
        } else {
            $this->obj = new mysqli($host, $username, $password, $database);
        }
    }

    
    /***********************************************************************
    *  FUNCTION changeDB()
    *  DESCRIPTION: tells the MySQLi extention which database to use
    **********************************************************************/
    function changeDB($db) {
        $this->obj->select_db($db);
    }

    /***********************************************************************
    *  FUNCTION refValues()
    *  DESCRIPTION: takes an array and returns it's memory referances
    **********************************************************************/
    
    function refValues($arr) {
        if (strnatcmp(phpversion(), '5.3') >= 0) { //Reference is required for PHP 5.3+
            $refs = array();
            foreach ($arr as $key => $value)
                $refs[$key] = &$arr[$key];
            return $refs;
        }
        return $arr;
    }

    /***********************************************************************
    *  FUNCTION query()
    *  DESCRIPTION: function will accept a normal mysqli query or a 
    *  mysqli prepared statement. This will execute the query.
    **********************************************************************/
    
    function query($query, $args = null) {
        if ($this->result) {
            //var_dump($this->result);
            //$this->result->free();
        }
        if (is_null($args)) {
            $this->result = $this->obj->query($query);
            $this->current_field = $this->result->current_field;
            $this->lengths = $this->result->lengths;
            $this->num_rows = $this->result->num_rows;
            return $this->result;
        } else {
            
            if ($stmt = $this->obj->prepare($query)) {
                
                $datatypes = "";
                foreach ($args as $value) {
                    if (is_int($value)) { // int
                        $datatypes .= "i";
                    } else if (is_double($value)) { // double
                        $datatypes .= "d";
                    } else if (is_string($value)) { //string
                        $datatypes .= "s";
                    } else { // blob
                        $datatypes .= "b";
                    }
                }
                
                array_unshift($args, $datatypes);
                //die(var_dump($args));
                //if($stmt->bind_param($datatypes, $args)){
                if (call_user_func_array(array($stmt, 'bind_param'), $this->refValues($args))) {
                    
                    $stmt->execute();
                    $this->result = $stmt->get_result();
                    if ($this->result){
                        
                        $this->current_field = $this->result->current_field;
                        $this->lengths = $this->result->lengths;
                        $this->num_rows = $this->result->num_rows;
                    } else {
                        
                        $this->current_field = "";
                        $this->lengths = 0;
                        $this->num_rows = 0;
                    }
                    $this->error = $stmt->error;
                    return $this->result;
                } else {
                    
                    $this->current_field = "";
                    $this->lengths = 0;
                    $this->num_rows = 0;
                    return false;
                }
            } else {
                $this->current_field = "";
                $this->lengths = 0;
                $this->num_rows = 0;
                return false;
            }
        }
    }

    /***********************************************************************
    *  FUNCTION data_seek()
    *  DESCRIPTION: Alias of MySQLi data_seek():
    **********************************************************************/
    
    function data_seek($offset = 0) {
        return $this->result->data_seek($offset);
    }

    /***********************************************************************
    *  FUNCTION fetch_all()
    *  DESCRIPTION: Alias of MySQLi fetch_all():
    **********************************************************************/
    
    function fetch_all() {
        return $this->result->fetch_all();
    }

    /***********************************************************************
    *  FUNCTION fetch_all_kv()
    *  DESCRIPTION: Returns an associative array of all returned rows
    *  from a query 
    **********************************************************************/
    
    function fetch_all_kv() {
        $out = array();
        while ($row = $this->result->fetch_assoc()) {
            $out[] = $row;
        }
        return $out;
    }

    /***********************************************************************
    *  FUNCTION fetch_array()
    *  DESCRIPTION: Alias of MySQLi fetch_array():
    **********************************************************************/
    
    function fetch_array() {
        $this->result->fetch_array();
    }

    /***********************************************************************
    *  FUNCTION fetch_assoc()
    *  DESCRIPTION: Alias of MySQLi fetch_assoc():
    **********************************************************************/
    
    function fetch_assoc() {
        return $this->result->fetch_assoc();
    }

    /***********************************************************************
    *  FUNCTION fetch_field_direct()
    *  DESCRIPTION: Alias of MySQLi fetch_field_direct():
    **********************************************************************/
    
    function fetch_field_direct($field) {
        return $this->result->fetch_field_direct($field);
    }
    
    /***********************************************************************
    *  FUNCTION fetch_field()
    *  DESCRIPTION: Alias of MySQLi fetch_field():
    **********************************************************************/

    function fetch_field() {
        return $this->result->fetch_field();
    }

    /***********************************************************************
    *  FUNCTION fetch_fields()
    *  DESCRIPTION: Alias of MySQLi fetch_fields():
    **********************************************************************/
    
    function fetch_fields() {
        return $this->result->fetch_fields();
    }
    
    /***********************************************************************
    *  FUNCTION fetch_object()
    *  DESCRIPTION: Alias of MySQLi fetch_object():
    **********************************************************************/

    function fetch_object($class_name = "stdClass", $params = null) {
        if (is_null($params)) {
            return $this->result->fetch_object($class_name);
        } else {
            return $this->result->fetch_object($class_name, $params);
        }
    }
    
    /***********************************************************************
    *  FUNCTION fetch_row()
    *  DESCRIPTION: Alias of MySQLi fetch_row():
    **********************************************************************/

    function fetch_row() {
        $this->result->fetch_row();
    }

    /***********************************************************************
    *  FUNCTION field_seek()
    *  DESCRIPTION: Alias of MySQLi field_seek():
    **********************************************************************/
    
    function field_seek($field) {
        return $this->result->field_seek($field);
    }
    
    /***********************************************************************
    *  FUNCTION insert_id()
    *  DESCRIPTION: Alias of MySQLi insert_id():
    **********************************************************************/
    
    function insert_id(){
        return $this->obj->insert_id;
    }
    
    function __destruct() {
        $this->obj->close();
    }

}

?>