<?php
header('Content-type: image/png');
//Kw's Bot Status Checker
//Copyright Kwpolska 2010.
$tim = date("H:i:s");
if (file_exists('/home/Kwpolska/kwbot/pid')) {
$txt  = "STATUS: on ($tim CEST)";
} else {
$txt = "STATUS: off ($tim CEST)";
}
$img = imagecreatetruecolor(250, 20);
$blk = imagecolorallocate($img,   0,  0,  0);
$wht = imagecolorallocate($img, 255,255,255);
imagefilledrectangle($img, 0, 0, 399, 29, $wht);
imagestring($img, 5, 2, 1, $txt, $blk);
imagepng($img);
imagedestroy($img);
?>
