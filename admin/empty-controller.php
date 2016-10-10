<?php

use Jorn\admin\model\Controller\Controller;

class {title} extends Controller {
    public function index($params = null){
        $params[] = {content};
        $params[] = '{title}';
        $this->view('{title}',['single-page.php'],$params);
    }
}
?>