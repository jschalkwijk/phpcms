<div class="container large">
	<?php 
		$downloads = content_Content::fetchAll('downloads',0);
		$writer = content_ContentWriter::write($downloads); 
	?>
</div>
