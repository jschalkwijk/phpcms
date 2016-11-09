<?php
	use CMS\Models\Content\ContentWriter;
	use CMS\Models\Users\UserWriter;
?>

<div class="container">
    <div class="row">

            <div class="col-sm-8 col-md-8 col-md-offset-2">
                <p class="logged">Hello <?= $_SESSION['username']; ?></p>
                <a href="<?= ADMIN."add-post";?>"><button>+ Post</button></a>
                <a href="<?= ADMIN."add-page"; ?>"><button>+ Page</button></a>
                <?php if($_SESSION['rights'] == 'Admin') { echo '<a href="'.ADMIN."add-user".'><button>+ User</button></a>'; } ?>
                <a href="#"><button>+ Products</button></a>
            </div>

    </div>

		<div class="row">
            <div class="center">
			<div class="col-sm-6 col-md-6">

                <h2>Posts</h2>
                <form class="backend-form" method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
                    <table class="backend-table title">
                        <tr><th class="td-title">Title</th><th class="td-author">Author</th><th class="td-category">Category</th><th class="td-date">Date</th><th>Edit</th><th>View</th>
                        <th><button type="button" id="check-all"><img class="glyph-small" alt="check-all-items" src="<?= IMG."/check.png"; ?>"/></button></th></tr>
                        <?php $posts = $data['posts']; ?>
                        <?php  foreach($posts as $single){ ?>
                        <tr>
                            <td class="td-title"><p><?= $single->title; ?></p></td>
                            <td class="td-author"><p><?= $single->author; ?></p></td>
                            <td class="td-category"><p><?= $single->categories_title; ?></p></td>
                            <td class="td-date"><p><?= $single->date; ?></p></td>
                            <?php
                            if ($_SESSION['rights'] == 'Admin' || $_SESSION['rights'] == 'Content Manager') { ?>
                                <td class="td-btn"><a href="<?= $single->table.'/edit-'.$single->table.'/' .$single->get_id().'/'.$single->getLink(); ?>"><img class="glyph-small link-btn" alt="edit-item" src="<?= IMG.'edit.png';?>"/></a></td>
                                <?php if ($single->approved == 0 ) { ?>
                                    <td class="td-btn"><img class="glyph-small" alt="item-hidden-from-front-end-user" src="<?= IMG.'hide.png'?>"/></td>
                                <?php }	else if ($single->approved == 1 ) { ?>
                                    <td class="td-btn"><img class="glyph-small" alt="item-visible-for-front-end-user" src="<?= IMG.'show.png'?>"/></td>
                                <?php } ?>
                                <td class="td-btn"><p><input type="checkbox" name="checkbox[]" value="<?= $single->get_id(); ?>"/></p></td>
                            <?php }
                            }?>
                        </tr>
                    </table>
                    <?php
                        require($data['view']['actions']);
                    ?>
                    <input type="hidden" name="dbt" value="posts"/>
                </form>
            </div>


			<div class="col-sm-6 col-md-6">
				<h2>Pages</h2>
				<table class="backend-table title">
					<tr><th class="td-title">Title</th><th class="td-author">Author</th><th class="td-category">Category</th><th class="td-date">Date</th><th>Edit</th><th>View</th>
                        <th><button type="button" id="check-all"><img class="glyph-small" alt="check-all-items" src="<?= IMG."/check.png"; ?>"/></button></th></tr>
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

    <div class="row">
        <div class="center">
            <div class="col-sm-4 col-md-4">
                <h2>New Users</h2>
                <table class="backend-table title">
                    <tr><th>User</th><th>Rights</th> <?php if ($_SESSION['rights'] == 'Admin') { ?> <th>Edit</th><th>View</th><!-- <th>Remove</th> --><?php } ?> </tr>
                    <form class="backend-form" method="post" action="<?= ADMIN; ?>">
                            <?php
                                $users = $data['users'];
                                $writer = UserWriter::write($users);
                                require('view/shared/manage-content.php');
                            ?>
                            <input type="hidden" name="dbt" value="login"/>
                        </form>
                </table>
            </div>
            <div class="col-sm-4 col-md-4">
                <h2>Add Files</h2>
                <?php require('view/files/add-files.php'); ?>
            </div>


            <div class="col-sm-4 col-md-4">
                <h2>Search Posts</h2>
                <?php require_once('view/search/search-post.php'); ?>
            </div>
        </div>
    </div>
</div>
				
