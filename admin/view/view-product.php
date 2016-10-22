<?php
use CMS\model\File\Folders;
use CMS\model\File\File;
use CMS\model\File\FileWriter;

$product = $data['product'];

(isset($params[0]) && isset($params[1])) ? $action = ADMIN.'products/info/'.$product->getID().'/'.$product->getName() : $action = ADMIN.'products/add-product';
(isset($data['output_form'])) ? $output_form = $data['output_form'] : $output_form = true;
?>

<div class="container small">
	<form class="small" enctype="multipart/form-data" method="post" action="<?php echo $action; ?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="43500000" />
		<label for="files[]">Choose File(max size: 3.5 MB): </label><br />
		<input type="file" name="files[]" multiple/><br />
		<input type="checkbox" name="public" value="public"/>
		<label for='public'>Public</label>
		<input type="checkbox" name="secure" value="secure"/>
		<label for='secure'>Secure</label>
		<input type="hidden" name="album_name" value=""/>
		<input type="hidden" name="category_name" value="<?php echo $product->getCategory(); ?>"/>
		<input type="hidden" name="album_id" value="<?php echo $product->getAlbumID(); ?>"/>
		<?php (isset($params)) ? Folders::get_albums($product->getAlbumID(),$product->getName()) : Folders::get_albums(null,null) ;?>
		<?php 	if(!isset($_GET['album'])){ ?>
			<input type="text" name="new_album_name" placeholder="Create New Album" maxlength="60"/>
		<?php 	} else { ?>
			<input type="text" name="new_album_name" placeholder="Create New Sub Folder" maxlength="60"/>
		<?php 	} ?>
		<button type="submit" name="submit_file">Add File('s)</button>
	</form>
</div>

<div class="container">
	<form method="post" action="<?php echo ADMIN.'products/info/'.$product->getID().'/'.$product->getName(); ?>">
		<input type="hidden" name="id" value="<?php echo $product->getID(); ?>"/>
		<input type="hidden" name="name" value="<?php echo $product->getName(); ?>"/>
		<?php
		if ($product->getTrashed() == 1) { // show restore button in deleted items ?>
			<button type="submit" name="restore">Restore</button>
			<button type="submit" name="delete"><img class="glyph-small" src="<?php echo IMG.'delete-post.png'?>"/></button>
		<?php   }
		if ($product->getTrashed() == 0) { ?>
			<button class="td-btn" type="submit" name="remove"><img class="glyph-small" src="<?php echo IMG.'trash-post.png'?>"/></button>
			<button><?php echo '<a href="'.ADMIN.'/products/edit-product/'.$product->getID().'/'.$product->getName().'">Edit</a>'?></button>
		<?php } ?>
	</form>

</div>

<div class="container medium">
	<form class="backend-form" method="post" action="<?php echo ADMIN."cart/add/".$product->getID();?>">
		<select name="quantity">
			<?php
			for($i = 0; $i < $product->getQuantity()+1; $i++){ ?>
				<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
			<?php } ?>
		</select>
		<button type="submit">Order</button>
	</form>
	<h1><?php echo $product->getName(); ?></h1>
	<td><?php if($product->lowStock()) { echo "Low stock!"; } else if($product->outOfStock()) { echo "Out of stock!"; } else { echo "";}?></td>
	<img class="left" src="<?php echo '/admin/'.$product->getProductImg(); ?>"/>
	<table>
		<tbody>
		<tr><td>Product Name</td><td><?php echo $product->getName(); ?></td</tr>
		<tr><td>Price</td><td><?php echo $product->getPrice();	 ?></td</tr>
		<tr><td>In Stock</td><td><?php echo $product->getQuantity();	 ?></td</tr>
		<tr><td>Category</td><td><?php echo $product->getCategory();	 ?></td</tr>
		<tr><td>Desciption</td><td><?php echo $product->getDescription();	 ?></td</tr>
		<tr><td>VAT</td><td><?php echo $product->getTax();	 ?></td</tr>
		</tbody>
	</table>
</div>
<div class="container large">
	<button id="check-all"><img class="glyph-small" src="<?php echo IMG."check.png"; ?>"/></button>
	<?php Folders::show_albums($product->getAlbumID()); ?>
</div>

<div class="container medium">
	<?php
	$img = ['jpg','jpeg','png'];
	$url = $_SERVER["REQUEST_URI"];
	echo '<form method="get" action="'.$url.'">';
	$files = File::fetchFilesByAlbum($product->getAlbumID(),0);
	FileWriter::write($files,ADMIN.'view/singleFile.php',[],$img);
	echo '<div class="left">';
	echo '<button type="submit" name="delete" id="delete">Delete Selected</button>';
	echo '<button type="submit" name="download_files" id="download_files" alt="Download File">Download files</button>';
	echo '</div>';
	echo '</form>';
	?>
</div>