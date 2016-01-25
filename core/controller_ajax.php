<?php
class controller_ajax {

    public $load = "";

    public function __construct() {
        $GLOBALS["controller"] = $this;
        $this->load = new load();
    }

    /***********************************************************************
    *  FUNCTION out()
    *  DESCRIPTION: this is the output function of the ajax controller, 
    *  it will detect if the output needs to be json or xml based off the 
    *  URL. If the content type is not defined and the content is an 
    *  array or object it will output as json, otherwise it will 
    *  be a raw output
    **********************************************************************/
    function out($content = array(), $header = null) {
        if (strtolower(uri(2)) == "xml") {
            header('Content-type: text/xml');
            echo $this->array2xml($content);
        } else if(strtolower(uri(2)) == "json") {
            header('Content-Type: application/json');
            echo json_encode($content);
        }else{
            if(is_array($content) || is_object($content)){
                header('Content-Type: application/json');
                echo json_encode($content);
            }else{
                if(!is_null($header)){
                    header('Content-Type: '.$header);
                }
                echo $content;
            }
        }
    }

    /***********************************************************************
    *  FUNCTION array2xml()
    *  DESCRIPTION: converts an array to json, this array can be as 
    *  many nodes deep as needed.
    **********************************************************************/
    function array2xml($array, $xml = false) {
        if ($xml === false) {
            $xml = new SimpleXMLElement('<root/>');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $key = (is_numeric($key)) ? "node".$key : "$key";
                $this->array2xml($value, $xml->addChild($key));
            } else {
                $key = (is_numeric($key)) ? "node".$key : "$key";
                $xml->addChild($key, $value);
            }
        }
        return $xml->asXML();
    }

}

?>