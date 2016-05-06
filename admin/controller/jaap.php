<?php

class jaap extends Controller {
    public function index($params = null){
        $params[] = 73;
        $params[] = 'jaap';
        $this->view('jaap',['single-page.php'],$params);
    }
}
?>