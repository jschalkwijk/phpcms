<?php
	use CMS\model\Content\ContentWriter;
	use CMS\model\Users\UserWriter;
?>
<div class="container-fluid">
	<div class="row">
		<div class="center-block col-sm-8 col-md-8 col-lg-8">
			<p class="logged">Hello <?= $_SESSION['username']; ?></p>
			<a href="<?= ADMIN."add-post";?>"><button>+ Post</button></a>
			<a href="<?= ADMIN."add-page"; ?>"><button>+ Page</button></a>
			<?php if($_SESSION['rights'] == 'Admin') { echo '<a href="'.ADMIN."add-user".'><button>+ User</button></a>'; } ?>
			<a href="#"><button>+ Client</button></a>
			<a href="#"><button>+ Project</button></a>
			<a href="#"><button>+ Product</button></a>
			<a href="#"><button>Send Mail</button></a>
		</div>
	</div>
</div>


<div class="container">
	<div class="center">
		<div class="row">
			<div class="col-sm-6 col-md-6">
				<table class="backend-table title">
					<h2>Posts</h2>
					<tbody>
					<tr><th class="td-title">Title</th><th class="td-author">Author</th><th class="td-category">Category</th><th class="td-date">Date</th><th>Edit</th><th>View</th><!-- <th>Remove</th> --></tr>
					<form class="backend-form" method="post" action="/admin">
						<?php
							// array of Post objects with data from the DB
							$posts = $data['posts'];
							// thisis a write function, it just writes out the data,
							// the information is supplied by the model
							ContentWriter::write($posts);
							require($data['view']['actions']);
						?>
						<input type="hidden" name="dbt" value="posts"/>
					</form>
					</tbody>
				</table>
			</div>

			<div class="col-sm-6 col-md-6">
				<h2>Pages</h2>
				<table class="backend-table title">
					<tr><th class="td-title">Title</th><th class="td-author">Author</th><th class="td-category">Category</th><th class="td-date">Date</th><th>Edit</th><th>View</th><!-- <th>Remove</th> --></tr>

					<form class="backend-form" method="post" action="<?= ADMIN ;?>">
						<?php
							$pages = $data['pages'];
							ContentWriter::write($pages);
							require($data['view']['actions']);
						?>
						<input type="hidden" name="dbt" value="pages"/>
					</form>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-xs-6 col-md-6">
			<h2>New Users</h2>
			<table class="backend-table title">
				<tr><th>User</th><th>Rights</th>
					<form class="backend-form" method="post" action="<?= ADMIN; ?>">
						<?php if ($_SESSION['rights'] == 'Admin') { ?> <th>Edit</th><th>View</th><!-- <th>Remove</th> --></tr> <?php } ?>

						<?php
							$users = $data['users'];
							$writer = UserWriter::write($users);
							require('view/manage_content.php');
						?>
						<input type="hidden" name="dbt" value="login"/>
					</form>
				</tr>
			</table>
		</div>
		<div class="col-sm-6 col-md-6">
			<h2>Add Files</h2>
			<?php require('view/add-files.php'); ?>
		</div>
	</div>
	<div class="row"
		<div class="col-sm-3 col-md-3">
			<h2>Search Posts</h2>
			<?php require_once('view/search-post.php'); ?>
		</div>
		<div class="col-sm-3 col-md-3">
			<h2>Contact Forms</h2>
			<h2>New Subscribers</h2>
		</div>
	</div>
</div>
				
