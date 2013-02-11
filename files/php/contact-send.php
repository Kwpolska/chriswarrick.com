<?php
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
    die('<strong>Failed!</strong>  Reason: wrong answer to sqrt(16) security question.');
}

if (empty($emptyfields)) {
    $from = $_POST['name'].' <'.$_POST['mail'].'>';
    $message = wordwrap($message, 70);
    $head = "From: ".$from.PHP_EOL."MIME-Version: 1.0".PHP_EOL."Content-Type: text/plain; charset=UTF-8".PHP_EOL."Content-Transfer-Encoding: 8bit";
    $ret = mail('Kwpolska <blogform@kwpolska.tk>', $_POST['subject'], $message, $head);

    if ($ret == true) {
        echo '<strong>Done!</strong>  Your message was successfully sent.';
    } else {
        echo '<strong>Failed!</strong>  An unknown error occurred.';
    }
} else {
    echo '<strong>Failed!</strong>  The following fields were empty: '.implode(', ', $emptyfields).'.';
}
?>
