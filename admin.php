<?php

	if(!is_file('./etc/conf.json'))
		die('create new file: ./etc/conf.json');

	$conf = json_decode(file_get_contents('./etc/conf.json'),true);

	$bantime = shell_exec("grep bantime /etc/fail2ban/jail.conf | grep -v \# | head -n1 | cut -d'=' -f2");
	$uptime  = shell_exec('uptime | cut -d"," -f1');
	$netstat = shell_exec('netstat -ntpa4 | cut -c 20- | tail -n+3 | sort');
	$pstree  = shell_exec('ps -eo "%u%a" kuid,args | uniq | grep -v "\[\|grep\|uniq\|ps\ -eo\|RUSER" | sed "s/ .*\/[s]*bin\//\t/"');
	$logins  = shell_exec('last -8 -aiF | head -n-2');

	$banfile   = $conf['logdir'].'banip-list.list';
	$unbanfile = $conf['logdir'].'unbanip-list.list';

	$banlist     = file($banfile);
	$unbanlist   = file($unbanfile);
	$ipsbanlist  = file($conf['logdir'].'lastlog-ipsban.log');
	$usersshlist = file($conf['logdir'].'lastlog-userssh.log');

	if( !isset($_SERVER["HTTPS"]) )
	{
		header('Location: https://'.$conf['httphost']);
		echo "Require ssl";
		exit(0);
	}
	
	if( !isset($_SERVER['PHP_AUTH_USER']) or !isset($_SERVER['PHP_AUTH_PW']) )
	{
	    header('HTTP/1.1 401 Authorization Required');
		echo "Authorization Required";
		exit(0);
	}
	
	if(isset($_GET['clear'])):

		if(isset($_GET['ban']))
			file_put_contents($banfile,'');
				
		elseif(isset($_GET['unban']))
			file_put_contents($unbanfile,'');
			
		header("Location: ./");
		exit(0);
	endif;

	if(isset($_GET['unban']) and !empty($_GET['ip'])):

		$ip = trim($_GET['ip']);

		if( preg_match('/^([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/',$ip,$r) ):

			if( !in_array($ip, file($unbanfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)) )
				file_put_contents($unbanfile, $ip."\n", FILE_APPEND);

			header("Location: ./");
		else:
			die("ip non valido");
		endif;
	endif;

	if(isset($_GET['ban']) and !empty($_GET['ip'])):

		$ip = trim($_GET['ip']);

		if( preg_match('/^([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/',$ip,$r) ):

			if( !in_array($ip, file($banfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)) )
				file_put_contents($banfile, $ip."\n", FILE_APPEND);

			header("Location: ./");
		else:
			die("ip non valido");
		endif;
	endif;

	if(isset($_GET['geoip']) and !empty($_GET['geoip'])):
	
		require_once('geoip/geoip.lib.php');

		$ip = trim($_GET['geoip']);

		header("Content-type: text/plain");

		echo geoip($ip);

	endif;

	if(isset($_GET['info'])):
		readfile('http://127.0.0.1/server-info');
	endif;

	if(isset($_GET['status'])):
		readfile('http://127.0.0.1/server-status');
	endif;

	if(isset($_GET['phpinfo'])):
		phpinfo();
	endif;
	
	// if(isset($_GET['greplog']) and !empty($_GET['greplog'])):
	// 	//TODO
	// 	//grep from /var/log/apache2/*access.log
	// endif;

?><html>
<head>
	<title>Admin <?php echo $conf['httphost']; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 	
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=0.5, user-scalable=no">	
	<link rel="stylesheet" href="style.css" />
