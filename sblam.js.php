<?php

require_once "sblamtest.php";

function sblamchallange()
{
	$serveruid = _sblamserveruid();

	$magic = dechex(mt_rand()) . ';' . dechex(time()) . ';' . $_SERVER['REMOTE_ADDR'];
	$magic = addslashes(md5($serveruid . $magic) . $magic);
	
	if (!headers_sent())
	{
		header("Content-Type: text/javascript;charset=UTF-8");
		header("Cache-Control: private,max-age=3600");
		setcookie('sblam_', md5($magic . $serveruid), time()+3600);
	}

	$fieldname = 'sc'.abs(crc32($serveruid)); 

echo <<<JS
(function(){
var f = document.getElementsByTagName('form');
f = f && f.length && f[f.length-1]
if (!f || f.$fieldname) return
setTimeout(function(){
var i = document.createElement('input')
i.setAttribute('type','hidden')
i.setAttribute('name','$fieldname')
i.setAttribute('value','$magic;' + (new Date()/1000).toFixed())
f.appendChild(i)
/*@cc_on @*/
/*@if (@_jscript_version < 5.9)
	i.name = '$fieldname';
	i.parentNode.removeChild(i); f.innerHTML += (''+i.outerHTML).replace(/>/,' name="$fieldname">');
/*@end @*/
var dclick,o = f.onsubmit
f.onsubmit = function()
{
	if (dclick) return false
	if (this.elements.$fieldname) this.elements.$fieldname.value += ';' + (new Date()/1000).toFixed()
	if (!o || false !== o()) {dclick=true;setTimeout(function(){dclick=false},4000); return true}
	return false;
}
},1000)
})()
JS;
}

sblamchallange();
