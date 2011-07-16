<?php
//KwInstaller (modded)
//Part of KRU
//Copyright Kwpolska 2010. Licensed on GPLv3.
include_once './config.php';
if($configured == false) {
   echo "It seems like you haven't configured it.  Read README.md.";
die();
}
try {
    $pdo = new PDO($dbdsn, $dbusr, $dbpwd, array(PDO::MYSQL_ATTR_INIT_COMMAND =>
    "SET NAMES utf8"));
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->exec('ALTER TABLE  `'.$dbtbl.'` ADD `pasteid` VARCHAR(100) NOT NULL');
    $stmt->closeCursor;
    echo "Updated the database structure according to the 03/13/11 update.";
} catch(PDOException $e) {
   echo 'Error message:' . $e->getMessage();
}
?>
