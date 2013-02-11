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

$from = $_POST['name'].' <'.$_POST['mail'].'>';

if ((int)$_POST['sqrt'] !== 4) {
    $head = "From: Kw’s Blog <blog@kwpolska.tk>".PHP_EOL."MIME-Version: 1.0".PHP_EOL."Content-Type: text/plain; charset=UTF-8".PHP_EOL."Content-Transfer-Encoding: 8bit";
    $message = "Warning: the sqrt() test failed in the JSON Contact Form, even though JS let the user through.\nFrom: $from\nSubject: ".$_POST['subject']."\n\n".$_POST['message'];
    $message = wordwrap($message, 70);
    mail('Kwpolska <blogform@kwpolska.tk>', 'WARNING: Contact form breakage', $message, $head);
    die(json_encode(array('status' => 'sqrt')));
}

if (empty($emptyfields)) {
    $message = wordwrap($message, 70);
    $head = "From: ".$from.PHP_EOL."MIME-Version: 1.0".PHP_EOL."Content-Type:
        text/plain; charset=UTF-8".PHP_EOL."Content-Transfer-Encoding: 8bit";
    $ret = mail('Kwpolska <blogform@kwpolska.tk>', $_POST['subject'], $message, $head);

    if ($ret == true) {
        echo json_encode(array('status' => 'ok'));
    } else {
        echo json_encode(array('status' => 'error'));
    }
} else {
    $head = "From: Kw’s Blog <blog@kwpolska.tk>".PHP_EOL."MIME-Version: 1.0".PHP_EOL."Content-Type: text/plain; charset=UTF-8".PHP_EOL."Content-Transfer-Encoding: 8bit";
    $message = "Warning: the fields test failed in the JSON Contact Form, even though JS let the user through.\nFrom: $from\nSubject: ".$_POST['subject']."\n\n".$_POST['message'];
    $message = wordwrap($message, 70);
    mail('Kwpolska <blogform@kwpolska.tk>', 'WARNING: Contact form breakage', $message, $head);
    echo json_encode(array('status' => 'fields', 'empty' => $emptyfields));
}
?>
