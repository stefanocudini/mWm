<?php
if($_SERVER['REMOTE_USER']=='z4k')
	readfile('http://127.0.0.1/server-info?'.$_SERVER['QUERY_STRING']);

?>
