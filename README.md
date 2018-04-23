# Installation
### Installation on Linux with Apache2 (rewrite and Php Curl modules are required). 

---

*This Portal works with iTop 2.1*

Installation step by step :

* Download Zip on https://github.com/Sparfel/iTop-s-Portal and move it into */tmp*,
* Create a folder on your webserver, */var/www/itopPortal/* for example,
* Copy all files into this new folder */var/www/itopPortal/* with the command *cp -R /tmp/iTop-s-Portal-master/. . *
* Change the owner of the files : **chown -R www-data:www-data itopPortal**
* Create the Database :
```
mysql -u root -p
mysql>CREATE DATABASE itop_portal;
mysql>exit;
```
* Create a virtual host for this web site and configure Apache2. In */etc/apache2/sites-available/* create a new file, **itopportal.conf** for example and add in this file :
```html
<VirtualHost *:80>
  ServerName itop.portal.local
	DocumentRoot '/var/www/itopPortal/public/'
	DirectoryIndex index.php
	<Directory '/var/www/itopPortal'>
		Options Indexes FollowSymLinks -MultiViews
		AllowOverride All
		Order allow,deny
		Allow from all
	</Directory>
</VirtualHost>
```
enable the site now :
``` a2ensite itopportal.conf ```

we must enable the rewrite module on apache too :
``` a2enmod rewrite ``` 

we need the **curl_init** module too (to use the iTop's webservices) :
``` apt-get install php5-curl``` 

reload Apache :
``` service apache2 reload```

---

That's all, the configuration will be done through the website **[http://your_vhost_name](http://your_vhost_name)**.

It may be necessary to define the adress of the server with his name in your host file.

---

A demo is now available at this adress : http://itop-portal.ddns.net/
* Admin user : admin / admin1234
* Normal user : dali@demo.com / Salvador1234

(backoffice address : http://itop-portal.ddns.net/admin/)

This portal is working with an iTop demo : http://services.sydel.fr/itop-test-web/
* Admin user : admin / admin1234

See the documentation online at : http://doc-portal.ddns.net/