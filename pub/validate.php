<?php
echo '<pre>';
echo "validate.php\n\n";
echo 'initializing...';
$valid = "
            _ _     _
__   ____ _| (_) __| |
\ \ / / _` | | |/ _` |
 \ V / (_| | | | (_| |
  \_/ \__,_|_|_|\__,_|

This is a valid, up-to-date file.";
$invalid = "
 _                 _ _     _
(_)_ ____   ____ _| (_) __| |
| | '_ \ \ / / _` | | |/ _` |
| | | | \ V / (_| | | | (_| |
|_|_| |_|\_/ \__,_|_|_|\__,_|

This is NOT a valid file.  It might also be too old.";
$ood = "
             _      _       _           _
  ___  _   _| |_ __| | __ _| |_ ___  __| |
 / _ \| | | | __/ _` |/ _` | __/ _ \/ _` |
| (_) | |_| | || (_| | (_| | ||  __/ (_| |
 \___/ \__,_|\__\__,_|\__,_|\__\___|\__,_|

This file is outdated, but not too much.  Update it.";
$checksums = array(
    'smspremium'    => '1119.5',
);
$oldchecksums = array(
    'smspremium'    => '1111.5',
);
function checksum($object, $value) {
    global $valid, $invalid, $ood, $checksums, $oldchecksums;
    if($checksums[$_GET['object']] == $_GET['value']) {
        echo $valid;
    } else {
        if($oldchecksums[$_GET['object']] == $_GET['value']) {
            echo $ood;
        } else {
             echo $invalid;
        }
    }
}
echo "done\n\n";

echo 'type:   '.$_GET['type'].PHP_EOL;
echo 'object: '.$_GET['object'].PHP_EOL;
echo 'value:  '.$_GET['value'].PHP_EOL;
switch($_GET['type']) {
    case 'checksum':
        if(!isset($checksums[$_GET['object']])) {
            echo 'no such object';
        } else {
            checksum($_GET['object'], $_GET['value']);
        }
        break;
    default:
        echo 'not implemented';
}

echo '</pre>';
?>
