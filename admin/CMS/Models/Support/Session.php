<?php

    /**
     * Created by PhpStorm.
     * User: jorn
     * Date: 15-12-17
     * Time: 07:59
     */

    namespace CMS\Models\Support;

    class Session 
    {
        
        public static function set($name,$value)
        {
            // Create new session ['default']
            if(!isset($_SESSION[$name])){
                $_SESSION[$name] = $value;
            }
        }

        public static function get($name)
        {
            // Get a product by it's ID, if it exists.
            if(!Session::exists($name)) {
                return null;
            }
            return $_SESSION[$name];
        }

        public static function exists($name)
        {
            // Check if a product is in the current name session
            // $_SESSION['default']['10']
            return isset($_SESSION[$name]);
        }

        public static function all()
        {
            // returns all the products in the name session as array
            return $_SESSION;
        }

        public static function unset($name)
        {
            // Remove a product from the session by it's ID, if it exist
            if(Session::exists($name)){
                unset($_SESSION[$name]);
            }
        }

        public static function clear($name){
            // Remove entire name session by it's name
            unset($_SESSION[$name]);
        }

        public static function count(){
            // count all name items in the session
            return count(Session::all());
        }

        public static function flash($name,$message = null)
        {

            (isset($message))? Flash::set($name,$message) : $message = Flash::message($name);
            return $message;
//            if(!Session::exists($name)){
//                Session::set($name,$message);
//            }

//            $message = Session::get($name);
//            Session::unset($name);

        }

    }