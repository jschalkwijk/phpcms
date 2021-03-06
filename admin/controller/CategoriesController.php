<?php
    namespace Controller;
use CMS\Models\Controller\Controller;
use CMS\Models\Actions\UserActions;
use \CMS\Models\Categories\Categories as Cat;

class CategoriesController extends Controller
{

    use UserActions;

    public function index($response,$params = null)
    {
        $categories = Cat::allWhere(['trashed'=>0]);
        $this->UserActions($categories[0]);

        $messages = [];

        if (isset($_POST['submit'])) {
            $category = new Cat($_POST);
            if(!empty($category->title)) {
                $category->user_id = $this->currentUser;
                print_r($category->request);
                if(!empty($category->title) && !empty($category->type)) {
                    $category->save();
                    $messages[] = 'Your post has been added/edited.<br />';
                } else {
                    $messages[] = "You forgot to fill in one or more of the required fields (title).<br />";
                };
            } else {
                $messages[] = 'You forgot to type in a category name.';
            }
        }

        $view = ['actions' => 'manage_content.php'];
        $this->view(
            'Categories',
            ['categories/categories.php'],
            $params,
            [
                'categories' => $categories,
                'view' => $view,
                'messages' => $messages,
                'trashed' => 0
            ]
        );
    }

    public function show($response, $params = null)
    {
        $category = Cat::one($params['id']);
        $tree = $category->tree($category->children());

        $this->view(
            $category->name,
            ['categories/show.php'],
            $params,
            [
                'category' => $category,
                'tree' => $tree,
                'trashed' => 0
            ]
        );
    }
    //
    public function deleted($response,$params = null)
    {
        $categories = Cat::allWhere(['trashed'=>1]);
        if(!empty($categories)) {
            $this->UserActions($categories[0]);
        }
        $view = ['actions' => 'view/shared/manage-content.php'];
        $this->view(
            'Categories',
            ['categories/categories.php'],
            $params,
            [
                'categories' => $categories,
                'view' => $view,
                'trashed' => 1
            ]
        );

    }

    //
    public function edit($response,$params = null)
    {
        $category = Cat::one($params['id']);
        $categories = Cat::all();
        $messages = [];
        $tree = $category->tree($category->children());
        if (isset($_POST['submit'])) {
            $category->request = $_POST;
            $category->user_id = $this->currentUser;

            $category->patch();
            if(!empty($category->title) && $category->confirm === 'Yes' ) {
                $category->savePatch();
                $messages[] = '<p>The category with title was successfully edited.';
                header("Location: ".ADMIN."categories");
            } else {
                $messages[] = "You forgot to fill in one or more of the required fields (title).<br />";
            };
        }

        $this->view(
            'Edit Categories',
            ['categories/edit-categories.php'],
            $params,
            [
                'category' => $category,
                'categories' => $categories,
                'tree' => $tree,
                'messages' => $messages,
            ]
        );

    }
}

?>