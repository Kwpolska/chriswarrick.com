<?php
//KwPastebin
//Copyright Kwpolska 2010. Licensed on GPLv3.
include_once './config.php';
include_once './geshi.php';
ob_start();

if ($open == false && $_GET['key'] != $closedkey) $content = 'Posting is locked
and you haven\'t provided a valid key. <form action="index.php"
method="GET"><input type="text" name="key"> <input type="submit"
value="UNLOCK"></form>';

if(isset($_GET['id'])) {
    include_once './geshi.php';
    ob_start();
    try {
        $pdo = createPDO();
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare('SELECT * FROM `'.$dbtbl.'` WHERE
                               `pasteid` = ?');
        //fields: pasteid, code, language, timestamp, dsc, rmable, rmid
        $stmt->execute(array($_GET['id']));
        $obj = $stmt->fetch(PDO::FETCH_OBJ);
        $dsc = $obj->dsc;
        if($dsc == '') $dsc .= 'no user notes';
        echo '<a href="./plain.php?id='.$_GET['id'].'">plaintext</a> &mdash; <a
        href="./dl.php?id='.$_GET['id'].'">download</a> &mdash; added '.date('F
        jS, Y \a\t h:i:s A T', $obj->timestamp).' &mdash; <em>'.$dsc.'</em>
        &mdash; <a href="./lnumbers.php?id='.$_GET['id'].'">Toggle line
        numbers</a> &mdash; hilighted by <a
        href="http://qbnz.com/highlighter/">GeSHi</a><br>';
        $geshi = new GeSHI($obj->code, $obj->language);

        $geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 2);
        $geshi->set_line_style('background: #fff;', 'background: #f0f0f0;');
        //this determines the line styles.  odd (white)          even (grey)
        if($_COOKIE['kwpstln'] == 0) {
            $geshi->enable_line_numbers(GESHI_NO_LINE_NUMBERS);
        }
        echo $geshi->parse_code();
        if($obj->rmable == '1') {
            echo "Removal ID: ".$obj->rmid.'<br>';
            echo "You can use the id at the deletion page.  Don't share it.  
            The ID willn't show up anymore.";
            $stmt->closeCursor();
            $stmt = $pdo->prepare('UPDATE `'.$dbtbl.'` SET `rmable` = 0
            WHERE `pasteid` = ?');
            $stmt->execute(array($_GET['id']));
         } 
        $stmt->closeCursor(); // cheating.  I have to close either the select
    }                         // or update.
    catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
    $content = ob_get_clean();
}

savant();
?>
