<?php

if( isset($_REQUEST['ip']) and !empty($_REQUEST['ip']) )
	$ip = trim($_REQUEST['ip']);
else
	if( isset($_SERVER['QUERY_STRING']) and !empty($_SERVER['QUERY_STRING']) )
		$ip = trim($_SERVER['QUERY_STRING']);
	else
		$ip = $_SERVER['REMOTE_ADDR'];

header("Content-type: text/plain");

require_once('geoip.lib.php');

echo geoip($ip);

?>
