<?php
use CMS\Models\File\Folders;
use CMS\Models\File\File;
use CMS\Models\File\FileWriter;

$products = $data['product'];

(isset($data['output_form'])) ? $output_form = $data['output_form'] : $output_form = true;
?>

<div class="container">
	<div class="row">
		<div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
			<?php if($output_form){
                foreach($products as $product){
                    (isset($params[0]) && isset($params[1])) ? $action = ADMIN.'products/info/'.$product->product_id.'/'.$product->name : $action = ADMIN.'products/add-product';
                ?>
                    <form class="small" enctype="multipart/form-data" method="post" action="<?= $action; ?>">
                        <input type="hidden" name="MAX_FILE_SIZE" value="43500000" />
                        <label for="files[]">Choose File(max size: 3.5 MB): </label><br />
                        <input type="file" name="files[]" multiple/><br />
                        <input type="checkbox" name="public" value="public"/>
                        <label for='public'>Public</label>
                        <input type="checkbox" name="secure" value="secure"/>
                        <label for='secure'>Secure</label>
                        <input type="hidden" name="album_name" value=""/>
                        <input type="hidden" name="category_name" value="<?= $product->category; ?>"/>
                        <input type="hidden" name="album_id" value="<?= $product->albumid; ?>"/>
                        <?php (isset($params)) ? Folders::get_albums($product->album_id,$product->name) : Folders::get_albums(null,null) ;?>
                        <?php 	if(!isset($_GET['album'])){ ?>
                            <input type="text" name="new_album_name" placeholder="Create New Album" maxlength="60"/>
                        <?php 	} else { ?>
                            <input type="text" name="new_album_name" placeholder="Create New Sub Folder" maxlength="60"/>
                        <?php 	} ?>
                        <button type="submit" name="submit_file">Add File('s)</button>
                    </form>
                <?php }
                }?>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
			<form method="post" action="<?= ADMIN.'products/info/'.$product->product_id.'/'.$product->name; ?>">
				<input type="hidden" name="id" value="<?= $product->product_id; ?>"/>
				<input type="hidden" name="name" value="<?= $product->name ?>"/>
				<?php
				if ($product->trashed == 1) { // show restore button in deleted items ?>
					<button type="submit" name="restore">Restore</button>
					<button type="submit" name="delete"><img class="glyph-small" src="<?= IMG.'delete-post.png'?>"/></button>
				<?php   }
				if ($product->trashed == 0) { ?>
					<button class="td-btn" type="submit" name="remove"><img class="glyph-small" src="<?= IMG.'trash-post.png'?>"/></button>
					<button><?= '<a href="'.ADMIN.'/products/edit-product/'.$product->product_id.'/'.$product->name.'">Edit</a>'?></button>
				<?php } ?>
			</form>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
			<form class="backend-form" method="post" action="<?= ADMIN."cart/add/".$product->product_id;?>">
				<select name="quantity">
					<?php
					for($i = 0; $i < ($product->quantity + 1); $i++){ ?>
						<option value="<?= $i; ?>"><?= $i; ?></option>
					<?php } ?>
				</select>
				<button type="submit">Order</button>
			</form>
			<h1><?= $product->name; ?></h1>
			<td><?php if($product->lowStock()) { echo "Low stock!"; } else if($product->outOfStock()) { echo "Out of stock!"; } else { echo "";}?></td>
			<img class="left" src="<?= '/admin/'.$product->img_path; ?>"/>
			<table>
				<tbody>
				<tr>
					<td>Product Name</td>
					<td><?= $product->name; ?></td
				</tr>
				<tr>
					<td>Price</td>
					<td><?= $product->name; ?></td
				</tr>
				<tr>
					<td>In Stock</td>
					<td><?= $product->quantity; ?></td
				</tr>
				<tr>
					<td>Category</td>
					<td><?= $product->category; ?></td
				</tr>
				<tr>
					<td>Description</td>
					<td><?= $product->description; ?></td
				</tr>
				<tr>
					<td>VAT</td>
					<td><?= $product->getTax(); ?></td
				</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
			<button id="check-all"><img class="glyph-small" src="<?= IMG."check.png"; ?>"/></button>
			<?php Folders::show_albums($product->album_id); ?>
			<?php
				$img = ['jpg','jpeg','png'];
				$url = $_SERVER["REQUEST_URI"];
				echo '<form method="get" action="'.$url.'">';
				$files = File::fetchFilesByAlbum($product->album_id,0);
				FileWriter::write($files,ADMIN.'view/singleFile.php',[],$img);
				echo '<div class="left">';
				echo '<button type="submit" name="delete" id="delete">Delete Selected</button>';
				echo '<button type="submit" name="download_files" id="download_files" alt="Download File">Download files</button>';
				echo '</div>';
				echo '</form>';
			?>
		</div>
	</div>
</div>


