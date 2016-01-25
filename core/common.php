<?php

// This file defines all global functions within the Framework
//build our database connection, since common.php is loaded within all page load types
//new database($GLOBALS["configs"]["db"]["hostname"], $GLOBALS["configs"]["db"]["username"], $GLOBALS["configs"]["db"]["password"], $GLOBALS["configs"]["db"]["database"]);
$GLOBALS["dbi"] = new databasei($GLOBALS["configs"]["db"]["hostname"], $GLOBALS["configs"]["db"]["username"], $GLOBALS["configs"]["db"]["password"], $GLOBALS["configs"]["db"]["database"]);
new session(); //call the session class construct to recover cookies and start the session

/***********************************************************************
*  FUNCTION uri()
*  DESCRIPTION: Read the URI into an array, return the requested part
**********************************************************************/

function uri($key) {
    $uri = explode("?", str_replace($GLOBALS["configs"]["domain_dir"], "", $_SERVER["REQUEST_URI"]));
    $uri = explode("/", $uri[0]);
    return $uri[$key];
}

/***********************************************************************
*  FUNCTION randString()
*  DESCRIPTION: Generate a random string with a length and charset
**********************************************************************/

function randString($length, $charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789') {
    $str = '';
    $count = strlen($charset);
    while ($length--) {
        $str .= $charset[mt_rand(0, $count - 1)];
    }
    return $str;
}

/***********************************************************************
*  FUNCTION getNewKey()
*  DESCRIPTION: Generates a random string with a given legnth
**********************************************************************/

function getNewKey($length = 5) {
    $i = 0;
    $string = "";
    while ($i < $length) {
        $string .= ($string == "") ? "" : "-";
        $string .= randString(rand(1, $length));
        $i++;
    }
    return strtoupper($string);
}

/***********************************************************************
*  FUNCTION generate_api_data()
*  DESCRIPTION: Generate API Key And Secret when working with building
*  an API for the BasicMVC application
**********************************************************************/

function generate_api_data() {
    $data["api_key"] = randString(128);
    $data["api_secret"] = randString(64);
    $data["enc_key"] = generateEncKey();
    $data["enc_iv"] = generateEncIv();
    load::smodel("api_info");
    $model = new api_info();
    $data["id"] = $model->create($data["api_key"], $data["api_secret"], $data["enc_key"], $data["enc_iv"]);
    return $data;
}

/***********************************************************************
*  FUNCTION enc()
*  DESCRIPTION: Quick way of encrypting strings
**********************************************************************/

function enc($string) {
    return encrypt($string, $GLOBALS["configs"]["encKey"], $GLOBALS["configs"]["encIV"]);
}

/***********************************************************************
*  FUNCTION dec()
*  DESCRIPTION: Quick way of decrypting strings
**********************************************************************/

function dec($string) {
    return decrypt($string, $GLOBALS["configs"]["encKey"], $GLOBALS["configs"]["encIV"]);
}

/***********************************************************************
*  FUNCTION encrypt()
*  DESCRIPTION: Encrypt a string with a geven Key and IV 256 bits
**********************************************************************/

function encrypt($string, $key, $iv) {
    $data = openssl_encrypt(
            encPad($string, 16), // padded data
            'AES-256-CBC', // cipher and mode
            $key, // secret key
            0, // options (not used)
            $iv                   // initialisation vector
    );
    return $data;
}

/***********************************************************************
*  FUNCTION decrypt()
*  DESCRIPTION: Decrypt a string with a geven Key and IV 256 bits
**********************************************************************/

function decrypt($string, $key, $iv) {
    $data = encUnpad(
            openssl_decrypt(
                    $string, 'AES-256-CBC', $key, 0, $iv
    ));
    return $data;
}

/***********************************************************************
*  FUNCTION encPad()
*  DESCRIPTION: Create padding for the encryption algorithm
**********************************************************************/

function encPad($data, $size) {
    $length = $size - strlen($data) % $size;
    return $data . str_repeat(chr($length), $length);
}

/***********************************************************************
*  FUNCTION encUnpad()
*  DESCRIPTION: Remove padding for the encryption algorithm
**********************************************************************/

function encUnpad($data) {
    return substr($data, 0, -ord($data[strlen($data) - 1]));
}

