<?php

use CMS\Models\Controller\Controller;

class porno extends Controller {
    public function index($params = null){
        $params[] = 3;
        $params[] = 'porno';
        $this->view('porno',['pages/single-page.php'],$params);
    }
}
?>