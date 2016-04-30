<?php
function removeDir($path) {
 	$files = glob($path . '/*');
	foreach ($files as $file) {
		is_dir($file) ? removeDir($file) : unlink($file);
	}
	rmdir($path);
 	return;
}
?>