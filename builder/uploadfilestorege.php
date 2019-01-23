<?php
$filename=base64_decode($_GET["filename"]);
header('Content-type: application/pdf');
header('Content-Disposition: attachment; filename="' . ''.basename($filename) . '"');
readfile("files/".$filename);
?>