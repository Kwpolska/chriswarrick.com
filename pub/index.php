<?php
echo '<html>
<head><title>Index of /pub/</title></head>
<body bgcolor="white">
<h1>Index of /pub/</h1><hr><pre><a href="../">../</a>'.PHP_EOL;
$afiles = glob('*');
while (list(, $file) = each($afiles)) {
    if (is_dir($file)) {
        $dirs[] = $file.'/';
    }
    else {
        $files[] = $file;
    }
} // done looping, now we can sort them.

if(isset($dirs)) {
    sort($dirs);
    foreach ($dirs as $file) {
        $attrd = fileatime($file);
        $attrf = date('d-M-Y H:i', $attrd);
        //$fsize = filesize($file);
        $fsize = '-';
        $spacn = 51 - strlen($file);
        $spacs = 20 - strlen($fsize);
        echo '<a href="'.$file.'">'.$file.'</a>'.str_repeat(' ',
            $spacn).$attrf.str_repeat(' ', $spacs).$fsize.PHP_EOL;
        //the line above has 123 chars (including soft tabulation).
    }
}

if(isset($files)) {
    sort($files);
    foreach ($files as $file) {
        $attrd = fileatime($file);
        $attrf = date('d-M-Y H:i', $attrd);
        $fsize = filesize($file);
        //$fsize = '-';
        $spacn = 51 - strlen($file);
        $spacs = 20 - strlen($fsize);
        echo '<a href="'.$file.'">'.$file.'</a>'.str_repeat(' ',
            $spacn).$attrf.str_repeat(' ', $spacs).$fsize.PHP_EOL;
        //the line above has 123 chars (including soft tabulation).
    }
}
echo "</pre><hr>".file_get_contents('HEADER.html')."</body>
    </html>";
?>
