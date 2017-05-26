<?php

    /**
     * Created by PhpStorm.
     * User: jorn
     * Date: 15-03-17
     * Time: 11:48
     */

    use CMS\Models\Controller\Controller;
    class Test extends Controller
    {
        public function index($response,$params = null)
        {
            $this->view('Test',['test/test.php'],$params);
        }
    }