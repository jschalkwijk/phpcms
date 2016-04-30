<p>De backend users moeten een shared encryptie key gebruiken voor de contactinformatie die zij
	onderling moeten kunnen delen. Daarnaast moeten ze een eigen apparte key hebben voor persoonlijke notities etc.
	Hoer goed over nadenken! de scheiding van admins en normale gebruikers moet echt goed in elkaar zitten.
</p>
<div class="container">
	<div class="container">
		<div class="container">
			<a href="users/add-user"><button>+ User</button></a>
			<a href="users/deleted-users"><button>Deleted Users</button></a>
		</div>
		<table class="backend-table title">
			<tr><th>User</th><th>Rights</th><?php if ($_SESSION['rights'] == 'Admin') { ?> <th>Edit</th><th>View</th><th><button id="check-all"><img class="glyph-small" src="/admin/images/check.png"/></button></th></tr> <?php } ?>
			<form class="backend-form" method="post" action="/admin/users">
				<?php 
					$users = $data['users'];
					$writer = users_UserWriter::write($users);
					require('view/manage_content.php');	
				?>
			</form>
		</tr></table>
	</div>
</div>