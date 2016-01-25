<?php
    ini_set('session.gc_maxlifetime', 86400);
    session_set_cookie_params(86400);
    include_once 'core/includes.php';
    //error_reporting(E_ALL);
    //ini_set("display_errors", 1);
    $keyStart = 1;
    //break the URL from the query string, ignore the app directory
    $uri = explode("?", str_replace($GLOBALS["configs"]["domain_dir"], "", $_SERVER["REQUEST_URI"]));
    $uri = explode("/", $uri[0]); //split the url by parts
    
    if(strtolower($uri[1]) == "cron"){ //if the first part of the URL equals "cron" then it's an automated task
        $keyStart++;
    }else if(strtolower($uri[1]) == "server"){
        if(strtolower($uri[2]) == "data"){ //server request
            $keyStart += 3;
        }else{
            $keyStart++;
        }
    }else if(strtolower($uri[1]) == "ajax"){
        $keyStart += 2;
    }
    
    $controller = $uri[($keyStart)]; //key start is where we start reading the URL, because of cron jobs (Just controllers, with a different main controller)
    $method = $uri[($keyStart + 1)];
    
    if(!$controller || $controller == ""){ // check if the controller is specified within the URL, if not, we specify it from the configs
        $controller = $GLOBALS["configs"]["mainController"];
    }
    if(!$method || $method == ""){// check if the controller method is specified within the URL, if not, we specify it from the configs
        $method = $GLOBALS["configs"]["mainMethod"];
    }
    
    
    if($keyStart == 1){ //define the controller path based off the key start to tell us if this is a cron task or not
        $controllerPath =  "site/controllers/$controller.php";
    }else if($keyStart == 3){
        $controllerPath =  "site/ajax/$controller.php";
    }else{
        if(strtolower($uri[1]) == "server"){
            if(strtolower($uri[2]) == "data"){
                $controllerPath =  "server/data/$controller.php";
            }else{
                $controllerPath =  "server/$controller.php";
            }
        }else{
            $controllerPath =  "jobs/$controller.php";
        }
    }
    
    if(!file_exists($controllerPath)){ //if the controller file does not exist, direct to the configured 404 error result
        $controllerPath = "site/controllers/{$GLOBALS[configs][errorController]}.php";
        $controller = $GLOBALS["configs"]["errorController"];
        $method = $GLOBALS["configs"]["404method"];
    }
    
    
    include_once $controllerPath; // read our controller 
    
    $controllerClass = new $controller(); //construct our controller
    if(!method_exists($controllerClass, $method)){ //if the controller does not contain the selected method, cancel and call 404 error result
        if(method_exists($controllerClass, "route")){ //if the controller has this functionality built into it, allow it to do its own routing
            $controllerClass->route();
        }else{
            show404();
        }
    }else{
        $controllerClass->$method(); // since everything seems to check out, call the selected controller method, and proceed with execution
    }
?>