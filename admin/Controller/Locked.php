<?php
    /**
     * Created by PhpStorm.
     * User: jorn
     * Date: 15-12-17
     * Time: 11:48
     */

    namespace Controller;


    use CMS\Models\Controller\Controller;

    class Locked extends Controller
    {
        public function index($response,$params)
        {
            $this->view('Locked',['shared/locked.php']);
        }
    }