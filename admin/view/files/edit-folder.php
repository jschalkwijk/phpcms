<?php
	$folder = $data['folder'];
	$folders = $data['folders'];
	if (!isset($params['id'])) {
        echo 'There is no folder selected.';
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
    <div class="row">
        <div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
            <form id="edit-form" method="post" action="<?= ADMIN."folders/edit/".$folder->get_id(); ?>">
                <input type="text" name="name" placeholder="Name" value="<?= $folder->name;?>"/><br />
                <!-- When page first loads, the hidden field will containe the set name, if the user edits the name. we can change the corresponding post folders. 			 -->
                <select id="parent" name="parent_id">
                    <?php
                        if($folder->parent_id == 0){
                            echo '<option value="0" selected>None</option>';
                        }
                        foreach($folders as $f) {
                            if($folder->parent_id == $f->get_id()){
                                echo  '<option value="'. $f->get_id().'" selected>'.$f->name.'</option>';
                            }
                            else {
                                echo  '<option value="'. $f->get_id().'">'.$f->name.'</option>';
                            }
                        }
                    ?>
                </select>
                <p>Are you sure you want to edit the following folder?</p>
                <input type="radio" name="confirm" value="Yes" /> Yes
                <input type="radio" name="confirm" value="No" checked="checked" /> No <br />
                <button type="submit" name="submit">Submit</button>
            </form>
        </div>
    </div>
    <h3>Tree</h3><br>
    <?php
//        echo $data['tree'];
    ?>

</div>