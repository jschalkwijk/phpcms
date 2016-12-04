<?php
	$categories = $data['category'];
	if (!isset($params[0])) {
		echo 'There is no category selected.';
	}
	
	(isset($data['output_form'])) ? $output_form = $data['output_form'] : $output_form = true;
?>
<div class="container">
	<div class="row">
		<div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
			<?php
			if (isset($_POST['submit'])) {
				echo '<div class="container medium">';
					echo implode(",",$data['errors']);
					echo implode(",",$data['messages']);
				echo '</div>';
			}
			?>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
			<?php if($output_form){
                foreach ($categories as $category) {
            ?>
                <form id="edit-form" method="post" action="<?= ADMIN."categories/edit-categories/".$category->get_id()."/".$category->title; ?>">
                    <input type="text" name="title" value="<?= $category->title;?>"/><br />
                    <!-- When page first loads, the hidden field will containe the set title, if the user edits the title. we can change the corresponding post categories. 			 -->
                    <input type="text" name="description" placeholder="Category Description (max 160 characters)" value="<?= $category->description; ?>"/><br />
                    <p>Are you sure you want to edit the following category?</p>
                    <input type="radio" name="confirm" value="Yes" /> Yes
                    <input type="radio" name="confirm" value="No" checked="checked" /> No <br />
                    <button type="submit" name="submit">Submit</button>
                </form>
			<?php }
				}// end if
			?>
		</div>
	</div>
</div>