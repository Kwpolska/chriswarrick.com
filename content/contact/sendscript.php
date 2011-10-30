---
extends: base-cform.j2
default_block: main
title: Contact Form
description: Thanks for your message.
---
<?php
echo "KBCF sending script\nPerforming sanity checks...";
function showError($contents) { //very helpful.
    die ("ERROR: $contents</div>");
}
/*if ($captcha != md5($_POST['captcha'])) showError("This is not the right
CAPTCHA phrase."); //md5 = idiot-proof*/
if ($_GET['abq'] != $_SESSION['fnum']) showError('This is not the valid
answer.');
if ($_POST['name'] == '' || $_POST['mail'] == '' || $_POST['subject'] == '' ||
$_POST['message'] == '') showError('You haven\'t filled all the fields.');
$subject = '[KBCF] '.$_POST['subject']; //I'm filtering my mail for [KBCF]
$from = $_POST['name'].' <'.$_POST['mail'].'>';
$dump = printf($_POST, true); //just in case...
$message = $_POST['message'].PHP_EOL.'---'.PHP_EOL.'      Sent by:
'.$_SERVER['REMOTE_ADDR'].PHP_EOL.'      Sent at: '.date('l, F jS, Y g:i:s
A').PHP_EOL.'Dump of $_POST: '.PHP_EOL.$dump; //useful^H^H^Hless data also
                                              //included
$message = wordwrap($message, 70); //to satisfy RFC 2822 (Internet Message
                                   //                     Format)
$head = "From: ".$from.PHP_EOL."MIME-Version: 1.0".PHP_EOL."Content-Type:
text/plain; charset=UTF-8".PHP_EOL."Content-Transfer-Encoding: 8bit";
echo " passed.\nSending...";
mail('Kwpolska <kwpolska@kwpolska.tk>', $subject, $message, $head) or
showError("Failed to send the form. Please spam me through my generic mail.");
echo " sent.\nKBCF sending script finished its work.";
session_destroy(); // kill the session.
session_unset();
?>
