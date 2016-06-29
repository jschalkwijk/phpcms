<?php $contact = $data['contact']; ?>

<div class="container">
	<form method="post" action="<?php echo ADMIN.'contacts/info/'.$contact->getID().'/'.$contact->getFirstName(); ?>">
		<input type="hidden" name="id" value="<?php echo $contact->getID(); ?>"/>
		<input type="hidden" name="name" value="<?php echo $contact->getFirstName(); ?>"/>
		<?php 
		if ($contact->getTrashed() == 1) { // show restore button in deleted items ?>		
			<button type="submit" name="restore">Restore</button>
			<button type="submit" name="delete"><img class="glyph-small" src="<?php echo IMG.'delete-post.png'?>"/></button>
	<?php   } 
		if ($contact->getTrashed() == 0) { ?>
			<button class="td-btn" type="submit" name="remove"><img class="glyph-small" src="<?php echo IMG.'trash-post.png'?>"/></button>
	<?php } ?>
	</form>
	<button><?php echo '<a href="'.ADMIN.'contacts/edit-contact/'.$contact->getID().'/'.$contact->getFirstName().'">Edit</a>'?></button>
</div>
<div class="container medium">
	<h1><?php echo $contact->getFirstName().' '.$contact->getLastName(); ?></h1>
	<img class="left" src="<?php echo '/admin/'.$contact->getContactImg(); ?>"/>
	<table>
		<tbody>
			<tr><td>First Name</td><td><?php echo $contact->getFirstName(); ?></td</tr>
			<tr><td>Last Name</td><td><?php echo $contact->getLastName();	 ?></td</tr>
			<tr><td>Phone 1</td><td><?php echo $contact->getPhone1();	 ?></td</tr>
			<tr><td>Phone 2</td><td><?php echo $contact->getPhone2();	 ?></td</tr>
			<tr><td>E-mail 1</td><td><?php echo $contact->getMail1();	 ?></td</tr>
			<tr><td>E-mail 2</td><td><?php echo $contact->getMail2();	 ?></td</tr>
			<tr><td>DOB</td><td><?php echo $contact->getDOB();		 ?></td</tr>
			<tr><td>Street</td><td><?php echo $contact->getStreet();	 ?></td</tr>
			<tr><td>Number</td><td><?php echo $contact->getStreetNum(); ?></td</tr>
			<tr><td>Addition</td><td><?php echo $contact->getStreetNumAdd();?></td></tr>
			<tr><td>ZIP/Postal</td><td><?php echo $contact->getZip(); 		 ?></td</tr>
			<tr><td><p>Personal Notes</p></td</tr>
			<tr><td></td><td><?php echo $contact->getNotes();	 ?></td</tr>
		</tbody>
	</table>
</div>
