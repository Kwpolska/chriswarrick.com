<?php
//KwPastebin
//Copyright Kwpolska 2010. Licensed on GPLv3.
include_once './config.php';
ob_start();
if(!isset($_COOKIE['kwpstln'])) setcookie('kwpstln', 1, 2147483640);
?>
<form action="send.php" method="post">
<div id="form">
<textarea name="code" rows="24" cols="80"></textarea><br>
<select name="lng"> 
<optgroup label="Popular">
<option>bash</option> <option>c</option> <option>cpp</option>
<option>csharp</option> <option>css</option> <option>html4strict</option>
<option>java5</option> <option>java</option> <option>javascript</option>
<option>php</option> <option>perl</option> <option>python</option>
<option>rails</option> <option>ruby</option> <option>sql</option> <option
selected="selected">text</option>
</optgroup>
<optgroup label="#"><option>4cs</option>
<optgroup label="A"><option>abap</option> <option>actionscript3</option> <option>actionscript</option> <option>ada</option> <option>apache</option> <option>applescript</option> <option>apt_sources</option> <option>asm</option> <option>asp</option> <option>autoconf</option> <option>autohotkey</option> <option>autoit</option> <option>avisynth</option> <option>awk</option>
<optgroup label="B"><option>basic4gl</option> <option>bf</option> <option>bibtex</option> <option>blitzbasic</option> <option>bnf</option> <option>boo</option> 
<optgroup label="C"><option>caddcl</option> <option>cadlisp</option> <option>cfdg</option> <option>cfm</option> <option>chaiscript</option> <option>cil</option> <option>clojure</option> <option>c_mac</option> <option>cmake</option> <option>cobol</option> <option>cpp-qt</option> <option>cuesheet</option>
<optgroup label="D"><option>dcs</option> <option>delphi</option> <option>diff</option> <option>div</option> <option>dos</option> <option>dot</option> <option>d</option>
<optgroup label="E"><option>ecmascript</option> <option>eiffel</option> <option>email</option> <option>erlang</option>
<optgroup label="F"><option>fo</option> <option>fortran</option> <option>freebasic</option> <option>fsharp</option>
<optgroup label="G"><option>gambas</option> <option>gdb</option> <option>genero</option> <option>genie</option> <option>gettext</option> <option>glsl</option> <option>gml</option> <option>gnuplot</option> <option>groovy</option> <option>gwbasic</option>
<optgroup label="H"><option>haskell</option> <option>hicest</option> <option>hq9plus</option>
<optgroup label="I"><option>icon</option> <option>idl</option> <option>ini</option> <option>inno</option> <option>intercal</option> <option>io</option>
<optgroup label="J"><option>j</option> <option>jquery</option>
<optgroup label="K"><option>kixtart</option> <option>klonec</option> <option>klonecpp</option>
<optgroup label="L"><option>latex</option> <option>lisp</option> <option>locobasic</option> <option>logtalk</option> <option>lolcode</option> <option>lotusformulas</option> <option>lotusscript</option> <option>lscript</option> <option>lsl2</option> <option>lua</option>
<optgroup label="M"><option>m68k</option> <option>magiksf</option> <option>make</option> <option>mapbasic</option> <option>matlab</option> <option>mirc</option> <option>mmix</option> <option>modula2</option> <option>modula3</option> <option>mpasm</option> <option>mxml</option> <option>mysql</option>
<optgroup label="N"><option>newlisp</option> <option>nsis</option>
<optgroup label="O"><option>oberon2</option> <option>objc</option> <option>ocaml-brief</option> <option>ocaml</option> <option>oobas</option> <option>oracle11</option> <option>oracle8</option> <option>oxygene</option> <option>oz</option>
<optgroup label="P"><option>pascal</option> <option>pcre</option> <option>perl6</option> <option>per</option> <option>pf</option> <option>php-brief</option> <option>pic16</option> <option>pike</option> <option>pixelbender</option> <option>plsql</option> <option>postgresql</option> <option>povray</option> <option>powerbuilder</option> <option>powershell</option> <option>progress</option> <option>prolog</option> <option>properties</option> <option>providex</option> <option>purebasic</option>
<optgroup label="Q"><option>qbasic</option> <option>q</option> 
<optgroup label="R"><option>rebol</option> <option>reg</option> <option>robots</option> <option>rpmspec</option> <option>rsplus</option>
<optgroup label="S"><option>sas</option> <option>scala</option> <option>scheme</option> <option>scilab</option> <option>sdlbasic</option> <option>smalltalk</option> <option>smarty</option> <option>systemverilog</option>
<optgroup label="T"><option>tcl</option> <option>teraterm</option> <option>thinbasic</option> <option>tsql</option> <option>typoscript</option>
<optgroup label="U"><option>unicon</option>
<optgroup label="V"><option>vala</option> <option>vbnet</option> <option>vb</option> <option>verilog</option> <option>vhdl</option> <option>vim</option> <option>visualfoxpro</option> <option>visualprolog</option> <option>whitespace</option>
<optgroup label="W"><option>whois</option> <option>winbatch</option></optgroup>
<optgroup label="X"><option>xbasic</option> <option>xml</option> <option>xorg_conf</option> <option>xpp</option></optgroup>
<optgroup label="Z"><option>z80</option></optgroup> </select>
&mdash; Description: <input type="text" name="desc"> &mdash; <input
type="submit" value="SEND">
</div>
<input type="hidden" name="key" value="<?php $_GET['key']; ?>">
</form>
<?php
$content = ob_get_clean();

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
