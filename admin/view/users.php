<?php use Jorn\admin\model\Users\UserWriter; ?>
<p>De backend users moeten een shared encryptie key gebruiken voor de contactinformatie die zij
	onderling moeten kunnen delen. Daarnaast moeten ze een eigen apparte key hebben voor persoonlijke notities etc.
	Hoer goed over nadenken! de scheiding van admins en normale gebruikers moet echt goed in elkaar zitten.
</p>

	<div class="container">
		<div class="container">
			<a class="link-btn" href="<?php echo ADMIN."users/add-user"; ?>">+ User</a>
			<a class="link-btn" href="<?php echo ADMIN."users/deleted-users";?>">Deleted Users</a>
		</div>
			<form class="backend-form" method="post" action="<?php echo ADMIN."users"; ?>">
				<table class="backend-table title">
					<tr><th>User</th><th>Rights</th><?php if ($_SESSION['rights'] == 'Admin') { ?> <th>Edit</th><th>View</th><th><button type="button" id="check-all"><img class="glyph-small" src="<?php echo IMG."check.png";?>" alt="check-uncheck-all-items"/></button></th></tr> <?php } ?>
					<?php
					$users = $data['users'];
					UserWriter::write($users);
					?>
				</table>
				<?php
					require('view/manage_content.php');
				?>
			</form>
	</div>
