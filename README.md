Mobile Web Monitor
============
*nix web interface monitoring for mobile


#Requirements

* apache2, mod_auth_basic, mod_ssl, mod_status, mod_info
* php > 5.3
* phpmyadmin
* fail2ban


#Permissions

* keep writable from www-data file: scripts/unbanip-list.list
* keep writable from www-data dir: geoip/cache/
* keep writable only root files scripts/*.sh


#Setup

1. create new file etc/conf.json from etc/conf.default.json
2. create apache ssl virtual host using etc/apache.conf
3. move etc/crond.conf in /etc/cron.d/mwm
