<?php
#TODO aggiungi pulsante per pulire log

	require('easyblog/dateutils.php');
	require('easyblog/iputils.php');

#TODO sftp logins
#grep sftp /var/log/auth.log -A1

	$logdir = './scripts/';
	$bantime = intval(shell_exec("grep bantime /etc/fail2ban/jail.conf | grep -v \# | head -n1 | cut -d'=' -f2"));
	$sshz4kip = 'SSH z4k ip: <em class="ip">'.zakbookip().'</em>';

	$geoipnum = shell_exec('ls /var/cache/geoipcache/ | wc -l');
	$netstat = shell_exec('netstat -ntpa4 | cut -c 20- | tail -n+3 | sort');
	$pstree = shell_exec('ps -eo "%u%a" kuid,args | uniq | grep -v "\[\|grep\|uniq\|ps\ -eo\|RUSER" | sed "s/ .*\/[s]*bin\//\t/"');
	$lastlogins = shell_exec('last -8 -aiF | head -n-2');

	$unbanfile = $logdir.'unbanip-list.list';
	$banfile = $logdir.'banip-list.list';
	$greplogfile = '/var/log/apache2/'.'*_access.log';

	if( !isset($_SERVER["HTTPS"]) )
	{
		header('Location: https://'.$_SERVER['SERVER_NAME']);
		exit(0);
	}
	
	if( !isset($_SERVER['PHP_AUTH_USER']) or !isset($_SERVER['PHP_AUTH_PW']) )
	{
	    header('HTTP/1.1 401 Authorization Required');
	    readfile('401.html');
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

#	if(isset($_GET['greplog']) and !empty($_GET['greplog'])):
#echo 'prova';
#		echo shell_exec('grep "'.$_GET['greplog'].'" '.$greplogfile);

#		exit(0);
#	endif;
		
?><html>
<head>
	<title>Admin <?php echo $_SERVER['SERVER_NAME']; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 	
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=0.5, user-scalable=no">	
	<link rel="stylesheet" href="style.css" />
</head>
<body>
<?php

?>
<div id="top_wrap">
	<div id="top" style="display:none">
		<h3>Admin <?php echo $_SERVER['SERVER_NAME']; ?> &bull; <em class="ip"><small><?php echo $_SERVER['SERVER_ADDR']; ?></small></em></h3>
		
		<div id="date"><?php echo date("d/m/Y h:i:s"); ?></div>
		
		<div style="text-align:right">
			<span><?php echo $sshz4kip; ?></span> &bull; 
			<span><?php
				echo isset($_SERVER['REMOTE_USER']) ?
					'Http Auth: '.$_SERVER['REMOTE_USER'].', <em class="ip">'.$_SERVER['REMOTE_ADDR'].'</em>' : 
					'<b style="color:red">No Http Auth!!</b>';
			?></span>	
		</div>
	
		<div id="menu" class="box">
			<span><img src="imgs/apache.ico" /> Apache 
				<a href="sss.php"> Status</a>, 
				<a href="iii.php"> Info</a>
			</span> | 
			<a href="ppp.php"><img src="imgs/php.ico" /> PHP Info</a> | 
			<!--a href="nnn/"><img src="http://labs.easyblog.it/dns-320-command-line/dns-320-pulse-icon.png" /> NAS</a> | -->
			<a href="mmm/"><img src="mmm/favicon.ico" /> PhpMyAdmin</a> | 
			<a href="ggg/"><img src="ggg/images/themes/default/Favicon.ico" /> PhpPgAdmin</a> | 
			<a href="rrr/"><img src="imgs/mongo.ico" /> RockMongo</a>
		</div>
	
		<div id="ban" class="box form">
			<form action="" method="get">
				<input type="hidden" name="ban" value="" />
				<label><b>Ban </label><br />
				<input name="ip" type="text" size="16" value="" /><br />
				<input type="submit" value="Add" />
				<input type="submit" name="clear" value="Clear" />
			</form>
			<pre style="overflow:hidden"><?php readfile($logdir.'banip-list.list'); ?></pre>
		</div>
	
		<div id="unban" class="box form">
			<form action="" method="get">
				<input type="hidden" name="unban" value="" />
				<label><b>Unban </b></label><br />
				<input name="ip" type="text" size="16" value="<? echo $_SERVER['REMOTE_ADDR']; ?>" /><br />
				<input type="submit" value="Add" />
				<input type="submit" name="clear" value="Clear" />			
			</form>
			<pre style="overflow:hidden"><?php readfile($logdir.'unbanip-list.list'); ?></pre>
		</div>
	
		<div class="box form">	
			<form id="geoipform" action="geoip/" method="get">
				<label><b>Geoip </b><small>(<?php echo $geoipnum; ?> ips)</small></label><br />
				<input name="ip" type="text" size="16" value="<? echo $_SERVER['REMOTE_ADDR']?>" /><br />
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
	<a id="topup" class="down" href="#down"></a>
</div>

<div id="bottom">

<div class="box">
	<b>Fail2ban IPs Banned</b>
	<small>(bantime: <?php echo Sec2Time($bantime); ?>)</small>
	<pre id="ipsbanned"><?php

	//readfile($logdir.'lastlog-ipsban.log');

	$ff = file($logdir.'lastlog-ipsban.log');

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
	<pre><?php echo $lastlogins; ?></pre>
</div>

<!--div class="box">
	<b>Rsnapshot</b>
	<pre id="rsnapshot">
	<?php
	#readfile($logdir.'lastlog-rsnapshot.log');
	?>
	</pre>
</div-->

<div class="box">
	<b>SSH Users Failed</b>
	<div id="userfailed">
	<?php
	$uu = file($logdir.'lastlog-userssh.log');
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

<div class="box">
	<b>Backups</b>
	<pre id="backups">
	<?php
		readfile($logdir.'lastlog-backups.log');
	?>
	</pre>
</div>

</div>

<pre id="ipbox" class="box"></pre>

<script src="jquery-1.9.0.min.js"></script>
<script>
$(function() {
	var geoip$ = $('#ipbox').hide();

	function formatJSON(json) {
		var html='<a class="aclose" href="#">&times;</a>';
		for(k in json)
			html += '<em>'+k+': </em><b>'+json[k]+'</b><br>';
		return html;
	}
	
	function showIp(ip) {
		$.getJSON('geoip/?'+ip, function(json) {
			geoip$.html(formatJSON(json)).show();
		});
	}	
	
	$('#geoipform').on('submit', function(e) {
		showIp( $(this).find(':text').val() );
		return false;
	});
	
	geoip$.on('click', '.aclose', function(e) {
		e.preventDefault();
		$(this).parent().hide();
	});
	
	$('.ip').on('click',function(e) {
		showIp( $(this).text() );
		return false;
	});
	
	$(window)
	.on('resize',function(e) {
		$('#bottom').css({marginTop: $('#top_wrap').height()+16});
	})
	.on('hashchange load',function(e) {
	
		var hash = window.location.href.split("#")[1];
	
		if(hash=='down')
			$('#top').slideDown('fast', function() {
				$('#topup').attr({'href':'#up','class':'up'}).trigger('resize');
			});
		else if(hash=='up')
			$('#top').slideUp('fast', function() {
				$('#topup').attr({'href':'#down','class':'down'}).trigger('resize');
			});			
	});

});

</script>
</body>
</html>

