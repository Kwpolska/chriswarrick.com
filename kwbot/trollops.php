<?php
echo "<strong>Funkcje operatorskie KwBota wykorzystywać mogą:</strong> ";
$ops = file_get_contents('/home/Kwpolska/kwbot/conf/trollowniaops.txt');
$ruskie = array('а', 'о', 'ϳ');
$polske = array('a', 'o', 'j');
$ops = str_replace($ruskie, $polske, $ops);
echo $ops;
?>
