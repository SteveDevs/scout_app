LICENCE: MIT

Uses:
PHP 7.4
Mysql
Phalcon4
Jquery
Bootstrap
CSS
Javascript

Requirements:
PHP 7.4
Mysql
Phalcon 4

How to run:
Windows:

This was tested on windows and used xammp to run.

Download phalcon DLL:
https://github.com/phalcon/cphalcon/releases/tag/v4.1.0
Extract and copy php_phalcon.dll into php extentions folder. On xammp: php/ext/

Add extension=php_phalcon.dll to the bottom of php.ini file
Restart server.

Create a mysql database called scout.

Create the table users_dev using:
CREATE TABLE `users_dev` ( `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT, `username` varchar(30) DEFAULT NULL, `email` varchar(50) DEFAULT NULL, `password` char(60) DEFAULT NULL, `mobile` varchar(255) DEFAULT NULL, `name` varchar(30) DEFAULT NULL, `surname` varchar(30) DEFAULT NULL, `job_title` varchar(255) DEFAULT NULL, `bio` text, PRIMARY KEY (`id`), UNIQUE KEY `idx_email` (`email`), UNIQUE KEY `idx_mobile` (`mobile`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;

git clone https://github.com/SteveDevs/scout_app.git

Rename extraction folder to scout_app
Copy folder into your server. (xammp: htdocs)

Open browser: http://localhost/scout_app
