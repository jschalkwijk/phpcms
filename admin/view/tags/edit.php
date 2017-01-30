<?php
    /**
     * Created by PhpStorm.
     * User: jorn
     * Date: 30-01-17
     * Time: 09:18
     */
    $tag = $data['tag'];
?>

<div class="container">
    <div class="row">
        <div class="col-md-4 push-md-4">
            <div class="center">
            <?php
                if (isset($_POST['submit'])) {
					echo implode(",",$data['messages']);
                }
            ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 push-lg-3">
            <form class="addpost-form" action="<?= '/admin/tags/edit/'.$tag->id() ?>" method="post">
                <input type="text" name="title" id="title" placeholder="Tag Name" value="<?= $tag->title ?>">
                <button type="submit" name="submit">Submit</button>
            </form>
        </div>
    </div>
</div>

