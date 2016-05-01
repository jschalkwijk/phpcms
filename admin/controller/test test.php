<?php

class test test extends Controller {
    public function index($params = null){
        $params[] = 72;
        $params[] = 'test test';
        $this->view('test test',['single-page.php'],$params);
    }
}
?>