</head>
<body>
<div id="top_wrap">
	<div id="top" style="display:none">
		<div style="float:left">
			<?php echo $conf['httphost']; ?> &bull; <em class="ip"><small><?php echo $_SERVER['SERVER_ADDR']; ?></small></em>
		</div>
		
		<div style="float:right">
			<span><?php
				echo isset($_SERVER['REMOTE_USER']) ?
					'Http User: '.$_SERVER['REMOTE_USER'] : 
					'<b style="color:red">No Http Auth!!</b>';
			?></span>	
			 &bull; <span><?php echo $uptime; ?></span>
		</div>
	
		<div id="menu" class="box">
			<span><img src="imgs/apache.ico" /> Apache 
				<a href="<?php echo $_SERVER['PHP_SELF']; ?>?info"> Info</a>, 
				<a href="<?php echo $_SERVER['PHP_SELF']; ?>?status"> Status</a>				
			</span>
			| <a href="<?php echo $_SERVER['PHP_SELF']; ?>?phpinfo"><img src="imgs/php.ico" /> PHP Info</a>
			| <a href="phpmyadmin/"><img src="imgs/phpmyadmin.ico" /> PhpMyAdmin</a>
			| <a href="phppgadmin/"><img src="imgs/phppgadmin.ico" /> PhpPgAdmin</a>
			<!-- | <a href="rockmongo/"><img src="imgs/rockmongo.ico" /> RockMongo</a> -->
		</div>
	
		<div id="ban" class="box form">
			<form action="" method="get">
				<input type="hidden" name="ban" value="" />
				<label><b>Ban </label><br />
				<input name="ip" type="text" value="" /><br />
				<input type="submit" value="Add" />
				<input type="submit" name="clear" value="Clear" />
			</form>
			<pre style="overflow:hidden"><?php echo implode('<br>',$banlist); ?></pre>
		</div>
	
		<div id="unban" class="box form">
			<form action="" method="get">
				<input type="hidden" name="unban" value="" />
				<label><b>Unban </b></label><br />
				<input name="ip" type="text" value="<? echo $_SERVER['REMOTE_ADDR']; ?>" /><br />
				<input type="submit" value="Add" />
				<input type="submit" name="clear" value="Clear" />			
			</form>
			<pre style="overflow:hidden"><?php echo implode('<br>',$unbanlist); ?></pre>
		</div>
	
		<div class="box form">
			<form id="geoipform" action="" method="get">
				<label><b>Geoip </b></label><br />
				<input name="geoip" type="text" value="<? echo $_SERVER['REMOTE_ADDR']?>" /><br />
				<input type="submit" value="View" />
			</form>
		</div>	
		<!--div class="box">
			<form action="" method="get">
				<label><b>Grep Log </b></label>
				<input name="greplog" type="text" size="16" value="" />
				<input type="submit" value="View" />
			</form>
		</div-->
	</div>
	<div id="logo"><small>powered by</small> <a target="_blank" href="http://labs.easyblog.it/mwm/"> mWm </a></div>
	<a id="topup" class="down" href="#down"></a>
</div>

<div id="bottom">

<div class="box">
	<b>Fail2ban IPs Banned</b>
	<small>(bantime: <?php echo Sec2Time($bantime); ?>)</small>
	<pre id="ipsbanned"><?php

	$ff = is_array($ipsbanlist)>0 ? $ipsbanlist : array();

	$banned = array();
	$rec = array();
	$chain = $ip = '';
	foreach($ff as $l)
	{
		if(preg_match('/^Chain fail2ban-(.*) \(.*$/',$l,$r))
		{
			$chain = $r[1];
			continue;
		}
		elseif(preg_match('/^DROP .* ([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}) .*$/',$l,$r))
			$ip = $r[1];
		else
			continue;
			
		$rec[$ip][]= $chain;
		#echo sprintf("%-16s",$chain)." $ip\n";
	}
	ksort($rec);
	foreach($rec as $ip=>$chains)
		echo sprintf("%-16s",$ip).' '.implode(', ',$chains)."\n";
	//*/
	?></pre>
</div>

<div class="box">
	<b>Ports</b>
	<pre><?php echo $netstat; ?></pre>
</div>

<div class="box">
	<b>Processes</b>
	<pre><?php echo $pstree; ?></pre>
</div>

<div class="box">
	<b>PHP Errors</b>
	<pre id="phperror"><?php readfile($logdir.'lastlog-php.log'); ?></pre>
</div>

<div id="lastlogins" class="box">
	<b>SSH Last Sessions</b>	
	<pre><?php echo $logins; ?></pre>
</div>

<div class="box">
	<b>SSH Users Failed</b>
	<div id="userfailed">
	<?php
	
	$uu = is_array($usersshlist)>0 ? $usersshlist : array();

	foreach($uu as $row)
	{
		list($n,$user) = explode(' ',trim($row));
		$s = 1 + ( intval($n) / 10);
		$s = min($s,2.25);//dimensioni massime in em
		$user = $n>1 ? htmlentities($user)."<small> &times; ${n}</small>" : htmlentities($user);
		echo "<span style=\"font-size:${s}em\">${user}</span> ";
	}
	?>
	</div>
</div>

</div>

<pre id="geoipbox" class="box"></pre>

<script src="jquery-1.9.0.min.js"></script>
<script src="admin.js"></script>
</body>
</html>
<?

function Sec2Time($time, $sep=' ')
{
  	$time = intval($time);

	$vals = array();
	
    if($time >= 31556926){
		$v = floor($time/31556926);
		$k = $v>1 ? 'years' : 'year'; 
		$vals[$k] = $v;
		$time = ($time%31556926);
    }
    if($time >= 86400){
		$v = floor($time/86400);
		$k = $v>1 ? 'days' : 'day';		   
		$vals[$k] = $v;
		$time = ($time%86400);
    }
    if($time >= 3600){
    	$v = floor($time/3600);
		$k = $v>1 ? 'hours' : 'hour';    	
		$vals[$k] = $v;
		$time = ($time%3600);
    }
    if($time >= 60){
    	$v = floor($time/60);
		$k = $v>1 ? 'minutes' : 'minute';    	
		$vals[$k] = $v;
		$time = ($time%60);
    }

	$at = array();
	foreach($vals as $k=>$v)
		if($v!=0)
			$at[]= $v.$sep.$k;
			
	return implode(' ',$at);
}

?>