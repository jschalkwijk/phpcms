<?php $contact = $data['contact'][0]; ?>

<div class="container">
	<div class="row">
		<div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
			<form method="post" action="<?= ADMIN.'contacts/info/'.$contact->contact_id.'/'.$contact->firstName(); ?>">
				<input type="hidden" name="id" value="<?= $contact->contact_id; ?>"/>
				<input type="hidden" name="name" value="<?= $contact->firstName(); ?>"/>
				<?php
				if ($contact->trashed == 1) { // show restore button in deleted items ?>
					<button type="submit" name="restore">Restore</button>
					<button type="submit" name="delete"><img class="glyph-small" src="<?= IMG.'delete-post.png'?>"/></button>
				<?php   }
					if ($contact->trashed == 0) { ?>
						<button class="td-btn" type="submit" name="remove"><img class="glyph-small" src="<?= IMG.'trash-post.png'?>"></button>
				<?php } ?>
				<button type="button"><?= '<a href="'.ADMIN.'contacts/edit-contact/'.$contact->contact_id.'/'.$contact->firstName().'">Edit</a>'?></button>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
			<h1><?= $contact->firstName().' '.$contact->lastName(); ?></h1>
			<img class="left" src="<?= '/admin/'.$contact->img_path; ?>"/>
			<table>
				<tbody>
				<tr>
					<td>First Name</td>
					<td><?= $contact->firstName(); ?></td
				</tr>
				<tr>
					<td>Last Name</td>
					<td><?= $contact->lastName(); ?></td
				</tr>
				<tr>
					<td>Phone 1</td>
					<td><?= $contact->phone1(); ?></td
				</tr>
				<tr>
					<td>Phone 2</td>
					<td><?= $contact->phone2(); ?></td
				</tr>
				<tr>
					<td>E-mail 1</td>
					<td><?= $contact->mail1(); ?></td
				</tr>
				<tr>
					<td>E-mail 2</td>
					<td><?= $contact->mail2(); ?></td
				</tr>
				<tr>
					<td>DOB</td>
					<td><?= $contact->dob(); ?></td
				</tr>
				<tr>
					<td>Street</td>
					<td><?= $contact->street(); ?></td
				</tr>
				<tr>
					<td>Number</td>
					<td><?= $contact->streetNum(); ?></td
				</tr>
				<tr>
					<td>Addition</td>
					<td><?= $contact->streetNumAddition(); ?></td>
				</tr>
				<tr>
					<td>ZIP/Postal</td>
					<td><?= $contact->zip(); ?></td
				</tr>
				<tr>
					<td><p>Personal Notes</p></td
				</tr>
				<tr>
					<td></td>
					<td><?= $contact->notes(); ?></td
				</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>