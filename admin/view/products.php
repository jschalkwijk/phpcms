<div class="container">
	<a class="link-btn" href="<?php echo ADMIN."products/add-product";?>">+ Product</a>
	<a class="link-btn" href="<?php echo ADMIN."products/deleted-products"; ?>">Deleted Products</a>
</div>
<div class="container">
	<?php 
		$products = $data['products'];
	?>
	<form class="backend-form" method="post" action="<?php echo ADMIN."products"; ?>>
		<table class="backend-table title">
			<tr><th>Name</th><th>Category</th><th>Price</th><th>In Stock</th><th>Edit</th><th>View</th><th><button type="button" id="check-all"><img class="glyph-small" src="<?php echo ADMIN."check.png"; ?>" alt="check-unheck-all-items"/></button></th></tr>
			<?php foreach($products as $product){ ?>
				<tr>
					<td class="td-title"><a href="<?php echo ADMIN.'products/info/'.$product->getID().'/'.$product->getName(); ?>"><?php echo $product->getName(); ?></a></td>
					<td class="td-author"><?php echo $product->getCategory(); ?></td>
					<td class="td-date"><?php echo $product->getPrice();?></td>
					<td class="td-category"><?php echo $product->getQuantity(); ?></td>
					<!--<input type="hidden" name="id" value="<?php /*echo $product->getID() */?>" />-->
					<?php
					if ($_SESSION['rights'] == 'Admin' || $_SESSION['rights'] == 'Content Manager') { ?>
							<td class="td-btn"><a href="<?php echo ADMIN.'products/edit-product/'.$product->getID().'/'.$product->getName() ?>"><img class="glyph-small link-btn" src="<?php echo IMG.'edit.png';?>" alt="edit-item"/></a></td>
						<?php if ($product->getApproved() == 0 ) { ?>
								<td class="td-btn"><img class="glyph-small" src="<?php echo IMG.'hide.png'?>" alt="item-hidden-from-front-end-user"/><!-- </button> --></td>
						<?php }	else if ($product->getApproved() == 1 ) { ?>
									<td class="td-btn"><img class="glyph-small" src="<?php echo IMG.'show.png'?>" alt="item-visible-from-front-end-user"/><!-- </button> --></td>
						<?php } ?>
						<td class="td-btn"><p><input type="checkbox" name="checkbox[]" value="<?php echo $product->getID(); ?>"/></p></td>
					<?php } ?>
				</tr>
			<?php } ?>
		</table>
		<?php
			require('view/manage_content.php');
		?>
	</form>
</div>


