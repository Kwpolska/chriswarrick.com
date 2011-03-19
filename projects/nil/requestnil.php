<?php
header('Expires: Fri, 09 Jan 1981 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
file_get_contents('./'.$directory.'/index.html');
?>
