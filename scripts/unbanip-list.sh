#!/bin/bash

UBFILE=$(echo $0 | sed 's/\.sh/\.list/g')
MAILFROM="fail2ban@adminsite.com"
MAILTO="admin@adminsite.com"
#TODO read params from etc/conf.json

if [ ! -f $UBFILE ]; then
	echo "Error file $UBFILE Not found!"
	exit 0
fi

if [ ! -s $UBFILE ]; then
	exit 0
fi

UNBANIP() {
	ip=$1
	for chain in $(/sbin/iptables -L | grep "Chain fail2ban" | cut -d' ' -f2)
	do
		/sbin/iptables -D $chain -s $ip -j DROP
	done
	return 0
}
IPS=$(cat $UBFILE)

for ip in $IPS; do
	UNBANIP $ip > /dev/null 2>&1
done

printf %b "Subject: [Fail2Ban] Unban ip mWm
From: Fail2Ban <$MAILFROM>
To: $MAILTO\n
Unbanned ips:
$IPS\n
by $0\n
$(/sbin/iptables -L -n)\n
" | /usr/sbin/sendmail -f $MAILFROM $MAILTO

> $UBFILE
