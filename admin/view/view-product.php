<?php $product = $data['product'];?>

<div class="container">
	<form method="post" action="<?php echo '/admin/products/info/'.$product->getID().'/'.$product->getName(); ?>">
		<input type="hidden" name="id" value="<?php echo $product->getID(); ?>"/>
		<input type="hidden" name="name" value="<?php echo $product->getName(); ?>"/>
		<?php 
		if ($product->getTrashed() == 1) { // show restore button in deleted items ?>		
			<button type="submit" name="restore">Restore</button>
			<button type="submit" name="delete"><img class="glyph-small" src="<?php echo IMG_UPLOADPATH.'delete-post.png'?>"/></button>
	<?php   } 
		if ($product->getTrashed() == 0) { ?>
			<button class="td-btn" type="submit" name="remove"><img class="glyph-small" src="<?php echo IMG_UPLOADPATH.'trash-post.png'?>"/></button>
	<?php } ?>
	</form>
	<button><?php echo '<a href="/admin/products/edit-product/'.$product->getID().'/'.$product->getName().'">Edit</a>'?></button>
</div>

<div class="container medium">
	<h1><?php echo $product->getName(); ?></h1>
	<img class="left" src="<?php echo '/admin/'.$product->getProductImg(); ?>"/>
	<table>
		<tbody>
			<tr><td>Product Name</td><td><?php echo $product->getName(); ?></td</tr>
			<tr><td>Price</td><td><?php echo $product->getPrice();	 ?></td</tr>
			<tr><td>In Stock</td><td><?php echo $product->getQuantity();	 ?></td</tr>
			<tr><td>Category</td><td><?php echo $product->getCategory();	 ?></td</tr>
			<tr><td>Desciption</td><td><?php echo $product->getDescription();	 ?></td</tr>
		</tbody>
	</table>
</div>

<div class="container medium">
<?php
		$img = ['jpg','jpeg','png'];
		$url = $_SERVER["REQUEST_URI"];
		echo '<form method="get" action="'.$url.'">';	
				$files = files_File::fetchFilesByAlbum($product->getAlbumID(),0);
				$writer = files_FileWriter::write($files,'view/singleFile.php',[],$img);
			echo '<div class="left">';
				echo '<button type="submit" name="delete" id="delete">Delete Selected</button>';
				echo '<button type="submit" name="download_files" id="download_files" alt="Download File">Download files</button>';	
			echo '</div>';
		echo '</form>';
	?>

</div>