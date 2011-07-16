<?php ob_start();
//KwPastebin
//Copyright Kwpolska 2010. Licensed on GPLv3.

if (isset($_COOKIE['kwpstln'])) {
    if ($_COOKIE['kwpstln'] == 1) {
        setcookie('kwpstln', 0, 2147483640); //cookie will be removed 7 seconds
                                             //before y2k38.  unless the user
                                             //wouldn't do it himself.
    } else {
        setcookie('kwpstln', 1, 2147483640);
    }
    header('Location: ./index.php?id='.$_GET['id']);
    ob_end_flush();
}
?>
