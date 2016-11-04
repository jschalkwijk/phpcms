<?php

use CMS\Models\Controller\Controller;
use CMS\Models\Content\Content;
use CMS\Models\Content\Pages\Page;
use CMS\Models\Content\Posts\Post;
use CMS\Models\Actions\UserActions;

class Pages extends Controller
{

    use UserActions;

    public function index($params = null)
    {
        $pages = Content::fetchAll('pages', 0);
        $action = ADMIN . 'pages';
        // Post requests need to be handled first! Then load the page, otherwise you will get the headers already sent error.
        $this->UserActions('pages');
        // view takes: page_title,[array of view files],params from the router,array of data from model
        $view = ['search' => ADMIN . 'search-page.php', 'actions' => ADMIN . 'manage_content.php'];
        $this->view(
            'pages',
            ['pages/pages.php'],
            $params,
            [
                'pages' => $pages,
                'action' => $action,
                'trashed' => 0,
                'js' => [JS . 'checkAll.js']
            ]
        );
    }

    public function AddPage($params = null)
    {
        if (isset($_POST['submit'])) {
            $page = new Page($_POST['page_title'], $_POST['page_desc'], null, $_POST['page_content'], $_SESSION['username'], 'pages');
            $add = $page->addPage($_POST['back-end'], $_POST['front-end'], $_POST['sub_page']);
            $this->view(
                'Add Page',
                ['pages/add-edit-page.php'],
                $params,
                [
                    'output_form' => $add['output_form'],
                    'page' => $page,
                    'messages' => $add['messages']
                ]
            );
        } else {
            $page = new Post(null, null, null, null, null, 'pages');
            $this->view(
                'Add Page',
                ['pages/add-edit-page.php'],
                $params,
                ['page' => $page]
            );
        }
    }

    public function DeletedPages($params = null)
    {
        $action = ADMIN . 'pages/deleted-pages';
        $delete = $this->UserActions('pages');
        $pages = Content::fetchAll('pages', 1);
        $this->view(
            'Deleted Pages',
            ['pages/pages.php'],
            $params,
            [
                'pages' => $pages,
                'action' => $action,
                'trashed' => 1,
                'messages' => $delete['messages'],
                'js' => [JS . 'checkAll.js']
            ]
        );

    }

    public function EditPages($params = null)
    {
        if (isset($_POST['submit'])) {
            $page = new Page($_POST['page_title'], $_POST['page_desc'], null, $_POST['page_content'], $_SESSION['username'], 'pages');
            $edit = $page->addPage($_POST['back-end'], $_POST['front-end'], $_POST['sub_page'], $_POST['id']);
            $this->view(
                'Edit Page',
                ['pages/add-edit-post.php'],
                $params,
                [
                    'page' => $page,
                    'output_form' => $edit['output_form'],
                    'messages' => $edit['messages']
                ]
            );
        } else {
            $page = Content::fetchSingle('pages', $params[0]);
            $this->view(
                'Edit Page',
                ['pages/add-edit-page.php'],
                $params,
                [
                    'page' => $page,
                    'output_form' => true
                ]
            );
        }

    }

}

?>