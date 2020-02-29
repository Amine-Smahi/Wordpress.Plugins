# BruteForceProtector
A Brute Force Attack aims at being the simplest kind of method to gain access to a site: it tries usernames and passwords, over and over again, until it gets in.<br>
BruteForceProtector is a lightweight plugin that protects your website against brute force login attacks using .htaccess.<br>
After a specified limit of login attempts within a specified time, the IP address of the hacker will be blocked.

## Features

* Limit the number of allowed login attempts using normal login form
* Limit the number of allowed login attempts using Auth Cookies
* Manually block/unblock IP addresses
* Manually whitelist trusted IP addresses
* Delay execution after a failed login attempt (to slow down brute force attack)
* Option to inform user about remaining attempts on login page
* Option to email administrator when an IP has been blocked
* Custom message to show to blocked users

## Download
Download the v1.1 from [Here](https://github.com/Amine-Smahi/BruteForceProtector/archive/master.zip)

## Installation 
1. Install the plugin either via the WordPress.org plugin directory, or by uploading the files to your wp-content/plugin directory.
2. Activate the plugin through the WordPress admin panel.
3. Customize the settings on the settings page.
4. Done!
 
## What is .htaccess?
.htaccess is a configuration file for use on web servers running the Apache Web Server software. When a .htaccess file is placed in a directory which is in turn 'loaded via the Apache Web Server', then the .htaccess file is detected and executed by the Apache Web Server software. These .htaccess files can be used to alter the configuration of the Apache Web Server software to enable/disable additional functionality and features that the Apache Web Server software has to offer. These facilities include basic redirect functionality, for instance if a 404 file not found error occurs, or for more advanced functions such as content password protection or image hot link prevention.

## Some Screenshots
![Brute Force Protector htaccess wordpress plugin](https://user-images.githubusercontent.com/24621701/29215388-b2315532-7e5f-11e7-8fd5-837d3dde21cb.png)

## Contact Me

FB : https://web.facebook.com/amine.developer

Email : mohammed.amine.smahi@gmail.com
