<?php
	$category = $data['category'];
	if (!isset($params[0]) || !isset($params[1])) { 
		echo 'There is no post selected.';
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
			<?php if($output_form){ ?>
			<form id="edit-form" method="post" action="<?= ADMIN."categories/edit-categories/".$category->getID()."/".$category->getTitle(); ?>">
				<input type="hidden" name="id" value="<?= $category->getID();?>"/>
				<input type="text" name="title" value="<?= $category->getTitle();?>"/><br />
				<!-- When page first loads, the hidden field will containe the set title, if the user edits the title. we can change the corresponding post categories. 			 -->
				<input type="hidden" name="old_title" value="<?=  $category->getTitle();?>"/><br />
				<input type="text" name="cat_desc" placeholder="Category Description (max 160 characters)" value="<?= $category->getDescription(); ?>"/><br />
				<p>Are you sure you want to edit the following category?</p>
				<input type="radio" name="confirm" value="Yes" /> Yes
				<input type="radio" name="confirm" value="No" checked="checked" /> No <br />
				<button type="submit" name="submit">Submit</button>
			</form>
			<?php
				}// end if
			?>
		</div>
	</div>
</div>