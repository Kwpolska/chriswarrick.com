<?php
/* Kw's CAPTCHA
 * Copyright Kwpolska 2011. Licensed under GPLv3.
 */
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
switch($_GET['action']) {
   case 'img':
      $chars              = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789'; //Ain't using 0 and O to prevent confusion.
      $captcha            = imagecreatefrompng('/home/Kwpolska/www/images/captcha.png');
      $color              = imagecolorallocate($captcha, 255, 255, 255);
      $font               = '/home/Kwpolska/www/includes/DejaVuSansMono.ttf';
      $phrase             = $chars[rand(0, strlen($chars)-1)].$chars[rand(0, strlen($chars)-1)].$chars[rand(0, strlen($chars)-1)].$chars[rand(0, strlen($chars)-1)].$chars[rand(0, strlen($chars)-1)].$chars[rand(0, strlen($chars)-1)]; //this code is not very good, but I don't care. Have a suggestion? Mail me.
      $_SESSION['phrase'] = md5($phrase); //This is idiot-proof.
      imagettftext(
            $captcha,
            20,
            2,
            rand(0, 8),
            25,
            $color,
            $font,
            $phrase);
      header('Content-Type: image/png');
      imagepng($captcha);
      break;
   case 'text':
      $chars              = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789'; //Ain't using 0 and O to prevent confusion.
      $phrase             = $chars[rand(0, strlen($chars)-1)].$chars[rand(0, strlen($chars)-1)].$chars[rand(0, strlen($chars)-1)].$chars[rand(0, strlen($chars)-1)].$chars[rand(0, strlen($chars)-1)].$chars[rand(0, strlen($chars)-1)]; //this code is not very good, but I don't care. Have a suggestion? Mail me.
      $_SESSION['phrase'] = md5($phrase); //This is idiot-proof.
      echo '<h1>Kw\'s Captcha</h1>'.PHP_EOL.'<strong>Decode the following code using (in Unix) <code>echo \''.base64_encode($phrase).'\' | base64 -d</code></strong>:'.PHP_EOL;
      echo '<strong>Your code is:</strong> <code>'.base64_encode($phrase).'</code>';
      break;
   default:
      // We weren't asked to provide the images. We must provide the phrase.
      $captcha = $_SESSION['phrase'];
      session_destroy(); // and kill the session.
      session_unset();
}
?>
