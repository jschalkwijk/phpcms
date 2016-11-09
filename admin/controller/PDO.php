<?php

use CMS\Models\Controller\Controller;

class PDO extends Controller {
    public function index($params = null){
        $params[] = 2;
        $params[] = 'PDO';
        $this->view('PDO',['pages/single-page.php'],$params);
    }
}
?>