<?php

use CMS\Models\Controller\Controller;
use CMS\Models\Pages\Page;
use CMS\Models\Actions\UserActions;

class Pages extends Controller
{

    use UserActions;

    public function index($params = null)
    {
        $pages = Page::all(0);
        $action = ADMIN . 'pages';
        // Post requests need to be handled first! Then load the page, otherwise you will get the headers already sent error.
        $this->UserActions($pages[0]);
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
        if (!isset($_POST['submit'])) {
            $page = new Page();
            $this->view(
                'Add Page',
                ['pages/add-edit-page.php'],
                $params,
                ['page' => [$page]]
            );
        } else {
            $page = new Page($_POST);
            $add = $page->add();
            $this->view(
                'Add Page',
                ['pages/add-edit-page.php'],
                $params,
                [
                    'output_form' => $add['output_form'],
                    'page' => [$page],
                    'messages' => $add['messages']
                ]
            );
        }
    }

    public function DeletedPages($params = null)
    {
        $action = ADMIN . 'pages/deleted-pages';
        $delete = $this->UserActions('pages');
        $pages = Page::all(1);
        $this->UserActions($pages[0]);
        $this->view(
            'Deleted Pages',
            ['pages/pages.php'],
            $params,
            [
                'pages' => [$pages],
                'action' => $action,
                'trashed' => 1,
                'messages' => $delete['messages'],
                'js' => [JS . 'checkAll.js']
            ]
        );

    }

    public function EditPages($params = null)
    {
        $page = Page::single($params[0]);
        if (!isset($_POST['submit'])) {
            $this->view(
                'Edit Page',
                ['pages/add-edit-page.php'],
                $params,
                [
                    'page' => $page,
                    'output_form' => true
                ]
            );
        } else {
//            $page = new Page($_POST);
//            $page->page_id = $params[0];
            $page = $page[0];
            $page->request = $_POST;
            $edit = $page->add();
            $this->view(
                'Edit Page',
                ['pages/add-edit-page.php'],
                $params,
                [
                    'page' => [$page],
                    'output_form' => $edit['output_form'],
                    'messages' => $edit['messages']
                ]
            );
        }

    }

}

?>