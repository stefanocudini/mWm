<?php
	
	$conf = json_decode(file_get_contents('./etc/conf.json'),true);
	
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

	if($_SERVER['REMOTE_USER']===$conf['httpuser'])
		readfile('http://127.0.0.1/server-info?'.$_SERVER['QUERY_STRING']);

?>