/***********************************************************************
*  FUNCTION generateEncryptionKey()
*  DESCRIPTION: Generates encryption Key and IV
**********************************************************************/

function generateEncryptionKey() {
    $data["key"] = generateEncKey();
    $data["iv"] = generateEncIv();
    return $data;
}

/***********************************************************************
*  FUNCTION generateEncIv()
*  DESCRIPTION: Generates encryption IV
**********************************************************************/

function generateEncIv($bits = 128) {
    return generateEncKey($bits);
}

/***********************************************************************
*  FUNCTION generateEncKey()
*  DESCRIPTION: Generates encryption Key
**********************************************************************/

function generateEncKey($bits = 256) {
    $length = $bits / 8; // 8 bits in a byte -- Each char in a string is a byte 
    return randString($length);
}

/***********************************************************************
*  FUNCTION cleanURL()
*  DESCRIPTION: Generates clean url from a string
**********************************************************************/

function cleanURL($value) {
    $value = strip_tags($value); //get rid of all html tags
    $value = str_replace(",", " ", $value); // replace commas
    $value = str_replace("\t", " ", $value); //replace tabs
    $value = str_replace("-", " ", $value); //replace hyphens
    $value = str_replace("_", " ", $value); //replace underscores
    $value = str_replace("+", " ", $value); //replace plus
    $value = str_replace("&", " ", $value); //replace ampersands
    $value = str_replace("&amp;", " ", $value); //replace ampersands (HTML ENTITY)
    $value = str_replace("/", " ", $value); //replace forward slashes
    $value = str_replace("\\", " ", $value); //replace back slashes
    $value = preg_replace('!\s+!', ' ', $value); //since we are replacing all of the above with spaces, replace all sequntial white space with one white space charactor
    $value = str_replace(" ", "-", $value); //replace white space charactor with a singe hyphen
    return $value; //return our final value
}

/***********************************************************************
*  FUNCTION thumb()
*  DESCRIPTION: creates a thumbnail URL for a given image URL
**********************************************************************/

function thumb($src, $width = 0, $height = 0, $quality = 80) {
    $url = "/thumb.php?src=$src";
    if ($width != 0) {
        $url .= "&w=$width";
    }
    if ($height != 0) {
        $url .= "&h=$height";
    }
    $url .= "&q=$quality";
    return $url;
}

/***********************************************************************
*  FUNCTION isLoggedIn()
*  DESCRIPTION: Checks if a user is logged in
**********************************************************************/

function isLoggedIn() {
    $check = array("id", "username", "admin", "fname", "lname");
    if (session::check($check)) {
        return true;
    } else {
        return false;
    }
}

/***********************************************************************
*  FUNCTION isLoggedIn()
*  DESCRIPTION: Checks if a user is an administrator
**********************************************************************/

