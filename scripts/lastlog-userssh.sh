#!/bin/bash

LOG=$(echo $0 | sed 's/\.sh/\.log/g')

zgrep 'Invalid user' /var/log/auth.log* | sed 's/.*user \(.*\) from.*/\1/' | sort | uniq -c | sort -nr > $LOG

chown z4k:www-data $LOG
chmod 0640 $LOG
