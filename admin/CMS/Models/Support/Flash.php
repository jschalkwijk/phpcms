<?php
    /**
     * Created by PhpStorm.
     * User: jorn
     * Date: 15-12-17
     * Time: 08:20
     */

    namespace CMS\Models\Support;

    class Flash
    {
        public static function set($name,$message)
        {
            if(!Session::exists($name)){
                Session::set($name,$message);
            }
        }

        public static function unset($name)
        {
            if(Session::exists($name)){
                Session::unset($name);
            }
        }

        public static function message($name)
        {
            if (Session::exists($name)){
                $message = Session::get($name);
                Session::unset($name);
                return $message;
            }
            return null;
        }
    }