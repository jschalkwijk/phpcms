<script type="text/javascript" src="<?= ADMIN . "/vendor/tinymce/tinymce/tinymce.min.js"; ?>"></script>
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
(isset($data['output_form'])) ? $output_form = $data['output_form'] : $output_form = true;
?>
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
            <?php
            if (isset($_POST['submit'])) {
                echo '<div class="container medium">';
                echo implode(",", $data['errors']);
                echo implode(",", $data['messages']);
                echo '</div>';
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
            <form class="small" enctype="multipart/form-data" method="post" action="<?= $action; ?>">
                <input type="hidden" name="MAX_FILE_SIZE" value="43500000"/>
                <label for="files[]">Choose File(max size: 3.5 MB): </label><br/>
                <input type="file" name="files[]" multiple/><br/>
                <input type="checkbox" name="public" value="public"/>
                <label for='public'>Public</label>
                <input type="checkbox" name="secure" value="secure"/>
                <label for='secure'>Secure</label>
                <input type="hidden" name="album_name" value="<?= $_SESSION['username'] . "'s Contacts"; ?>"/>
                <button type="submit" name="submit_file">Add File('s)</button>
            </form>
        </div>
    </div>
        <div class="row">
            <div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
                <?php $action = ADMIN . 'contacts/create'; ?>
                <form id="create-form" method="post" action="<?= $action; ?>">
                    <input type="hidden" name="contact_id" value="<?= $contact->contact_id; ?>"/>
                    <input type="text" name="first_name" placeholder="First Name" value="<?= $contact->keep($contact->first_name); ?>"/><br/>
                    <input type="text" name="last_name" placeholder="Last Name"
                           value="<?= $contact->keep($contact->last_name); ?>"/><br/>
                    <input type="tel" name="phone_1" placeholder="Phone 1" value="<?= $contact->keep($contact->phone_1); ?>"/><br/>
                    <input type="tel" name="phone_2" placeholder="Phone 2" value="<?= $contact->keep($contact->phone_2); ?>"/><br/>
                    <input type="email" name="email_1" placeholder="E-mail 1" value="<?= $contact->keep($contact->mail_1); ?>"/><br/>
                    <input type="email" name="email_2" placeholder="E-mail 2" value="<?= $contact->keep($contact->mail_2); ?>"/><br/>
                    <input type="date" name="dob" placeholder="dob" value="<?= $contact->keep($contact->dob); ?>"/><br/>
                    <input type="text" name="street" placeholder="Street" value="<?= $contact->keep($contact->street); ?>"/><br/>
                    <input type="number" name="street_num" placeholder="Street Num"
                           value="<?= $contact->keep($contact->street_num); ?>"/>
                    <input type="number" name="street_num_add" placeholder="Add"
                           value="<?= $contact->keep($contact->street_num_add); ?>"/><br/>
                    <input type="" name="zip" placeholder="Zip/Postal" value="<?= $contact->keep($contact->zip); ?>"/><br/>
                    <p>Personal Notes</p>
                    <textarea name="notes" placeholder="Notes"><?= $contact->keep($contact->notes); ?></textarea>
                    <button type="submit" name="submit">Submit</button>
                    <br/>
                </form>
            </div>
        </div>
</div>
