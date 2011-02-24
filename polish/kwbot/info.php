---
layout: page
title: KwBot
---
<?php
$file  = 'http://kwpolska.co.cc/polish/kwbot/';
$title = 'KwBot';
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, $file); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
echo curl_exec($ch); 
curl_close($ch);
echo '<div id="postsftr">Koniec pliku <strong>'.$file.'</strong>. <a href="'.$file.'" title="ModalBox" onclick="Modalbox.show(this.href, {title: \''.$title.'\', width: 600}); return false;">Otwórz w ModalBoksie</a> lub <a href="http://kwpolska.co.cc/kwbot/info.php">zobacz wersję angielską</a>.</div>';
?>
