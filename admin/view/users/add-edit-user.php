<?php
	use CMS\Models\File\Folders;
	$dbc = new \CMS\Models\DBC\DBC;

	$user = $data['user'];
	
	(isset($params[0]) && isset($params[1])) ? $action = ADMIN.'users/edit-users/'.$user->getID().'/'.$user->getUserName() : $action = ADMIN.'users/add-user';
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
			<form class="medium" enctype="multipart/form-data" method="post" action="<?= $action; ?>">
				<input type="hidden" name="MAX_FILE_SIZE" value="43500000" />
				<label for="files[]">Choose File(max size: 3.5 MB): </label><br />
				<input type="file" name="files[]" multiple/><br />
				<input type="checkbox" name="public" value="public"/>
				<label for='public'>Public</label>
				<input type="checkbox" name="secure" value="secure"/>
				<label for='secure'>Secure</label>
				<?php (!empty($params)) ? Folders::get_albums($user->getAlbumID(),$params[1]) : Folders::get_albums(null,null) ;?>
				<button type="submit" name="submit_file">Add File('s)</button>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
			<?php
				if($output_form){
			?>
			<form id="add-user" method="post" action="<?= $action ;?>">
				<input type="hidden" name="id" value="<?= $user->getID(); ?>"/>
				<input type="text" name="username" placeholder="Username" value="<?= $user->getUserName(); ?>"/><br />
				<input type="password" name="new_password" placeholder="New Password"/><br />
				<input type="password" name="new_password_again" placeholder="New Password Again"/><br />
				<input type="text" name="first_name" placeholder="First name" value="<?= $user->getFirstName(); ?>"/> <br />
				<input type="text" name="last_name" placeholder="Last name" value="<?= $user->getLastName(); ?>"/> <br />
				<input type="text" name="email" placeholder="Email" value="<?= $user->getMail(); ?>"/> <br />
				<input type="text" name="function" placeholder="Function" value="<?= $user->getFunction(); ?>"/> <br />
				<p>Rights</p>
				<input type="radio" name="rights" value="Admin" <?php if($user->getRights() == 'Admin') { echo 'checked="checked"';}?>/>
				<span> Admin | Can add new vacancies, approve and change them. Add/delete/change users and changes user rights/passwords.</span><br />
				<input type="radio" name="rights" value="Content Manager" <?php if($user->getRights() == 'Content Manager') { echo 'checked="checked"';} ?>/>
				<span> Content Manager | Can add new vacancies, approve and change them.</span><br />
				<input type="radio" name="rights" value="Author" <?php if($user->getRights() == 'Author') { echo 'checked="checked"';} ?>/>
				<span> Author | Can add new vacancies, change them. The changes made need approval from either a Admin or Content Manager.</span><br />
				<p>Are you sure you want to edit this user?</p>
				<input type="radio" name="confirm" value="Yes" /> Yes
				<input type="radio" name="confirm" value="No" checked="checked" /> No <br />
				<button type="submit" name="submit">Update</button>
			</form>
			<?php
				}
			?>
		</div>
	</div>
</div>