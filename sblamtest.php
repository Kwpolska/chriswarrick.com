<?php
/** Sblam! 1.3 http://sblam.com/instalacja.html
*/

/** Wysyła pola z $_POST do sprawdzenia na serwerze Sblam!.
	* @param fieldnames tablica zawierająca *nazwy* pól w kolejności: treść, autor, e-mail autora, www autora. Może być NULL zamiast nazwy, jeśli nie ma takiego pola w formularzu.
	* @param apikey klucz API wygenerowany na http://sblam.com/key.html
*/
function sblamtestpost($fieldnames = NULL, $apikey = NULL)
{
	global $_sblam_last_id, $_sblam_last_error;
	$_sblam_last_id=$_sblam_last_error=NULL;

	if (!count($_POST)) return NULL;

	if (NULL === $apikey) $apikey = "default";
	$in = array(
	 'uid' => _sblamserveruid(),
	 'uri' => empty($_SERVER['REQUEST_URI'])?$_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING']:$_SERVER['REQUEST_URI'],
	 'host'=> empty($_SERVER['HTTP_HOST'])?$_SERVER['SERVER_NAME']:$_SERVER['HTTP_HOST'],

	 'ip'	 => $_SERVER['REMOTE_ADDR'],
	 'time'=> time(),

	 'cookies'=> count($_COOKIE)?1:0,
	 'session'=> isset($_COOKIE[session_name()])?1:0,
	 'sblamcookie'=> isset($_COOKIE['sblam_'])?$_COOKIE['sblam_']:'',

	 'salt'=>'x'.mt_rand().time(),
	);

	if (is_array($fieldnames)) foreach($fieldnames as $key => $val)
		$in['field_'.$key] = $val;

	foreach($_POST as $key => $val)
		$in['POST_'.$key] = stripslashes(is_array($val)?implode("\n",$val):$val);

	if (function_exists("getallheaders"))
	foreach(getallheaders() as $header => $val)
	{
		$in['HTTP_'.strtr(strtoupper($header),"-","_")] = $val;
	}
	else foreach($_SERVER as $key => $val)
	{
		if (substr($key,0,5) !== 'HTTP_') continue;
		$in[$key] = stripslashes($val);
	}
	unset($in['HTTP_COOKIE']);
	unset($in['HTTP_AUTHORIZATION']);

	$data = '';
	foreach($in as $key => $val)
		$data .= strtr($key,"\0"," ")."\0".strtr($val,"\0"," ")."\0";

	if (strlen($data) > 300000) return 0;

	if ($compress = (strlen($data) > 5000 && function_exists('gzcompress'))) $data = gzcompress($data,1);

	if (function_exists('fsockopen'))
	{
		$hosts = array('api.sblam.com','api2.sblam.com','spamapi.geekhood.net');
		foreach($hosts as $host)
		{
			$request	= "POST / HTTP/1.1\r\n" .
			"Host:$host\r\n" .
			"Connection:close\r\n" .
			"Content-Type:application/x-sblam;sig=".md5("^&$@$2\n$apikey@@").md5($apikey . $data).($compress?";compress=gzip":'')."\r\n" .
			"Content-Length:" . strlen($data) . "\r\n".
			"\r\n".$data;

			$fs = @fsockopen($host, 80, $errn, $errs, 5);
			if ($fs !== false && function_exists('stream_set_timeout')) stream_set_timeout($fs, 15);
			if ($fs !== false && fwrite($fs, $request))
			{
				$response = '';
				while(!feof($fs))
				{
					$response .= fread($fs,1024);
					if (preg_match('!\r\n\r\n.*\n!',$response)) break;
				}
				fclose($fs);
				if (preg_match('!HTTP/1\..\s+(\d+\s+[^\r\n]+)\r?\n((?:[^\r\n]+\r?\n)+)\r?\n(.+)!s',$response,$out))
					if (intval($out[1]) == 200)
						if (preg_match('!^(-?\d+):([a-z0-9-]{0,42}):([a-z0-9]{32})!',$out[3],$res))
							if (md5($apikey . $res[1] . $in['salt']) === $res[3])
							{
								$_sblam_last_id = $res[2];
								return $res[1];
							}
							else trigger_error($_sblam_last_error.="Sblam: Rezultat od serwera $host ma niepoprawny podpis\n");
						else trigger_error($_sblam_last_error.="Sblam: Awaria serwera $host. Otrzymany rezultat ma niepoprawny format ".htmlspecialchars($out[3])."\n");
					else trigger_error($_sblam_last_error.="Sblam: Komunikat serwera $host: ".htmlspecialchars(substr($out[1],0,80))."\n");
				else trigger_error($_sblam_last_error.="Sblam: Niepoprawny rezultat otrzymany od serwera $host\n");
			}
			else trigger_error($_sblam_last_error.="Sblam: Problem komunikacji z serwerem $host - $errn:$errs\n");
		}
	}
	else trigger_error($_sblam_last_error.="Sblam: Brak wymaganego rozszerzenia sockets (fsockopen)\n");
	return 0;
}

/** Funkcja pomocnicza dla Sblam!, która generuje identyfikator serwera (nie używaj) */
function _sblamserveruid()
{
	return md5(phpversion() . $_SERVER['HTTP_HOST'] . __FILE__);
}

/** Podaje URL pod którym użytkownik może zgłosić błąd filtru. */
function sblamreporturl()
{
	global $_sblam_last_id;
	return "http://sblam.com/report/$_sblam_last_id";
}

/** Zwraca ostatni komunikat o błędzie lub NULL, jeśli sprawdzanie odbyło się bezbłędnie
		Wtyczka próbuje komunikacji z kilkoma serwerami, więc może zainstnieć sytuacja, że post zostanie sprawdzony mimo błędów.
*/
function sblamlasterror()
{
	global $_sblam_last_error;
	return $_sblam_last_error;
}
