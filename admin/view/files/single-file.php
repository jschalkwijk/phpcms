<?php
	echo '<tr class="meta">';
	if (in_array($single->type, $img)){
		if($single->secured == 0){
			echo '<td class="media"><a class="image_link" href="'.ADMIN.$single->path.'">'.'<img class="ADMIN" src="'.ADMIN.$single->thumb_path.'"/></a></td>';
		}
		if($single->secured == 1){
			echo '<td class="media"><a href="'.ADMIN.'secured/'.$single->album_name.'/'.$single->file_name.'">'.'<img class="ADMIN" src="'.ADMIN.'secured/thumbs/'.$single->albums_album_name.'/'.$single->thumb_name.'"/>'.'</a></td>';
		}
	}
	echo '<td> '.$single->name.'</td>';
	echo '<td>'.$single->type.'</td>';
	echo '<td>Size</td>';
	if (in_array($single->type, $doc)){
		echo '<td><a href="'.$single->path.'">'.'<img class="ADMIN" src="'.IMG.'word.png"/>'.'</a></td>';
	}
	if ($single->type == 'pdf'){
		echo '<td><a class="link-btn" href="'.$single->path.'">'.'<img class="ADMIN" src="/images/pdf.png"/>'.'</a></td>';
	}
	echo '<td><a class="downloadLink left meta" href="'.ADMIN.$single->path.'" download="'.$single->name.'"><img class="glyph-small" src="'.IMG.'download.png" /></a></td>';
	echo '<td><input class="checkbox left" type="checkbox" name="checkbox[]" value="'.$single->get_id().'"/></td>';
	echo '</tr>';
?>