---
layout: page
title: ModalBox Contents
---
<?php
$file = 'http://kwpolska.co.cc/'.$_GET['file'];
if($_GET['mbox'] == 'n') {
   $ch = curl_init(); 
   curl_setopt($ch, CURLOPT_URL, $file); 
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
   echo curl_exec($ch); 
   curl_close($ch);
   echo '<div id="postsftr">End of <strong>'.$file.'</strong>. <a href="'.$file.'" title="ModalBox" onclick="Modalbox.show(this.href, {title: this.title, width: 600}); return false;">Open in ModalBox</a></em></div>';
} else {
   if($_GET['title'] != '') {
      $title = $_GET['title'];
   } else { 
      $title = "ERROR: No title specified. Proceeding...";
   }
   echo "<script type=\"text/javascript\">Modalbox.show('$file', {title: '$title', width: 600}); return false;</script>
<div id=\"postsftr\">A ModalBox shall open with your page. <a href=\"http://kwpolska.co.cc/modalbox.php?mbox=n&file=$file\">Open without ModalBox</a></div>";
}
?>
