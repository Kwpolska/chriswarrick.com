<?php
header('Content-Type: text/plain');
$emptyfields = array();

if (empty($_POST['name'])) {
    $emptyfields[] = 'name';
}

if (empty($_POST['mail'])) {
    $emptyfields[] = 'mail';
}

if (empty($_POST['subject'])) {
    $emptyfields[] = 'subject';
}

if (empty($_POST['message'])) {
    $emptyfields[] = 'message';
}

if (empty($_POST['sqrt'])) {
    $emptyfields[] = 'sqrt';
}

if ((int)$_POST['sqrt'] !== 4) {
    die(json_encode(array('status' => 'sqrt')));
}

if (empty($emptyfields)) {
    $from = $_POST['name'].' <'.$_POST['mail'].'>';
    $message = wordwrap($message, 70);
    $head = "From: ".$from.PHP_EOL."MIME-Version: 1.0".PHP_EOL."Content-Type:
        text/plain; charset=UTF-8".PHP_EOL."Content-Transfer-Encoding: 8bit";
    $ret = mail('Kwpolska <kwpolska@kwpolska.tk>', $_POST['subject'], $_POST['message'], $head);

    if ($ret == true) {
        echo json_encode(array('status' => 'ok'));
    } else {
        echo json_encode(array('status' => 'error'));
    }
} else {
    echo json_encode(array('status' => 'fields', 'empty' => $emptyfields));
}
?>
