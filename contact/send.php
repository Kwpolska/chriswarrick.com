<?php
require_once '/home/Kwpolska/www/captcha.php';
echo "<strong>Checking CAPTCHA...</strong>";
if ($captcha != md5($_GET['captcha'])) die ("failed.<br><div class=\"error\"><strong>ERROR:</strong> This is not the right CAPTCHA phrase. <input type='button' value='Back' onclick=\"Modalbox.show('http://kwpolska.co.cc/contact/form.php', {title: 'Contact form', width: 700}); return false;\"></div>");
echo " bypassed.<br><strong>Sending...</strong>";
$subject = 'Blog Kw: '.$_GET['subject'];
$from = $_GET['name'].' <'.$_GET['mail'].'>';
ob_start();
var_dump($_GET);
$dump = ob_end_flush();
$message = $_GET['message'].PHP_EOL.'---'.PHP_EOL.'      Sent by: '.$_SERVER['REMOTE_ADDR'].PHP_EOL.'      Sent at: '.date(DATE_RFC850).PHP_EOL.'Dump of $_GET: '.$dump;
$head = "From: ".$from.PHP_EOL."MIME-Version: 1.0".PHP_EOL."Content-Type: text/plain; charset=UTF-8".PHP_EOL."Content-Transfer-Encoding: 8bit";
mail('Kwpolska <kwpolska@kwpolska.co.cc>', $subject, $message, $head) or die("failed.<br>
      <div class=\"error\"><strong>ERROR</strong>: Failed to send the form. Please spam me through my generic mail.</div>");
echo ' sent.<br>This window will close itself in 3 seconds.<script type="text/javascript">setTimeout(\'Modalbox.hide()\',3000);</script>';
destroysession();
?>
