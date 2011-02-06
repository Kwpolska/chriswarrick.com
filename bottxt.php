<?php
//Kw's Bot Status Checker
//Copyright Kwpolska 2010.
$tim = date("H:i:s");
if (file_exists('/home/Kwpolska/pid.KwBot')) {
echo "STATUS: on ($tim CET)";
} else {
echo "STATUS: off ($tim)";
}
?>
