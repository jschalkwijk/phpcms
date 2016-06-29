<?php 
	$id = $id;
	echo '<div class="media">';
		echo '<div class="meta">';
			echo '<input class="checkbox left" type="checkbox" name="checkbox[]" value="'.$id.'"/>';
			echo '<div class="left center"> '.$name.'</div>';
		echo '</div>';
	if (in_array($type, $img)){
		if($secured == 0){
			echo '<div class="center"><a class="image_link" href="'.ADMIN.$path.'">'.'<img class="ADMIN" src="'.ADMIN.$thumb_path.'"/></a></div>';
		}
		if($secured == 1){
			echo '<a href="'.ADMIN.'secured/'.$album_name.'/'.$file_name.'">'.'<img class="ADMIN" src="'.ADMIN.'secured/thumbs/'.$album_name.'/'.$thumb_name.'"/>'.'</a>';
		}
	}
	if (in_array($type, $doc)){
		echo '<a href="'.$path.'">'.'<img class="ADMIN" src="'.IMG.'word.png"/>'.'</a>';
	}
	if ($type == 'pdf'){
		echo '<a class="link-btn" href="'.$path.'">'.'<img class="ADMIN" src="/images/pdf.png"/>'.'</a>';
	}
	echo '<a class="downloadLink left meta" href="/'.ADMIN.$path.'" download="'.$name.'"><img class="glyph-small" src="'.IMG.'download.png" /></a>';
	echo '</div>';
?>