<?php

use CMS\model\Controller\Controller;

class NameSpees extends Controller {
    public function index($params = null){
        $params[] = 76;
        $params[] = 'NameSpees';
        $this->view('NameSpees',['single-page.php'],$params);
    }
}
?>