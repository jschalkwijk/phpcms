<?php

use CMS\Models\Controller\Controller;

class {title} extends Controller {
    public function index($params = null){
        $params[] = {content};
        $params[] = '{title}';
        $this->view('{title}',['pages/single-page.php'],$params);
    }
}
?>