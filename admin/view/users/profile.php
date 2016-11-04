<?php $user = $data['user']; ?>

<div class="container">
	<div class="row">
		<div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
			<div class="center">
				<form method="post" action="<?= ADMIN.'users/profile/'.$user->getID().'/'.$user->getFirstName(); ?>">
					<input type="hidden" name="id" value="<?= $user->getID(); ?>"/>
					<input type="hidden" name="name" value="<?= $user->getFirstName(); ?>"/>
					<?php
					if ($user->getTrashed() == 1) { // show restore button in deleted items ?>
						<button type="submit" name="restore">Restore</button>
						<button type="submit" name="delete"><img class="glyph-small"><img src="<?= IMG.'delete-post.png'?>"></button>
					<?php   }
						if ($user->getTrashed() == 0) { ?>
							<button class="td-btn" type="submit" name="remove"><img class="glyph-small" src="<?= IMG.'trash-post.png'?>"></button>
					<?php } ?>
					<button type="button"><?= '<a href="'.ADMIN.'users/edit-users/'.$user->getID().'/'.$user->getUserName().'">Edit</a>'?></button>
				</form>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
			<div class="center">
				<img class="left" src="<?= ADMIN . $user->getUserImg(); ?>"/>
				<h1><?= $user->getFirstName() . ' ' . $user->getLastName(); ?></h1>
				<img class="left" src="<?php # echo '/admin/'.$user->getProfileImg(); ?>"/>
				<table>
					<tbody>
					<tr>
						<td>First Name</td>
						<td><?= $user->getFirstName(); ?></td
					</tr>
					<tr>
						<td>Last Name</td>
						<td><?= $user->getLastName(); ?></td
					</tr>
					<tr>
						<td>E-mail</td>
						<td><?= $user->getMail(); ?></td
					</tr>
					<tr>
						<td>Function</td>
						<td><?= $user->getFunction(); ?></td
					</tr>
					<tr>
						<td>Rights</td>
						<td><?= $user->getRights(); ?></td
					</tr>
					<tr>
						<td><p>Personal Notes</p></td
					</tr>
					<tr>
						<td></td>
						<td><?php # echo $user->getNotes();	 ?></td
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
