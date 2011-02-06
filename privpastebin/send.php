<?php
ob_start();
//KwPastebin
//Copyright Kwpolska 2010. Licensed on GPLv3.
include_once './config.php';
if ($open == false && $_POST['key'] != $closedkey) $content = 'Posting is locked and you haven\'t provided a valid key. <form action="index.php" method="GET"><input type="text" name="key"> <input type="submit" value="UNLOCK"></form>';
try
{
   $pdo = new PDO($dbdsn, $dbusr, $dbpwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
   $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   $time = time().'.'.rand(0, 9);
   $stmt = $pdo -> prepare('INSERT INTO `'.$dbtbl.'` (`code`, `language`, `timestamp`, `dsc` ) VALUES(
            :code,
            :language,
            :time,
            :desc)');
   $stmt -> bindValue(':code', $_POST['code'], PDO::PARAM_STR);
   $stmt -> bindValue(':language', $_POST['lng'], PDO::PARAM_STR);
   $stmt -> bindValue(':time', $time, PDO::PARAM_STR);
   $stmt -> bindValue(':desc', $_POST['desc'], PDO::PARAM_STR);
   $ilosc = $stmt -> execute();
   if($ilosc = 0) {
      ob_end_flush();
      die('ERROR: Adding failed!');
   }
   // Okay, we've added it, so now, I have to send user to it...
   header('Location: ./index.php?id='.$time);
}
catch(PDOException $e)
{
   echo 'ERROR: ' . $e->getMessage();
}
ob_end_flush();
?>
