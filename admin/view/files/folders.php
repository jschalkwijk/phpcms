<div class="container">
    	<div class="row">
    		<div class="col-sm-6 col-lg-6 col-sm-offset-3 push-lg-3">
				<?php require_once 'view/files/add-files.php'; ?>
    		</div>
    	</div>
	<div class="row">
		<div class="col-lg-6 col-lg-6 push-lg-3 col-lg-offset-3">
		<?php
			if(isset($folder)){
//				$folder = $data['folder'];
				echo '<h1>' . $folder->name . '</h1>';
				$doc = ['txt', 'doc', 'docx', 'odt'];
				$img = ['jpg', 'jpeg', 'png'];
				$url = $_SERVER["REQUEST_URI"];
                foreach($folder->products() as $product){
                    echo "Productname".$product->name."<br>";
                }
		?>
			<form id="check-files" method="post" action="<?= ADMIN.'files/action' ?>">
				<table class="files-table">
					<thead><th></th><th>Name</th><th>Type</th><th>Size</th><th>Del</th></thead>
					<tbody>
					<?php
						foreach($folder->files() as $single){
							require 'view/files/single-file.php';
						}
					?>
					</tbody>
				</table>
                <span>Move to: </span>
                <select id="parent" name="destination">
                    <?php
                        echo '<option value="0" selected>None</option>';

                        foreach($folders as $f) {
                            echo  '<option value="'. $f->get_id().'">'.$f->name.'</option>';
                        }
                    ?>
                </select>
				<button type="submit" name="delete-selected" id="delete-selected">Delete Files</button>
				<button type="submit" name="download_files" id="download_files">Download files</button>
				<button type="submit" name="move-selected" id="move_selected">Move files</button>
			</form>
		<?php } ?>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6 col-lg-6 push-lg-3 col-lg-offset-3">
			<form id="check-folders" method="post" action="<?= ADMIN.'folders/action' ?>">
				<button type="button" id="check-all"><img class="glyph-small" src="<?= IMG."check.png"; ?>"/></button>
				<table class="files-table">
					<thead></thead><th></th><th>Name</th><th>Size(MB)</th><th>Delete</th></thead>
					<tbody>

					<?php
                        if(isset($data['folders']) && !isset($data['folder'])){
                            $folders = $data['folders'];
                        } else {
                            $folders = $folder->children();
                        }
						foreach($folders as $folder) { ?>
							<tr>
								<td><img class="glyph-medium" src="<?= ADMIN.'images/files.png' ?>"/></td>
								<td><a href="<?= ADMIN ?>folders/<?= $folder->folder_id.'/'.$folder->name ?>"> <?= $folder->name ?></a></td>
								<td>Size</td>
								<input type="hidden" name="album_name" value="<?= $folder->name ?>"/>
                                <td><a href="<?= ADMIN.$folder->table.'/destroy/',$folder->get_id()?>" class="btn btn-sm btn-danger">Delete</a></td>
								<td><input class="checkbox" type="checkbox" name="checkbox[]" value="<?= $folder->folder_id ?>"/></td>
							</tr>
					<?php } ?>
					</tbody>
				</table>
				<button type="submit" name="delete-selected" id="delete-selected">Delete Albums</button>
			</form>
		</div>
	</div>
</div>
