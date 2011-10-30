
layout: page-cform
title: Contact Form

<?php
/*require_once '/home/Kwpolska/www/captcha.php';*/
function showError($contents) { //very helpful functionality.
    die ("failed.<br><div class=\"error\"><strong>ERROR:</strong>
         $contents</div>");
}
echo "<strong>Checking anti-bot question...</strong>";
/*if ($captcha != md5($_POST['captcha'])) showError("This is not the right
CAPTCHA phrase."); //md5 = idiot-proof*/
if ($_GET['abq'] != $_SESSION['fnum']) showError('This is not the valid
answer.');
echo " correct.<br><strong>Checking the fields...</strong>";
if ($_POST['name'] == '' || $_POST['mail'] == '' || $_POST['subject'] == '' ||
$_POST['message'] == '') showError('You haven\'t filled all the fields.');
echo "all filled.<br><strong>Sending...</strong>";
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
mail('Kwpolska <kwpolska@kwpolska.tk>', $subject, $message, $head) or
showError("Failed to send the form. Please spam me through my generic mail.");
echo ' sent.<br>Thanks for sending the message.';
session_destroy(); // kill the session.
session_unset();
?>
