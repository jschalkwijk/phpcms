<?php 
	$id = $id;
	echo '<tr class="meta">';
	if (in_array($type, $img)){
		if($secured == 0){
			echo '<td class="media"><a class="image_link" href="'.ADMIN.$path.'">'.'<img class="ADMIN" src="'.ADMIN.$thumb_path.'"/></a></td>';
		}
		if($secured == 1){
			echo '<td class="media"><a href="'.ADMIN.'secured/'.$album_name.'/'.$file_name.'">'.'<img class="ADMIN" src="'.ADMIN.'secured/thumbs/'.$album_name.'/'.$thumb_name.'"/>'.'</a></td>';
		}
	}
	echo '<td> '.$name.'</td>';
	echo '<td>'.$type.'</td>';
	echo '<td>Size</td>';
	if (in_array($type, $doc)){
		echo '<td><a href="'.$path.'">'.'<img class="ADMIN" src="'.IMG.'word.png"/>'.'</a></td>';
	}
	if ($type == 'pdf'){
		echo '<td><a class="link-btn" href="'.$path.'">'.'<img class="ADMIN" src="/images/pdf.png"/>'.'</a></td>';
	}
	echo '<td><a class="downloadLink left meta" href="/'.ADMIN.$path.'" download="'.$name.'"><img class="glyph-small" src="'.IMG.'download.png" /></a></td>';
	echo '<td><input class="checkbox left" type="checkbox" name="checkbox[]" value="'.$id.'"/></td>';
	echo '</tr>';
?>