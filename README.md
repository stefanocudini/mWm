Mobile Web Monitor
============
*nix web interface monitoring for mobile

Copyright(c) 2014 [Stefano Cudini](https://opengeo.tech/stefano-cudini/)

MIT License

**Tested on:**
* Server: Debian Squeeze
* Client: iPhone 4s iOS 7


**Source code:**  
[Github](https://github.com/stefanocudini/mwm)  

# Screenshots

![Image](https://raw.githubusercontent.com/stefanocudini/mWm/master/screenshots/mwm1.png)
![Image](https://raw.githubusercontent.com/stefanocudini/mWm/master/screenshots/mwm2.png)
![Image](https://raw.githubusercontent.com/stefanocudini/mWm/master/screenshots/mwm3.png)

# Features

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

# Requirements

* apache2, mod_auth_basic, mod_ssl, mod_status, mod_info
* php > 5.3
* fail2ban
<<<<<<< HEAD
* API KEY from [ipinfodb.com](https://ipinfodb.com/)
=======

# Optionals

* phpmyadmin
* API KEY from [ipinfodb.com](http://ipinfodb.com/)
>>>>>>> 4f2e3d72bba606ef446d21adc755f140277be340

# Permissions

* keep writable from www-data file: ./scripts/unbanip-list.list
* keep writable from www-data dir:  ./geoip/cache/
* keep writable only root files:    ./scripts/*.sh


# Setup

1. create new file ./etc/conf.json from ./etc/conf.default.json
2. create apache ssl virtual host using ./etc/apache.conf
3. copy ./etc/crond.conf in /etc/cron.d/mwm
4. browser https://adminsite.com/admin.php
