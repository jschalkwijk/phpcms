<?php
    /**
     * Created by PhpStorm.
     * User: jorn
     * Date: 06-10-17
     * Time: 10:40
     */
	$category = $data['category'];
	$tree = $data['tree'];
	if (!isset($params['id'])) {
        echo 'There is no category selected.';
    }
?>
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
            <?php
                if (isset($_POST['submit'])) {
                    echo '<div class="container medium">';
                    echo implode(",",$data['messages']);
                    echo '</div>';
                }
            ?>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-lg-6 offset-xs-3 offset-sm-3 offset-lg-3">
                <div class="center button-block">
                    <a href="category/create" class="btn btn-primary btn-sm visible-md-block">Add Category</a>
                    <a href="categories/deleted-categories" class="btn btn-primary visible-md-block">Deleted Categories</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 offset-xs-3 offset-sm-3 offset-md-2 offset-lg-3">
                <?php
                    echo $category->title;
                    echo $category->description;
                    echo $category->user()->firstName();
                    echo $category->created_at;
                    echo $category->updated_at;
                    echo "<h3>Tree</h3>";
                    echo $tree;
                ?>
            </div>
        </div>

    </div>
    <br>
    <h3>Related Posts</h3><br>
    <?php
        foreach ($category->posts() as $post){
            echo $post->title."<br>";
        };
    ?>
    <h3>Related Products</h3><br>
    <?php
        foreach ($category->products() as $product){
            echo $product->name."<br>";
        };
    ?>
</div>