<?php
    /**
     * Created by PhpStorm.
     * User: jorn
     * Date: 15-12-17
     * Time: 10:36
     */

    namespace CMS\Models\Support;

    class Error
    {
        public $name = 'errors';

        public function __construct($errors = [])
        {
            if (!Session::exists($this->name)) {
                Session::set($this->name, $errors);
            }
            return $this;
        }

        public function push($errors = [])
        {
            $errors = (!is_array($errors)) ? [$errors] : $errors;
            if(Session::exists($this->name)){
                foreach ($errors as $error){
                    Session::get($this->name)[] = $error;
                }
            }
        }

        public function errors()
        {
            if (Session::exists($this->name)){
                $message = Session::get($this->name);
                Session::unset($this->name);
                return $message;
            }
        }
    }