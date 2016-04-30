<?php require_once('globals.php');
// formulier in een apparte file, en dit script ook om aan te roepen in de javascript file.
// waarom lukt het niet als ik deze aanroep vanuit JS vanuit een andere folder dan de admin folder?
// hij probeert de uploade file naar blocks/files te uploaden maar die folder bestaat daar nier,
// ook kan hij de globals niet vinden.. dus moet hij in de admin folder staan.
?>
<!DOCTYPE html>
<html>
<head>
<link type="text/css" rel="stylesheet" href="blocks/css/drag-drop.css"/>
</head>
<body>
<div id="uploads"></div>
<div class="dropzone" id="dropzone">Drop files here</div>
<script src="blocks/js/drag-drop.js"></script>
</body>
</html>
