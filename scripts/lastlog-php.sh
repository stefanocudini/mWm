#!/bin/bash

#crea un log con ip bannati e non ancora sbannati!

LOG=$(echo $0 | sed 's/\.sh/\.log/g')

grep -i "error\|warning" /var/log/php5/*.log | tail -n200 > $LOG

chown z4k:www-data $LOG
chmod 0640 $LOG
