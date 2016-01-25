<?php
    class load{
        
        /***********************************************************************
        *  FUNCTION model()
        *  DESCRIPTION: load model, add an instance of the model to the 
        *  current controller instance, and return the model instance
        **********************************************************************/
        
        function model($modelName){
            include_once "site/models/$modelName.php";
            $GLOBALS["controller"]->$modelName = new $modelName();
            return $GLOBALS["controller"]->$modelName;
        }
        
        /***********************************************************************
        *  FUNCTION lib()
        *  DESCRIPTION: load lib, add an instance of the lib to the current 
        *  controller instance, and return the lib instance
        **********************************************************************/
        
        function lib($libName){
            include_once "site/libs/$libName.php";
            //$GLOBALS["controller"]->$libName = new $libName();
            //return $GLOBALS["controller"]->$libName;
        }
        
        /***********************************************************************
        *  FUNCTION view()
        *  DESCRIPTION: load a view, dynamically place variables within the 
        *  view's local scope based off the variables array passed down
        **********************************************************************/
        
        function view($viewName, $variables = array()){
            foreach($variables as $key => $value){
                $$key = $value;
            }
            include "site/views/$viewName.php";
        }
        
        /***********************************************************************
        *  FUNCTION static::smodel()
        *  DESCRIPTION: load a model, return the referance to the constructor
        **********************************************************************/
        
        static function smodel($modelName){
            include_once "site/models/$modelName.php";
            return new $modelName();
        }
        
        /***********************************************************************
        *  FUNCTION static::slib()
        *  DESCRIPTION: load a lib, return the referance to the constructor
        **********************************************************************/
        
        static function slib($libName){
            include_once "site/libs/$libName.php";
            //return new $libName();
        }
        
        /***********************************************************************
        *  FUNCTION static::sview()
        *  DESCRIPTION: load a view, dynamically place variables within the 
        *  view's local scope based off the variables array passed down
        **********************************************************************/
        
        static function sview($viewName, $variables = array()){
            foreach($variables as $key => $value){
                $$key = $value;
            }
            include "site/views/$viewName.php";
        }
        
        /***********************************************************************
        *  FUNCTION static::handlebar()
        *  DESCRIPTION: load a handlebar within the current veiw, define
        *  javascript settings for the template 
        **********************************************************************/
        
        static function handlebar($file, $id, $variables = array()){
            foreach($variables as $key => $value){
                $$key = $value;
            }
            echo "<script id='$id' type='text/x-handlebars-template'>";
            include "site/views/$file.handlebar.php";
            echo "</script>";
        }
        
    }
?>