#!/bin/bash

UBFILE=$(echo $0 | sed 's/\.sh/\.list/g')
MAILFROM="fail2ban@adminsite.com"
MAILTO="admin@adminsite.com"

#TODO read params from etc/conf.json

if [ ! -f $UBFILE ]; then
	echo "Error file $UBFILE Not found!"
	exit 0
fi

#se non e' vuoto
if [ ! -s $UBFILE ]; then
	exit 0
fi

BANIP() {
	ip=$1
	for chain in $(/sbin/iptables -L | grep "Chain fail2ban" | cut -d' ' -f2)
	do
		/sbin/iptables -I $chain 1 -s $ip -j DROP
	done
	return 0
}
IPS=$(cat $UBFILE)

for ip in $IPS; do
	BANIP $ip > /dev/null 2>&1
done

printf %b "Subject: [Fail2Ban] Ban ip by mWm
From: Fail2Ban <$MAILFROM>
To: $MAILTO\n
Banned ips:
$IPS\n
by $0\n
$(/sbin/iptables -L -n)\n
" | /usr/sbin/sendmail -f $MAILFROM $MAILTO

> $UBFILE
