Mobile Web Monitor
============
*nix web interface monitoring for mobile

Copyright(c) 2014 [Stefano Cudini](http://labs.easyblog.it/stefano-cudini/)

MIT License

**Tested on:**
* Server: Debian Squeeze
* Client: iPhone 4s iOS 7


**Source code:**  
[Github](https://github.com/stefanocudini/mwm)  
[Bitbucket](https://bitbucket.org/zakis_/mwm)

#Screenshots

![Image](https://raw2.github.com/stefanocudini/mWm/master/screenshots/mwm1.png)

#Features

* Mobile browsing
* Secure access
* Un/Ban ips address
* IP location finder
* Fail2ban logs
* Ports monitoring
* Processes monitoring
* PHP errors monitoring
* SSH users attempts cloud
* SSH recent logins

#Requirements

* apache2, mod_auth_basic, mod_ssl, mod_status, mod_info
* php > 5.3
* phpmyadmin
* fail2ban
* API KEY from [ipinfodb.com](http://ipinfodb.com/)

#Permissions

* keep writable from www-data file: ./scripts/unbanip-list.list
* keep writable from www-data dir:  ./geoip/cache/
* keep writable only root files:    ./scripts/*.sh


#Setup

1. create new file ./etc/conf.json from ./etc/conf.default.json
2. create apache ssl virtual host using ./etc/apache.conf
3. copy ./etc/crond.conf in /etc/cron.d/mwm
4. browser https://adminsite.com/admin.php
