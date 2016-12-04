<?php
namespace CMS\Models\Categories;

use CMS\Models\DBC\DBC;
use CMS\Core\Model\Model;

class Categories extends Model{
    public $primaryKey = 'category_id';

    public $table = 'categories';

    protected $relations = [
        'users' => 'user_id'
    ];

    protected $joins = [
        'users' => ['username']
    ];

    protected $allowed = [
        'title',
        'description',
        'type',
        'trashed',
        'approved'
    ];


	#SETTERS
	public function setID($id){
		$this->id = $id;
	}

	#GETTERS
	public function get_id(){
		return $this->category_id;
	}

	public function getlink(){
        return preg_replace("/[\s-]+/", "-", $this->title);
    }

	// Called from the controller.
	// First a new contact object needs to be created inside the controller which then is used
	// to call this function. As you can see, it is using the objects data. The objects data is formed
	// by Post values passed to the object inside the controller.

	public function edit(){

		$messages = array();
		$errors = array();
		$output_form = false;

            $this->hidden['user_id'] = $this->user_id;
            $this->patch();
            if(!empty($this->title) && $this->request['confirm'] === 'Yes' ) {
                $this->savePatch();
                $output_form = true;
            } else {
                $errors[] = "You forgot to fill in one or more of the required fields (title).<br />";
            };

			$messages[] = '<p>The category with title was successfully edited.';

		// We return an array which contains value that can be passed from the controller to the view.
		// If the form needs to be outputted, errors or success messages.
		return ['output_form' => $output_form,'messages' => $messages, 'errors' => $errors];
	}

	// Called from the controller.
	// First a new contact object needs to be created inside the controller which then is used
	// to call this function. As you can see, it is using the objects data. The objects data is formed
	// by Post values passed to the object inside the controller.
	// It also updates the category table if a category is created.
	public function add() {
		$errors = array();
		if(!empty($this->title)) {
            $this->hidden['user_id'] = $_SESSION['user_id'];
            print_r($this->request);
            if(!empty($this->title) && !empty($this->type)) {
                $this->save();
                $category_id = $this->connection->lastInsertId();
                echo "Category_id: ".$category_id;
                $messages[] = 'Your post has been added/edited.<br />';
            } else {
                $errors[] = "You forgot to fill in one or more of the required fields (title).<br />";
            };
		} else {
			$errors[] = 'You forgot to type in a category name.';
		}
		// We return an array which contains value that can be passed from the controller to the view.
		// If the form needs to be outputted, errors or success messages.
		return ['errors' => $errors,'category_id' => $category_id,'category_name' => $this->title];
	}
	// Returns a list of options with all the categories that are of the defined type.
	// like type: post, type: product, type: page.
	// We want to automatically have the te current category selected if we update a post
	// This function is called inside a select form type:
	/*
	  <select id="categories" name="cat_name">
		<option name="none" value="None">None</option>
		<?php $category = content_Categories::getSelected($post->getCategory(),'post'); >
	  </select>
	*/

	public static function getSelected($selected_cat,$type) {
		$db = new DBC;
		$dbc = $db->connect();
		try {
            $query = $dbc->prepare("SELECT * FROM categories WHERE trashed = 0 AND type = ?");
			$query->execute([$type]);

			while ($row = $query->fetch()) {
				if ($selected_cat == $row['category_id']) {
					echo '<option value="' . $row['category_id'] . '" selected="selected">' . $row['title'] . '</option>';
				} else {
					echo '<option value="' . $row['category_id'] . '">' . $row['title'] . '</option>';
				}
			}
		} catch (\PDOException $e) {
			echo $e->getMessage();
            exit();
		}
		$db->close();
	}
	//
}
?>