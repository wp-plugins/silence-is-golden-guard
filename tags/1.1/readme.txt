=== Silence is Golden Guard ===
Contributors: ShinePHP
Donate link: http://www.shinephp.com/donate/
Tags: index, htaccess, silence, security, guard, DirectoryIndex
Requires at least: 2.8.
Tested up to: 2.9.2
Stable tag: 1.1

Silence is Golden Guard WordPress plugin prevents your blog directories from listing if visitor types just directory name as the URL, e.g. http://yourdomain/wp-content/plugins/

== Description ==

Silence is Golden Guard plugin prevents WordPress blog directories from listing if visitor types just directory name as the URL, e.g. 
http://yourdomain/wp-content/plugins/
Plugin can automatically check WordPress site state and fix it if needed once a day.
The checking are:
1) .htaccess file. If it has not Options -Index line, plugins makes
backup copy and adds Options -Indexes line into .htaccess file in the WordPress
root directory.
2) In case .htaccess doesn't work on your site, plugin checks if empty
index.php file exists in the every directory. If index.php file doesn't exist,
plugin creates one with comments line only 'Silence is golden' exactly as WordPress does.
This check and fix are useful as many plugin author do not put index.php files into their plugin directories.
To read more about 'Silence is Golden Guard' visit this link http://www.shinephp.com/silence-is-golden-guard-wordpress-plugin

== Installation ==

Installation procedure:

1. Deactivate plugin if you have the previous version installed. (It is important requirement for switching to this version from a previous one.)
2. Extract "silence-is-golden-guard.x.x.x.zip" archive content to the "/wp-content/plugins/silence-is-golden-guard" directory.
3. Activate "Silence is Golden Guard" plugin via 'Plugins' menu in WordPress admin menu. 
4. Go to the "Settings"-"SIG Guard" menu item and check/change your preferences to customize how this plugin will work for you.

== Frequently Asked Questions ==
- coming soon with your help


== Screenshots ==
1. screenshot-1.png Silence is Golden Guard plugin Settings page

== Translations ==
* Russian: [ShinePHP](http://shinephp.com)
* French: [Whiler](http://blogs.wittwer.fr/whiler/)

Dear plugin User,
if you wish to help me with this plugin translation I very appreciate it. Please send your language .po and .mo files to vladimir@shinephp.com email. Do not forget include you site link in order I can show it with greetings for the translation help at shinephp.com, plugin settings page and in this readme.txt file.

== Special Thanks to ==
You are welcome! Help me with plugin translation, share with me new ideas about it further development and link to your site will appear here.

== Changelog ==
= 1.1 = 19.03.2010
- Minor bug with usage of the textdomain for the translation is fixed. Thanks to Whiler who found it.
- French translation is added. Thanks to Whiler again.

= 1.0 = 16.03.2010
- 1st release.

== Additional Documentation ==

You can find more information about "Silence is Golden Guard" plugin at this page
http://www.shinephp.com/silence-is-golden-guard-wordpress-plugin/

I am ready to answer on your questions about plugin usage. Use ShinePHP forum at
http://shinephp.com/community/forum/silence-is-golden-guard/
or plugin page comments and site contact form for it please.
