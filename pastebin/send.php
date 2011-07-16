<?php
ob_start();
//KwPastebin
//Copyright Kwpolska 2010. Licensed on GPLv3.
include_once './config.php';
if ($open == false && $_POST['key'] != $closedkey) $content = 'Posting is
locked and you haven\'t provided a valid key. <form action="index.php"
method="GET"><input type="text" name="key"> <input type="submit"
value="UNLOCK"></form>';
try {
    $pdo = createPDO();
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $time = time().'.'.rand(0, 9);
    $stmt = $pdo -> prepare('INSERT INTO `'.$dbtbl.'` (`code`, `pasteid`,
    `language`, `timestamp`, `dsc`, `rmable`, `rmid`) VALUES(
                                            :code,
                                            :pasteid,
                                            :language,
                                            :time,
                                            :desc,
                                            1,
                                            :rmid)');
    $pasteid =  uniqid();
    $stmt -> bindValue(':code', $_POST['code'], PDO::PARAM_STR);
    $stmt -> bindValue(':pasteid', $pasteid, PDO::PARAM_STR);
    $stmt -> bindValue(':language', $_POST['lng'], PDO::PARAM_STR);
    $stmt -> bindValue(':time', $time, PDO::PARAM_INT);
    $stmt -> bindValue(':desc', $_POST['desc'], PDO::PARAM_STR);
    $stmt -> bindValue(':rmid', uniqid(rand(1, 10)), PDO::PARAM_INT);
    $ilosc = $stmt -> execute();
    if($ilosc = 0) {
        ob_end_flush();
        die('ERROR: Adding failed!');
    }
    // Okay, we've added it, so now, I have to send user to it...
    header('Location: ./index.php?id='.$pasteid);
}
catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}
ob_end_flush();
?>
