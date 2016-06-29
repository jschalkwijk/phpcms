<div class="container large">
	<a class="link-btn" href="<?php echo ADMIN."contacts/add-contact";?>">Add Contact</a>
	<a class="link-btn" href="<?php echo ADMIN."contacts/deleted-contacts";?>">Deleted Contacts</a>
</div>
<div class="container large">	
	<?php ($data['trashed'] === 1) ? $action = ADMIN.'contacts/deleted-contacts' : $action = 'contacts' ; ?>
	<form class="backend-form" method="post" action="<?php echo $action; ?>">
		<table class="backend-table title">
			<tbody>
			<tr>
				<th>Name</th><th>Surname</th><th>Phone</th><th>E-mail</th><th>Edit</th><th><button id="check-all"><img class="glyph-small" src="<?php echo IMG."check.png";?>" alt="check-unchek-all-items"/></button></th>
			</tr>
		<?php foreach($data['contacts'] as $contact){?>
			<tr>
				<td><?php echo '<a href="'.ADMIN.'contacts/info/'.$contact->getID().'/'.$contact->getFirstName().'">'.$contact->getFirstName().'</a>'?></td>
				<td><?php echo $contact->getLastName(); ?></td>
				<td><?php echo $contact->getPhone1(); ?></td>
				<td><?php echo $contact->getMail1(); ?></td>
				<td><?php echo '<a href="'.ADMIN.'contacts/edit-contact/'.$contact->getID().'/'.$contact->getFirstName().'">Edit</a>'?></td>
				<td class="td-btn"><p><input type="checkbox" name="checkbox[]" value="<?php echo $contact->getID(); ?>"/></p></td>
			</tr>
		<?php } ?>
			</tbody>
		</table>
		<?php
			require_once('view/manage_content.php');
		?>
	</form>
</div>
