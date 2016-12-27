<?php

use CMS\Models\Controller\Controller;

class ccc extends Controller {
    public function index($params = null){
        $params[] = 82;
        $params[] = 'ccc';
        $this->view('ccc',['pages/single-page.php'],$params);
    }
}
?>