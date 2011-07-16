<?php
header('Content-type: text/plain');
//KwPastebin
//Copyright Kwpolska 2010. Licensed on GPLv3.
include_once './config.php';
try {
    $pdo = createPDO();
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo -> prepare('SELECT code, language FROM `'.$dbtbl.'` WHERE
    `pasteid` = ?');
    try {
        $stmt->execute(array($_GET['id']));

        $obj = $stmt->fetch(PDO::FETCH_OBJ);
        echo $obj->code;
    } catch(PDOException $e){
        echo 'ERROR: ' . $e->getMessage();
    }
    $stmt -> closeCursor();

}
catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}
?>
