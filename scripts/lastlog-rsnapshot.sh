#!/bin/bash

LOG=$(echo $0 | sed 's/\.sh/\.log/g')

tail -n10 /var/log/rsnapshot.log > $LOG

chown z4k:www-data $LOG
chmod 0640 $LOG