function isAdmin() {
    if (isLoggedIn()) {
        load::smodel("users");
        $user = new users();
        $info = $user->getInfo(session::get("id"));
        if ($info["admin_flag"] == 1) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/***********************************************************************
*  FUNCTION requireLogin()
*  DESCRIPTION: Requires a log in to view current URL
**********************************************************************/

function requireLogin() {
    if (!isLoggedIn()) {
        fwd("/log/in");
    }
}

/***********************************************************************
*  FUNCTION t()
*  DESCRIPTION: outputs a variables content for debugging, 
*  only if the user is a developer or admin
**********************************************************************/

function t($var, $exit = false) {
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
    if ($exit) {
        exit();
    }
}

/***********************************************************************
*  FUNCTION d()
*  DESCRIPTION: outputs a debug backtrace, only if the 
*  user is a developer or admin
**********************************************************************/

function d($exit = false) {
    echo "<pre>";
    var_dump(debug_backtrace());
    echo "</pre>";
    if ($exit) {
        exit();
    }
}

/***********************************************************************
*  FUNCTION redir()
*  DESCRIPTION: redirects user to a different page, uses javascript 
*  fallback if the headers have already been sent
**********************************************************************/
function redir($to, $exit = true) {
    if (headers_sent()) {
        echo "<script>window.location = '$to';</script>";
    } else {
        header("location: $to");
    }
    if ($exit) {
        exit();
    }
}

/***********************************************************************
*  FUNCTION fwd()
*  DESCRIPTION: alias of redir()
**********************************************************************/

function fwd($to, $exit = true) {
    redir($to, $exit);
}

/***********************************************************************
*  FUNCTION show404()
*  DESCRIPTION: shows a 404 error page
**********************************************************************/

function show404() {
    $controller = $GLOBALS["configs"]["errorController"];
    $method = $GLOBALS["configs"]["404method"];
    include_once "site/controllers/$controller.php";
    $controllerClass = new $controller();
    $controllerClass->$method(); // pass the page load to the configured 404 error method
    die();
}

function dlink($rel) {
    return "//" . $GLOBALS["configs"]["domain"] . $GLOBALS["configs"]["domain_dir"] . $rel;
}

/***********************************************************************
*  FUNCTION sendEmail()
*  DESCRIPTION: creates an email from a template and replaces the
*  the given variables with correct data, sends the email
**********************************************************************/

function sendEmail($to, $subject, $template, $variables) {
    if(is_array($to)){
        foreach($to as $key => $value){
            sendEmail($value, $subject, $template, $variables);
        }
    }else{
        $templateString = file_get_contents("site/email_templates/$template.html");
        foreach ($variables as $key => $value) {
            $templateString = str_replace("{" . $key . "}", $value, $templateString);
        }
        //load::slib("Mailgun/Mailgun");
        //$mailgun = new Mailgun\Mailgun($GLOBALS["configs"]["smtp_password"]);
        $message = array(
            'from'    => $GLOBALS["configs"]["smtp_from"].' <'.$GLOBALS["configs"]["smtp_email"].'>',
            'to'      => $to,
            'subject' => $subject,
            'text'    => strip_tags($templateString),
            'html'    => $templateString
        );
        //$mailgun->sendMessage($GLOBALS["configs"]["smtp_domain"], $message);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        //curl_setopt($ch, CURLOPT_USERPWD, 'api:key-6e16726eb3b91126540f6c3b8bf3dd84');
        curl_setopt($ch, CURLOPT_USERPWD, 'api:key-6e16726eb3b91126540f6c3b8bf3dd84');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        //curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v3/leads.plus/messages');
        curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v3/sandbox9ed1561ae21648e9a690cde09aab1080.mailgun.org/messages');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
        json_decode(curl_exec($ch));
        curl_getinfo($ch);
        curl_close($ch);
    }
}

/***********************************************************************
*  FUNCTION sendSMS()
*  DESCRIPTION: Sends a text message through the Twilio API
**********************************************************************/

function sendSMS($to, $message, $from = "409-202-6047") {
    $uri = 'https://api.twilio.com/2010-04-01/Accounts/ACdd21ac4507c4dee834a2c6c3967ef5ad/SMS/Messages';
    $auth = 'ACdd21ac4507c4dee834a2c6c3967ef5ad:d18b397cd1564e7e3b70e1534fada0e0';
    $fields = '&To=' . urlencode($to) .
            '&From=' . urlencode($from) .
            '&Body=' . urlencode($message);
    $res = curl_init();
    curl_setopt($res, CURLOPT_URL, $uri);
    curl_setopt($res, CURLOPT_POST, 3);
    curl_setopt($res, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($res, CURLOPT_USERPWD, $auth);
    curl_setopt($res, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($res);
    //var_dump($result);
    //die();
    return $result;
}

/***********************************************************************
*  FUNCTION calcSidebarRatings()
*  DESCRIPTION: Calculates the sidebar ratings for a user
**********************************************************************/

function calcSidebarRatings() {
    $network = load::smodel("network");
    return $network->getAllRelativeRatings(session::get("id"));
}

/***********************************************************************
*  FUNCTION toUserScore()
*  DESCRIPTION: Converts raw score to percentile rank
**********************************************************************/

function toUserScore($score) {
    if ($score > 100) {
        $score = 100;
    } else if ($score < 1) {
        return 1;
    }
    load::smodel("ranks");
    $ranks = new ranks();
    return $ranks->convert($score);
}

/***********************************************************************
*  FUNCTION getDispositions()
*  DESCRIPTION: Gets a list of available dispositions for user
**********************************************************************/

function getDispositions(){
    load::smodel("users");
    load::smodel("dispositions");
    $dispositions = new dispositions();
    $users = new users();
    $user_id = session::get("id");
    $user = $users->getInfo($user_id);
    if($user["parent_user"]){
        $user_id = $user["parent_user"];
    }
    return $dispositions->getAvail($user_id);
}

/***********************************************************************
*  FUNCTION getDisposition()
*  DESCRIPTION: Gets the name of a defined disposition
**********************************************************************/

function getDisposition($id){
    load::smodel("dispositions");
    $dispositions = new dispositions();
    $disposition = $dispositions->getInfo($id);
    return $disposition["name"];
}

function isPaid(){
    load::smodel("users");
    $users = new users();
    if($users->isPaid(session::get("id"))){
        return true;
    }else if(hasParent()){
        return true;
    }else{
        return false;
    }
}

function getUserGroup($user_id = 0){
    load::smodel("users");
    $users = new users();
    if(!$user_id){
        $user_id = session::get("id");
    }
    $user = $users->getInfo($user_id);
    if($user["parent_user"]){
        $user = $users->getInfo($user["parent_user"]);
    }
    $group = array($user["id"]);
    $children = $users->getChildren($user["id"]);
    foreach($children as $child){
        $group[] = $child["id"];
    }
    return $group;
}

function getParentUsername($user_id){
    load::smodel("users");
    $users = new users();
    $user = $users->getInfo($user_id);
    if($user["parent_user"]){
        $user = $users->getInfo($user["parent_user"]);
    }
    return $user["username"];
}

function getTotalMinutes(){
    load::smodel("users");
    $users = new users();
    $user = $users->getInfo(session::get("id"));
    if($user["parent_user"]){
        $user = $users->getInfo($user["parent_user"]);
    }
    return $user["minutes"];
}

function getTotalMinutesUsed(){
    load::smodel("incoming_calls");
    load::smodel("outgoing_calls");
    $incoming_calls = new incoming_calls();
    $outgoing_calls = new outgoing_calls();
    $user_incoming = $incoming_calls->getByUsers(getUserGroup(session::get("id")));
    $user_outgoing = $outgoing_calls->getByUsers(getUserGroup(session::get("id")));
    $billedFor = 0;
    foreach($user_incoming as $call){
        $billedFor += $call["billed_length"];
    }
    foreach($user_outgoing as $call){
        $billedFor += $call["billed_duration"];
    }
    return $billedFor;
}

function numbertoString($number, $string){
    $chars = str_split(strtolower($string));
    $nums = "";
    foreach($chars as $char){
        switch($char){
            case "a": $nums .= "2"; break 1;
            case "b": $nums .= "2"; break 1;
            case "c": $nums .= "2"; break 1;
            case "d": $nums .= "3"; break 1;
            case "e": $nums .= "3"; break 1;
            case "f": $nums .= "3"; break 1;
            case "g": $nums .= "4"; break 1;
            case "h": $nums .= "4"; break 1;
            case "i": $nums .= "4"; break 1;
            case "j": $nums .= "5"; break 1;
            case "k": $nums .= "5"; break 1;
            case "l": $nums .= "5"; break 1;
            case "m": $nums .= "6"; break 1;
            case "n": $nums .= "6"; break 1;
            case "o": $nums .= "6"; break 1;
            case "p": $nums .= "7"; break 1;
            case "q": $nums .= "7"; break 1;
            case "r": $nums .= "7"; break 1;
            case "s": $nums .= "7"; break 1;
            case "t": $nums .= "8"; break 1;
            case "u": $nums .= "8"; break 1;
            case "v": $nums .= "8"; break 1;
            case "w": $nums .= "9"; break 1;
            case "x": $nums .= "9"; break 1;
            case "y": $nums .= "9"; break 1;
            case "z": $nums .= "9"; break 1;
        }
    }
    //die($nums);
    return str_replace($nums, "<strong>".  strtoupper($string)."</strong>", str_replace("-", "", $number));
}

function hasParent($user = 0){
    if(!$user){
        $user = session::get("id");
    }
    load::smodel("users");
    $users = new users();
    $info = $users->getInfo($user);
    if($info["parent_user"]){
        return true;
    }else{
        return false;
    }
}

?>