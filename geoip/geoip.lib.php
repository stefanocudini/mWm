<?php

//info: http://ipinfodb.com/ip_location_api.php
//user zakis1
//pass ...ip

function geoip($ip, $array=false)
{
	$dircache = '../geoipcache/';
	//directory di cache per le richieste a ipinfodb.com
	$KEY = trim(file_get_contents('../etc/api.ipinfodb.com.key'));
	$info = '';

	if( !preg_match('/^((1?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(1?\d{1,2}|2[0-4]\d|25[0-5])$/', $ip) )
		$info = 'IP Not Valid';
	else
	{
	//API: http://ipinfodb.com/ip_location_api.php
/*
{
	"statusCode" : "OK",
	"statusMessage" : "",
	"ipAddress" : "62.56.230.41",
	"countryCode" : "GB",
	"countryName" : "UNITED KINGDOM",
	"regionName" : "-",
	"cityName" : "-",
	"zipCode" : "-",
	"latitude" : "51.5085",
	"longitude" : "-0.12574",
	"timeZone" : "+00:00"
}
*/
		$hostinfo = 'api.ipinfodb.com';
		$urlinfo = 'http:/'.'/'.$hostinfo.'/v3/ip-city/?key='.$KEY.'&format=json&ip='.$ip;

		if(file_exists($dircache.$ip))
			$info = file_get_contents($dircache.$ip);
		else
		{
			$info = geoip_get($urlinfo, $hostinfo);
			sleep(1);//nel caso di richieste successive, non si deve superare 2 richieste al secondo(vedi API doc)
			$json = json_decode($info,true);
			$info = $json === null ? '' : $info;
			if(!empty($info) and $json['statusCode']=='OK')
				file_put_contents($dircache.$ip, $info) and chmod($dircache.$ip, 0775);
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
