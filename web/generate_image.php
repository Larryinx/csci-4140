<?php
header('Content-Type: image/jpg');
$image = new Imagick('../images/1.jpg');
$image->thumbnailImage(200, 0);
echo $image;
?>