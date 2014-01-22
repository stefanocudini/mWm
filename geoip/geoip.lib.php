<?php

//info: http://ipinfodb.com/ip_location_api.php

function geoip($ip, $array=false)
{
	global $conf;

	$KEY = $conf['ipinfodbapikey'];
	$info = '';

	if( !preg_match('/^((1?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(1?\d{1,2}|2[0-4]\d|25[0-5])$/', $ip) )
		$info = 'IP Not Valid';
	else
	{
		$hostinfo = 'api.ipinfodb.com';
		$urlinfo = 'http:/'.'/'.$hostinfo.'/v3/ip-city/?key='.$KEY.'&format=json&ip='.$ip;

		if(file_exists($conf['geoipdir'].$ip))
			$info = file_get_contents($conf['geoipdir'].$ip);
		else
		{
			$info = geoip_get($urlinfo, $hostinfo);
			sleep(1);//nel caso di richieste successive, non si deve superare 2 richieste al secondo(vedi API doc)
			$json = json_decode($info,true);
			$info = $json === null ? '' : $info;
			if(!empty($info) and $json['statusCode']=='OK')
				file_put_contents($conf['geoipdir'].$ip, $info) and chmod($conf['geoipdir'].$ip, 0775);
		}
	}
	return $array ? json_decode($info, true) : $info;
}

function geoip_get($url, $host, $post=null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: $host"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    return trim($data);
}

?>
