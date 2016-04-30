<div class="container large">
	<button><a href="/admin/contacts/add-contact">Add Contact</a></button>
	<button><a href="/admin/contacts/deleted-contacts">Deleted Contacts</a></button>
</div>
<div class="container large">	
	<?php ($data['trashed'] === 1) ? $action = '/admin/contacts/deleted-contacts' : $action = 'contacts' ; ?>
	<table class="backend-table title">
		<tbody>
			<tr>
				<th>Name</th><th>Surname</th><th>Phone</th><th>E-mail</th><th>Edit</th><th><button id="check-all"><img class="glyph-small" src="/admin/images/check.png"/></button></th>
			</tr>
			<form class="backend-form" method="post" action="<?php echo $action; ?>">
				<?php foreach($data['contacts'] as $contact){?>
					<tr>
						<td><?php echo '<a href="/admin/contacts/info/'.$contact->getID().'/'.$contact->getFirstName().'">'.$contact->getFirstName().'</a>'?></td>
						<td><?php echo $contact->getLastName(); ?></td>
						<td><?php echo $contact->getPhone1(); ?></td>
						<td><?php echo $contact->getMail1(); ?></td>
						<td><?php echo '<a href="/admin/contacts/edit-contact/'.$contact->getID().'/'.$contact->getFirstName().'">Edit</a>'?></td>
						<td class="td-btn"><input type="checkbox" name="checkbox[]" value="<?php echo $contact->getID(); ?>"/></td>
				 </tr>
				<?php }
					require_once('view/manage_content.php'); 
				?>
			</form>
		</tbody>
	</table>
</div>
