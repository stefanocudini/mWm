<?php

	$conf = file_get_contents(json_decode('../etc/conf.json',true));

	if( !isset($_SERVER["HTTPS"]) )
	{
		header('Location: https://'.$conf['httphost']);
		exit(0);
	}
	
	if( !isset($_SERVER['PHP_AUTH_USER']) or !isset($_SERVER['PHP_AUTH_PW']) )
	{
		header('HTTP/1.1 401 Authorization Required');
		echo "Authorization Required";
		exit(0);
	}

	if($_SERVER['REMOTE_USER']===$conf['httpuser']):
	
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
	endif;
?>
