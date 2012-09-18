---
extends: tools.j2
default_block: main
title: IP/User Agent
description: Basic browser information.
---
<?php
if(!isset($_SERVER['REMOTE_HOST'])) {
    $rhost = '?';
}
echo '<p>'.$_SERVER['REMOTE_ADDR'].' ('.$rhost.')</p>';

echo '<p>'.$_SERVER['HTTP_USER_AGENT'].'</p>';
?>
