<?php
//KwPastebin
//Copyright Kwpolska 2010. Licensed on GPLv3.
include_once './config.php';
include_once './geshi.php';
ob_start();
try
{
   $pdo = new PDO($dbdsn, $dbusr, $dbpwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
   $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   $stmt = $pdo -> prepare('SELECT code, language, dsc FROM `'.$dbtbl.'` WHERE `timestamp` = ?');
   $stmt->execute(array($_GET['id']));
   $obj = $stmt->fetch(PDO::FETCH_OBJ);
   $timestamp = substr($_GET['id'], 0, -2);
   $dsc = $obj->dsc;
   if($dsc == '') $dsc .= 'no user notes';
   echo '<a href="./plain.php?id='.$_GET['id'].'">plaintext</a> &mdash; <a href="./dl.php?id='.$_GET['id'].'">download</a> &mdash; added '.date('F jS, Y \a\t h:i:s A T', $timestamp).' &mdash; <em>'.$dsc.'</em> &mdash; hilighted by <a href="http://qbnz.com/highlighter/">GeSHi</a><br>';
   $geshi = new GeSHI($obj->code, $obj->language);
   $geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 2); $geshi->set_line_style('background: #fcfcfc;', 'background: #f0f0f0;'); //this determines the line styles; even = grey, odd - white-ish.
   echo $geshi->parse_code();
   $stmt -> closeCursor();
}
catch(PDOException $e)
{
   echo 'ERROR: ' . $e->getMessage();
}
$content = ob_get_clean();
savant();
?>
