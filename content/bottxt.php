<?php
//Kw's Bot Status Checker
//Copyright Kwpolska 2010.
$tim = date("H:i:s");
if (file_exists('/home/Kwpolska/kwbot/pid')) {
echo  "<pre>   STATUS: on ($tim CEST)
CONNECTED: yes
 HOSTNAME: Kwpolska@unaffiliated/kwpolska/bot/kwbot</pre>";
} else {
echo "<pre>   STATUS: off ($tim CEST)
CONNECTED: no
 HOSTNAME: n/a</pre>";
}
?>
