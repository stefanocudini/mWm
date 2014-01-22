<?php

	$conf = file_get_contents(json_decode('etc/conf.json',true));

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
		phpinfo();

?>