<?php
ob_start();
//KwPastebin
//Copyright Kwpolska 2011. Licensed on GPLv3.
include_once './config.php';
if ($open == false && $_GET['key'] != $closedkey) $content = 'Posting is
locked and you haven\'t provided a valid key. <form action="index.php"
method="GET"><input type="text" name="key"> <input type="submit"
value="UNLOCK"></form>';
if(isset($_GET['rmid'])) {
    try {
        $pdo = createPDO();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare('SELECT rmid FROM `'.$dbtbl.'`
                WHERE `pasteid` = ?');
        $stmt->execute(array($_GET['id']));
        $obj = $stmt->fetch(PDO::FETCH_OBJ);

        if($_GET['rmid'] == $obj->rmid) {
            $stmt->closeCursor();
            $stmt = $pdo->prepare('DELETE FROM `'.$dbtbl.'`
                    WHERE `pasteid` = ?');
            $stmt->execute(array($_GET['id']));
            echo "The paste was removed.";
        } else {
            echo "Incorrect code. Did not remove.";
        }
        $stmt->closeCursor();
    }
    catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
} else {
    echo '<form action="" method="GET"><p>
          <input type="text" name="pasteid" placeholder="Paste ID"> &mdash; Paste ID<br>
          <input type="text" name="rmid"  placeholder="Removal ID"> &mdash; Removal ID<br>
          <input type="submit" value="Remove (remember: you can\'t go back!)">
          </p>';
}
ob_end_flush();
?>
