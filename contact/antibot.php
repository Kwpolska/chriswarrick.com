<?php
session_start(); //initialize a session
// session protection code
if (!isset($_SESSION['init'])) {
    session_regenerate_id();
    $_SESSION['init'] = true;
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
}

if($_SESSION['ip'] != $_SERVER['REMOTE_ADDR']) {
    session_destroy();
    session_start();
    session_regenerate_id();
    $_SESSION['init'] = true;
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
}
$_SESSION['num'] = rand(1,6);
$_SESSION['let'] = chr($_SESSION['num'] + 64);
$numbers = Array('first', 'second', 'third', 'fourth', 'fifth', 'sixth');
$_SESSION['fnum'] = $numbers[$_SESSION['num'] - 1];
?>
