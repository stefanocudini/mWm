#!/bin/sh

LOG=$(echo $0 | sed 's/\.sh/\.log/g')

/sbin/iptables -nL  &>$LOG

chown www-data:www-data $LOG
chmod 0640 $LOG
