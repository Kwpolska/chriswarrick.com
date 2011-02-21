<?php
require_once '/home/Kwpolska/www/captcha.php';
function showError($contents) { //a very helpful functionality.
die ("failed.<br><div class=\"error\"><strong>ERROR:</strong> $contents <input type='button' value='Back' onclick=\"Modalbox.show('http://kwpolska.co.cc/contact/form.php', {title: 'Contact form', width: 700}); return false;\"></div>");
}
echo "<strong>Checking CAPTCHA...</strong>";
if ($captcha != md5($_GET['captcha'])) showError("This is not the right CAPTCHA phrase."); //md5 = idiot-proof
echo " bypassed.<br><strong>Checking the fields...</strong>";
if ($_GET['name'] == '' || $_GET['mail'] == '' || $_GET['subject'] == '' || $_GET['message'] == '') showError('You haven\'t filled all the fields.');
echo "all filled.<br><strong>Sending...</strong>";
$subject = '[KBCF] '.$_GET['subject']; //I'm filtering my mail for [KBCF]
$from = $_GET['name'].' <'.$_GET['mail'].'>';
$dump = printf($_GET, true); //just in case...
$message = $_GET['message'].PHP_EOL.'---'.PHP_EOL.'      Sent by: '.$_SERVER['REMOTE_ADDR'].PHP_EOL.'      Sent at: '.date('l, F jS, Y g:i:s A').PHP_EOL.'Dump of $_GET: '.PHP_EOL.$dump; //useful^H^H^Hless data also included
$message = wordwrap($message, 70); //to satisfy RFC 2822 (Internet Message Format)
$head = "From: ".$from.PHP_EOL."MIME-Version: 1.0".PHP_EOL."Content-Type: text/plain; charset=UTF-8".PHP_EOL."Content-Transfer-Encoding: 8bit";
mail('Kwpolska <kwpolska@kwpolska.co.cc>', $subject, $message, $head) or showError("Failed to send the form. Please spam me through my generic mail.");
echo ' sent.<br>This window will close itself in 3 seconds.<script type="text/javascript">setTimeout(\'Modalbox.hide()\',3000);</script>';
destroysession();
?>
