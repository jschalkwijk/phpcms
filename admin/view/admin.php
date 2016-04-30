<div class="container">
	<p class="logged">Hello <?php echo $_SESSION['username']; ?></p>
	<a href="addpost"><button>+ Post</button></a>
	<a href="addpage"><button>+ Page</button></a>
	<?php if($_SESSION['rights'] == 'Admin') { echo '<a href="adduser"><button>+ User</button></a>'; } ?>
	<a href="#"><button>+ Client</button></a>
	<a href="#"><button>+ Project</button></a>
	<a href="#"><button>+ Product</button></a>
	<a href="#"><button>Send Mail</button></a>
</div>
<h2>Latest posts</h2>	

<div class="large left">	
	<div class="large">
		<h2>Posts</h2>
		<table class="backend-table title">
			<tr><th>Title</th><th>Author</th><th>Category</th><th>Date</th><th>Edit</th><th>View</th><!-- <th>Remove</th> --></tr>
			<form class="backend-form" method="post" action="/admin">
				<?php
					// array of Post objects with data from the DB
					$posts = $data['posts'];
					// thisis a write function, it just writes out the data,
					// the information is supplied by the model
					$writer = content_ContentWriter::write($posts);
					require($data['view']['actions']);
				?>
				<input type="hidden" name="dbt" value="posts"/>
			</form>	
		</table>
	</div>
	<div class="large right">
		<h2>Pages</h2>
		<table class="backend-table title">
			<tr><th>Title</th><th>Author</th><th>Category</th><th>Date</th><th>Edit</th><th>View</th><!-- <th>Remove</th> --></tr>
			<form class="backend-form" method="post" action="/admin">
				<?php 	
					$pages = $data['pages'];
					$writer = content_ContentWriter::write($pages);
					require($data['view']['actions']);
				?>
				<input type="hidden" name="dbt" value="pages"/>
			</form>	
		</table>
	</div>
</div>
<div class="large left">
	<div class="medium">
		<h2>New Users</h2>
		<table class="backend-table title">
			<tr><th>User</th><th>Rights</th>
			<form class="backend-form" method="post" action="/admin">
				<?php if ($_SESSION['rights'] == 'Admin') { ?> <th>Edit</th><th>View</th><!-- <th>Remove</th> --></tr> <?php } ?>
		
				<?php 
					$users = $data['users'];
					$writer = users_UserWriter::write($users);
					require('view/manage_content.php');	
				?>
				<input type="hidden" name="dbt" value="login"/>
			</form>
		</tr></table>
	</div>
	<div class="medium">
		<h2>Add Files</h2>
		<?php require('view/add-files.php'); ?>
		<?php //drop_files();?>
	</div>
	<div class="small">
		<h2>Search Posts</h2>
		<?php require_once('view/search-post.php'); ?>
	</div>
	<div class="small">
		<h2>Contact Forms</h2>
		<h2>New Subscribers</h2>
	</div>
</div>
				
