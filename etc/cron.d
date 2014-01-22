*/3 *	* * *	root	/var/www/admin/scripts/unbanip-list.sh
*/4	*	* * *	root	/var/www/admin/scripts/banip-list.sh
*/6 *	* * *	root	/var/www/admin/scripts/lastlog-ipsban.sh
10  7	* * *	z4k		/var/www/admin/scripts/lastlog-userssh.sh
11 */2	* * *	z4k		/var/www/admin/scripts/lastlog-php.sh
12 */8	* * *	root	cat /var/www/admin/scripts/lastlog-php.log | ifne mail -s '[PHP Error]' $MAILTO
15  9	* * 6	root	/var/www/admin/scripts/lastlog-backups.sh
