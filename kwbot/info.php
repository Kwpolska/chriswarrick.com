---
layout: page
title: KwBot
---
<?php
$file  = 'http://kwpolska.co.cc/kwbot/';
$title = 'KwBot';
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, $file); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
echo curl_exec($ch); 
curl_close($ch);
echo '<div id="postsftr">End of <strong>'.$file.'</strong>. <a href="'.$file.'" title="ModalBox" onclick="Modalbox.show(this.href, {title: \''.$title.'\', width: 600}); return false;">Open in ModalBox</a> or <a href="http://kwpolska.co.cc/polish/kwbot/info.php">view in Polish</a>.</div>';
?>
