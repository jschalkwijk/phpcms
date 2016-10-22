<?php $contact = $data['contact']; ?>

<div class="container">
	<form method="post" action="<?= ADMIN.'contacts/info/'.$contact->getID().'/'.$contact->getFirstName(); ?>">
		<input type="hidden" name="id" value="<?= $contact->getID(); ?>"/>
		<input type="hidden" name="name" value="<?= $contact->getFirstName(); ?>"/>
		<?php 
		if ($contact->getTrashed() == 1) { // show restore button in deleted items ?>		
			<button type="submit" name="restore">Restore</button>
			<button type="submit" name="delete"><img class="glyph-small" src="<?= IMG.'delete-post.png'?>"/></button>
	<?php   } 
		if ($contact->getTrashed() == 0) { ?>
			<button class="td-btn" type="submit" name="remove"><img class="glyph-small" src="<?= IMG.'trash-post.png'?>"/></button>
	<?php } ?>
	</form>
	<button><?= '<a href="'.ADMIN.'contacts/edit-contact/'.$contact->getID().'/'.$contact->getFirstName().'">Edit</a>'?></button>
</div>
<div class="container medium">
	<h1><?= $contact->getFirstName().' '.$contact->getLastName(); ?></h1>
	<img class="left" src="<?= '/admin/'.$contact->getContactImg(); ?>"/>
	<table>
		<tbody>
			<tr><td>First Name</td><td><?= $contact->getFirstName(); ?></td</tr>
			<tr><td>Last Name</td><td><?= $contact->getLastName();	 ?></td</tr>
			<tr><td>Phone 1</td><td><?= $contact->getPhone1();	 ?></td</tr>
			<tr><td>Phone 2</td><td><?= $contact->getPhone2();	 ?></td</tr>
			<tr><td>E-mail 1</td><td><?= $contact->getMail1();	 ?></td</tr>
			<tr><td>E-mail 2</td><td><?= $contact->getMail2();	 ?></td</tr>
			<tr><td>DOB</td><td><?= $contact->getDOB();		 ?></td</tr>
			<tr><td>Street</td><td><?= $contact->getStreet();	 ?></td</tr>
			<tr><td>Number</td><td><?= $contact->getStreetNum(); ?></td</tr>
			<tr><td>Addition</td><td><?= $contact->getStreetNumAdd();?></td></tr>
			<tr><td>ZIP/Postal</td><td><?= $contact->getZip(); 		 ?></td</tr>
			<tr><td><p>Personal Notes</p></td</tr>
			<tr><td></td><td><?= $contact->getNotes();	 ?></td</tr>
		</tbody>
	</table>
</div>
