# BruteForceProtector
A Brute Force Attack aims at being the simplest kind of method to gain access to a site: it tries usernames and passwords, over and over again, until it gets in.<br>
Brute Force Login Protection is a lightweight plugin that protects your website against brute force login attacks using .htaccess.<br>
After a specified limit of login attempts within a specified time, the IP address of the hacker will be blocked.

### Features

* Limit the number of allowed login attempts using normal login form
* Limit the number of allowed login attempts using Auth Cookies
* Manually block/unblock IP addresses
* Manually whitelist trusted IP addresses
* Delay execution after a failed login attempt (to slow down brute force attack)
* Option to inform user about remaining attempts on login page
* Option to email administrator when an IP has been blocked
* Custom message to show to blocked users

### Installation 
1. Install the plugin either via the WordPress.org plugin directory, or by uploading the files to your wp-content/plugin directory.
2. Activate the plugin through the WordPress admin panel.
3. Customize the settings on the settings page.
4. Done!
