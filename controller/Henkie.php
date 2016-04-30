<?php

class Henkie extends Controller {
    public function index($params = null){
        $params[] = 69;
        $params[] = 'Henkie';
        $content = $this->view('Henkie',['single-page.php'],$params);
    }
    public function Penkie($params = null){
        $params[] = 71;
        $params[] = 'Penkie';
        $content = $this->view('Penkie',['single-page.php'],$params);
    }
}
?>