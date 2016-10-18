<script type="text/javascript" src="<?php echo ADMIN."/vendor/tinymce/tinymce/tinymce.min.js"; ?>"></script>
<script type="text/javascript">
tinymce.init({
    selector: "textarea",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});
</script>
<?php

	$contact = $data['contact'];
	
	if (!isset($params[0]) || !isset($params[1])) { 
		echo 'There is no contact selected.';
	}
	(isset($params[0]) && isset($params[1])) ? $action = ADMIN.'contacts/edit-contact/'.$contact->getID().'/'.$contact->getFirstName() : $action = ADMIN.'contacts/add-contact';
	(isset($data['output_form'])) ? $output_form = $data['output_form'] : $output_form = true;
	
	if (isset($_POST['submit'])) {
		echo '<div class="container medium">';
			echo implode(",",$data['errors']);
			echo implode(",",$data['messages']);
		echo '</div>';
	}
?>
	<div class="container small">
		<form class="small" enctype="multipart/form-data" method="post" action="<?php echo $action; ?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="43500000" />
		<label for="files[]">Choose File(max size: 3.5 MB): </label><br />
		<input type="file" name="files[]" multiple/><br />
		<input type="checkbox" name="public" value="public"/>
		<label for='public'>Public</label>
		<input type="checkbox" name="secure" value="secure"/>
		<label for='secure'>Secure</label>
		<input type="hidden" name="album_name" value="<?php echo $_SESSION['username']."'s Contacts";?>"/>
		<button type="submit" name="submit_file">Add File('s)</button>
		</form>
	</div>
<?php
	if ($output_form){ ?>
		<div class="container large">
			<form class="backend-form"method="post" action="<?php echo $action; ?>">
				<input type="hidden" name="id" value="<?php echo $contact->getID();?>"/>
				<input type="text" name="first_name" placeholder="First Name" value="<?php echo $contact->getFirstName(); ?>"/><br />
				<input type="text" name="last_name" placeholder="Last Name" value="<?php echo $contact->getLastName(); ?>"/><br />
				<input type="tel" name="phone_1" placeholder="Phone 1" value="<?php echo $contact->getPhone1(); ?>"/><br />
				<input type="tel" name="phone_2" placeholder="Phone 2" value="<?php echo $contact->getPhone2(); ?>"/><br />
				<input type="email" name="email_1" placeholder="E-mail 1" value="<?php echo $contact->getMail1(); ?>"/><br />
				<input type="email" name="email_2" placeholder="E-mail 2" value="<?php echo $contact->getMail2(); ?>"/><br />
				<input type="date" name="dob" placeholder="dob" value="<?php echo $contact->getDOB(); ?>"/><br />
				<input type="text" name="street" placeholder="Street" value="<?php echo $contact->getStreet(); ?>"/><br />
				<input type="number" name="street_num" placeholder="Street Num" value="<?php echo $contact->getStreetNum(); ?>"/>
				<input type="number" name="street_num_add" placeholder="Add" value="<?php echo $contact->getStreetNumAdd(); ?>"/><br />
				<input type="" name="zip" placeholder="Zip/Postal" value="<?php echo $contact->getZip(); ?>"/><br />
				<p>Personal Notes</p>
				<textarea name="notes" placeholder="Notes"><?php echo $contact->getNotes(); ?></textarea>		
				<button type="submit" name="submit">Submit</button><br />
			</form>	
		</div>

<?php } ?>