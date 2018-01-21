<?php
//	use CMS\Models\Files\Folders;
	$dbc = new \CMS\Models\DBC\DBC;
	$user = $data['user'];
?>
<script src="/admin/view/users/users.js"></script>

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
			<form class="medium" enctype="multipart/form-data" method="post" action="#">
				<input type="hidden" name="MAX_FILE_SIZE" value="43500000" />
				<label for="files[]">Choose File(max size: 3.5 MB): </label><br />
				<input type="file" name="files[]" multiple/><br />
				<input type="checkbox" name="public" value="public"/>
				<label for='public'>Public</label>
				<input type="checkbox" name="secure" value="secure"/>
				<label for='secure'>Secure</label>
<!--				--><?php //(!empty($params)) ? Folders::get_albums($user->album_id,$params[1]) : Folders::get_albums(null,null) ;?>
				<button type="submit" name="submit_file">Add File('s)</button>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
			<?php
				(isset($params['id'])) ? $action = ADMIN . 'users/edit/' . $user->user_id: $action = ADMIN . 'users/create';
			?>
			<form id="add-use" method="post" action="<?= $action; ?>">
				<input type="hidden" name="id" value="<?= $user->user_id; ?>"/>
				<input type="hidden" name="old_username" value="<?= $user->username; ?>"/>
				<input type="text" name="username" placeholder="Username"
					   value="<?= $user->username; ?>"/><br/>
				<input type="password" name="password" placeholder="New Password"/><br/>
				<input type="password" name="password_again" placeholder="New Password Again"/><br/>
				<input type="text" name="first_name" placeholder="First name" value="<?= $user->firstName(); ?>"/> <br/>
				<input type="text" name="last_name" placeholder="Last name"
					   value="<?= $user->lastName(); ?>"/> <br/>
				<input type="text" name="email" placeholder="Email" value="<?= $user->mail(); ?>"/> <br/>
				<input type="text" name="function" placeholder="Function"
					   value="<?= $user->position(); ?>"/> <br/>

                <table class="table">
                    <thead>
                    <tr>
                        <th class="text-center" colspan="6">Roles</th>
                    </thead>
                    <tbody>
                    <tr>
                        <?php
                            $i = 1;
                            foreach($data['roles'] as $role){
                                if(isset($data['currentRoles']) && in_array($role->role_id,$data['currentRoles'])) {
                                    echo "<td><input type='checkbox' value='$role->role_id' name='roles[]' checked/></td>";
                                } else {
                                    echo "<td><input type='checkbox' value='$role->role_id' name='roles[]'/></td>";
                                }

                                foreach ($role->permissions as $perm){
                                    $permissionsID[] = $perm->permission_id;
                                }
                                echo "<td><input id='role_$role->role_id' class='$role->name' type='hidden' value='".json_encode($permissionsID)."'/></td>";
                                $permissionsID = [];
                                ?>

                                <td><label><?= ucfirst($role->name) ?></label> </td>
                                <?php if ($i % 4 == 0) echo "</tr><tr>"; $i++;?>
                            <?php } ?>
                    </tr>
                    </tbody>
                </table>
                <table class="table">
                    <thead>
                    <tr>
                        <th class="text-center" colspan="6">Permissions</th>
                    </thead>
                    <tbody>
                    <tr>
                        <?php
                            $i = 1;
                            foreach($data['permissions'] as $permission){
                                if(isset($data['currentPermissions']) && in_array($permission->permission_id,$data['currentPermissions'])) {
                                    echo "<td><input type='checkbox' value='$permission->permission_id' name='permissions[]' checked disabled/></td>";
                                } else if( isset($data['customPermissions']) && in_array($permission->permission_id,$data['customPermissions'])){
                                    echo "<td><input type='checkbox' value='$permission->permission_id' name='permissions[]' checked /></td>";
                                } else {
                                    echo "<td><input type='checkbox' value='$permission->permission_id' name='permissions[]'/></td>";
                                }
                                ?>
                                <td><lable><?= ucfirst($permission->name)?></lable></td>
                                <?php if ($i % 4 == 0) echo "</tr><tr>"; $i++;?>
                            <?php } ?>
                    </tr>
                    </tbody>
                </table>
				<button type="submit" name="submit">Update</button>
			</form>
		</div>
	</div>
</div>
