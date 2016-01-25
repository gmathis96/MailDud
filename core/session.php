<?php
class session {

    function __construct() {
        if(!headers_sent() && !isset($_SESSION)){
            session_start();
        }
        foreach ($_COOKIE as $key => $value) {
            if (!isset($_SESSION[$key])) {
                json_decode($string);
                if ((json_last_error() == JSON_ERROR_NONE)) {
                    $_SESSION[$key] = json_decode($value);
                } else {
                    $_SESSION[$key] = $value;
                }
            }
        }
    }

    /***********************************************************************
    *  FUNCTION static::check()
    *  DESCRIPTION: check if a session key exists
    **********************************************************************/
    
    static function check($key) {
        if (is_array($key)) {
            $set = true;
            foreach ($key as $k) {
                if (!session::check($k)) {
                    $set = false;
                }
            }
            return $set;
        } else {
            $key = session::generateSessionKey($key);
            return isset($_SESSION[$key]);
        }
    }

    /***********************************************************************
    *  FUNCTION static::get()
    *  DESCRIPTION: get a session value by key
    **********************************************************************/
    
    static function get($key) {
        if (isset($_SESSION[session::generateSessionKey($key)])) {
            return $_SESSION[session::generateSessionKey($key)];
        } else {
            return false;
        }
    }

    /***********************************************************************
    *  FUNCTION static::set()
    *  DESCRIPTION: set a new session key and value, if TTL is passed
    *  a cookie will also be sent to the browser 
    **********************************************************************/
    
    static function set($key, $value, $ttl = 0) {
        $_SESSION[session::generateSessionKey($key)] = $value;
        if ($ttl !== 0) {
            if (is_object($value) || is_array($value)) {
                $value = json_encode($value);
            }
            setcookie(session::generateSessionKey($key), $value, (time() + $ttl), "/", $_SERVER["HTTP_HOST"]);
        }
    }

    /***********************************************************************
    *  FUNCTION static::kill()
    *  DESCRIPTION: remove a session value by key
    **********************************************************************/
    
    static function kill($key) {
        unset($_SESSION[session::generateSessionKey($key)]);
        if (isset($_COOKIE[session::generateSessionKey($key)])) {
            setcookie(session::generateSessionKey($key), "", (time() - 500000), "/", $_SERVER["HTTP_HOST"]);
        }
    }

    /***********************************************************************
    *  FUNCTION static::endSession()
    *  DESCRIPTION: remove all session variables
    **********************************************************************/
    
    static function endSession() {
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }
        foreach ($_COOKIE as $key => $value) {
            setcookie($key, "", (time() - 500000), "/", $_SERVER["HTTP_HOST"]);
        }
        session_destroy();
    }

    /***********************************************************************
    *  FUNCTION static::generateSessionKey()
    *  DESCRIPTION: generates a key for storing session values
    **********************************************************************/
    
    static function generateSessionKey($key) {
        return base64_encode(md5($key));
    }

}

?>