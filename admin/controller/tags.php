<?php
    /**
     * Created by PhpStorm.
     * User: jorn
     * Date: 11-01-17
     * Time: 16:11
     */
    use CMS\Models\Controller\Controller;
    use CMS\Models\Tag\Tag;
    class Tags extends Controller{

        public function index($params = null)
        {
            $tags = Tag::all();
            $this->view(
                'Tags',
                [
                    'tags/tags.php'
                ],
                $params,[
                'tags' => $tags
                ]
            );
        }
    }