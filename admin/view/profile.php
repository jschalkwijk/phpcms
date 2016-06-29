<?php $user = $data['user']; ?>

<div class="container">
	<form method="post" action="<?php echo ADMIN.'users/profile/'.$user->getID().'/'.$user->getFirstName(); ?>">
		<input type="hidden" name="id" value="<?php echo $user->getID(); ?>"/>
		<input type="hidden" name="name" value="<?php echo $user->getFirstName(); ?>"/>
		<?php 
		if ($user->getTrashed() == 1) { // show restore button in deleted items ?>		
			<button type="submit" name="restore">Restore</button>
			<button type="submit" name="delete"><img class="glyph-small" src="<?php echo IMG.'delete-post.png'?>"/></button>
	<?php   } 
		if ($user->getTrashed() == 0) { ?>
			<button class="td-btn" type="submit" name="remove"><img class="glyph-small" src="<?php echo IMG.'trash-post.png'?>"/></button>
	<?php } ?>
	</form>
	<button><?php echo '<a href="'.ADMIN.'users/edit-users/'.$user->getID().'/'.$user->getUserName().'">Edit</a>'?></button>
</div>
<div class="container medium">
	<img class="left" src="<?php echo ADMIN.$user->getUserImg(); ?>"/>
	<h1><?php echo $user->getFirstName().' '.$user->getLastName(); ?></h1>
	<img class="left" src="<?php # echo '/admin/'.$user->getProfileImg(); ?>"/> 
	<table>
		<tbody>
			<tr><td>First Name</td><td><?php echo $user->getFirstName(); ?></td</tr>
			<tr><td>Last Name</td><td><?php echo $user->getLastName();	 ?></td</tr>
			<tr><td>E-mail </td><td><?php echo $user->getMail();	 ?></td</tr>
			<tr><td>Function </td><td><?php echo $user->getFunction();	 ?></td</tr>
			<tr><td>Rights</td><td><?php echo $user->getRights();	 ?></td</tr>
			<tr><td><p>Personal Notes</p></td</tr>
			<tr><td></td><td><?php # echo $user->getNotes();	 ?></td</tr>
		</tbody>
	</table>
</div>