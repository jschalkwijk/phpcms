<?php
namespace CMS\Models\Categories;

use CMS\Models\DBC\DBC;
use CMS\Core\Model\Model;
use CMS\Models\Posts\Post;
use CMS\Models\Products\Product;
use CMS\Models\Users\Users;

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
        'approved',
        'parent_id'
    ];

	protected $hidden = [
		'user_id',
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
        return $this->owns(Post::class);
    }

    public function products()
    {
        return $this->owns(Product::class);
    }

    public function user()
    {
        return $this->ownedBy(Users::class);
    }

    /**
     * @param $array
     * @return string
     *
     * Takes an array with the first chidren of the Object, then if those children have children
     * it wil cascade over them en create a list output.
     */
    public function tree($array)
    {
        $html = '<ul class="list-group">';

        foreach ($array as $key => $value)
        {
            $html .= '<li class="list-group-item">' . $value->title;
            if (!empty($value->children()))
            {
                $html .= $this->tree($value->children());
            }
            $html .= '</li>';
        }

        $html .= '</ul>' ;

        return $html;
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