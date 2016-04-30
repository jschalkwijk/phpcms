<div class="container">
	<a href="products/add-product"><button>+ Product</button></a>
	<a href="products/deleted-products"><button>Deleted Products</button></a>
</div>
<div class="container">
	<?php 
		$products = $data['products']; 
		
	?>
	<table class="backend-table title">
		<tr><th>Name</th><th>Category</th><th>Price</th><th>In Stock</th><th>Edit</th><th>View</th><th><button id="check-all"><img class="glyph-small" src="/admin/images/check.png"/></button></th></tr>
		<form class="backend-form" method="post" action="/admin/products">
			<?php foreach($products as $product){ ?>
				<tr>
					<td class="td-title"><a href="<?php echo 'products/info/'.$product->getID().'/'.$product->getName(); ?>"><?php echo $product->getName(); ?></a></td>
					<td class="td-author"><?php echo $product->getCategory(); ?></td>
					<td class="td-date"><?php echo $product->getPrice();?></td> 
					<td class="td-category"><?php echo $product->getQuantity(); ?></td>
					<input type="hidden" name="id" value="<?php echo $product->getID() ?>" />
					<?php 
					if ($_SESSION['rights'] == 'Admin' || $_SESSION['rights'] == 'Content Manager') { ?>
							<td class="td-btn"><a href="<?php echo 'products/edit-product/'.$product->getID().'/'.$product->getName() ?>"><img class="glyph-small link-btn" src="<?php echo IMG_UPLOADPATH.'edit.png';?>"/></a></td>
						<?php if ($product->getApproved() == 0 ) { ?>
								<td class="td-btn"><img class="glyph-small" src="<?php echo IMG_UPLOADPATH.'hide.png'?>"/><!-- </button> --></td>
						<?php }	else if ($product->getApproved() == 1 ) { ?>
									<td class="td-btn"><img class="glyph-small" src="<?php echo IMG_UPLOADPATH.'show.png'?>"/><!-- </button> --></td>
						<?php } ?>
						<td class="td-btn"><input type="checkbox" name="checkbox[]" value="<?php echo $product->getID(); ?>"/></td>
					<?php } ?>
				</tr>
			<?php } ?>
			<?php require_once("view/manage_content.php")?>
		</form>
	</table>
</div>



