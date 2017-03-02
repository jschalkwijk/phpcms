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
	#Relations
    public function posts()
    {
        return $this->owns('CMS\Models\Posts\Post');
    }

    public function products()
    {
        return $this->owns('CMS\Models\Products\Product');
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