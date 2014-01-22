Mobile Web Monitor
============
*nix web interface monitoring for mobile

**Tested on:**
* iphone 4s iOS 7


**Source code:**  
[Github](https://github.com/stefanocudini/mwm)  
[Bitbucket](https://bitbucket.org/zakis_/mwm)

#Screenshots

![Image](https://raw2.github.com/stefanocudini/mWm/master/screeshots/mwm1.png)

#Requirements

* apache2, mod_auth_basic, mod_ssl, mod_status, mod_info
* php > 5.3
* phpmyadmin
* fail2ban

#Permissions

* keep writable from www-data file: ./scripts/unbanip-list.list
* keep writable from www-data dir:  ./geoip/cache/
* keep writable only root files:    ./scripts/*.sh


#Setup

1. create new file ./etc/conf.json from ./etc/conf.default.json
2. create apache ssl virtual host using ./etc/apache.conf
3. copy ./etc/crond.conf in /etc/cron.d/mwm
