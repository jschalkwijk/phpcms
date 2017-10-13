<?php
use CMS\Models\Files\Folder;
use CMS\Models\Files\File;
use CMS\Models\Files\FileWriter;

$product = $data['product'];

(isset($data['output_form'])) ? $output_form = $data['output_form'] : $output_form = true;
?>

<div class="container">
	<div class="row">
		<div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
			<?php if($output_form){
                    (isset($params['id']) && isset($params['name'])) ? $action = ADMIN.'products/info/'.$product->product_id.'/'.$product->name : $action = ADMIN.'products/create';
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
                        <input type="hidden" name="category_name" value="<?= $product->category_name; ?>"/>
                        <input type="hidden" name="album_id" value="<?= $product->folder_id; ?>"/>
                        <?php (isset($params)) ? Folder::get_albums($product->folder_id,$product->name) : Folder::get_albums(null,null) ;?>

                            <input type="text" name="new_album_name" placeholder="Create New Folder" maxlength="60"/>

                        <button type="submit" name="submit_file">Add File('s)</button>
                    </form>
                <?php
                }?>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
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
					<button><?= '<a href="'.ADMIN.'/products/edit/'.$product->product_id.'">Edit</a>'?></button>
				<?php } ?>
			</form>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
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
					<td><?= $product->category->name; ?></td
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
		<div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
			<button id="check-all"><img class="glyph-small" src="<?= IMG."check.png"; ?>"/></button>
			<?php
				$img = ['jpg','jpeg','png'];
				$url = $_SERVER["REQUEST_URI"];
				echo '<form method="get" action="'.$url.'">';
				$files = File::allWhere(['album_id' => $product->album_id]);
				FileWriter::write($files,ADMIN.'view/singleFile.php',[],$img);
				echo '<div class="left">';
				echo '<button type="submit" name="delete" id="delete">Delete Selected</button>';
				echo '<button type="submit" name="download_files" id="download_files" alt="Download File">Download files</button>';
				echo '</div>';
				echo '</form>';
			?>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6 col-lg-6 push-lg-3 col-lg-offset-3">
			<form id="check-folders" method="post" action="<?= ADMIN.'files' ?>">
				<button type="button" id="check-all"><img class="glyph-small" src="<?= IMG."check.png"; ?>"/></button>
				<table class="files-table">
					<thead></thead><th></th><th>Name</th><th>Size(MB)</th></thead>
					<tbody>
					<?php
                    // get all products related to a folder
                    foreach($product->folder()->products() as $product){
                        echo "Productname".$product->name."<br>";
                    }
//                    foreach($product->folder()->subFolders() as $folder){
//                        echo "Foldername: ".$folder->name."<br>";
//                        foreach($folder->files() as $file){
//                            echo "Filename: ".$file->name."<br>";
//                        };
//                    };
						foreach($product->folder()->children() as $folder) {// echo "hello:<br>"; print_r($folder->files())?>

							<tr class="meta">
								<td><img class="glyph-medium" src="<?= ADMIN.'images/files.png' ?>"/></td>
								<td><a href="<?= ADMIN ?>files/albums/<?= $folder->album_id.'/'.$folder->name ?>"> <?= $folder->name ?></a></td>
								<td>Size</td>
								<input type="hidden" name="album_name" value="<?= $folder->name ?>"/>
								<td><input class="checkbox" type="checkbox" name="checkbox[]" value="<?= $folder->album_id ?>"/></td>
							</tr>
						<?php } ?>

					</tbody>
				</table>
				<button type="submit" name="delete-albums" id="delete-albums">Delete Albums</button>
			</form>
		</div>
	</div>
</div>


