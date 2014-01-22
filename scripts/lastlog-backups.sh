#!/bin/bash

#crea un log dell'ultimo backups inviato pulse

LOG=$(echo $0 | sed 's/\.sh/\.log/g')

ls -lrth /mnt/backups | tail -n 20 > $LOG
tail -n 10 /mnt/backups/sync2pulse.log >> $LOG

chown z4k:www-data $LOG
chmod 0640 $LOG
