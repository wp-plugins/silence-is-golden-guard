=== Silence is Golden Guard ===
Contributors: shinephp
Donate link: http://www.shinephp.com/donate/
Tags: index, htaccess, silence, security, guard, DirectoryIndex
Requires at least: 3.0
Tested up to: 4.2
Stable tag: trunk

It prevents your blog directories from listing requests with redirect to home page, removes unused txt, screenshot files from plugins directories.

== Description ==

Silence is Golden Guard plugin prevents WordPress blog directories from listing if visitor types just directory name as the URL, e.g. 
http://yourdomain/wp-content/plugins/
Plugin can automatically check WordPress site state and fix it if needed once a day.
The checking are:
* .htaccess file. If it has not Options -Index line, plugins makes
backup copy and adds Options -Indexes line into .htaccess file in the WordPress
root directory.
* Plugin can check if empty index.php file exists in the every directory. If index.php file doesn't exist,
plugin creates one with comments line 'Silence is golden' as WordPress does.
This check and fix are useful as many plugin authors do not put index.php files into their plugin directories.
Redirect to the site root option from index.php call is available.
* Other functions:
* remove unused files (readme.txt, screenshot-*.*)  from plugins folders, which could expose plugin versions to attacker.
* remove WordPress version information from blog pages.
You can find more infomation here at <a href="http://shinephp.com/silence-is-golden-guard-wordpress-plugin/" rel="nofollow">shinephp.com</a>

<strong>There is a very strong recommendation to make full backup of your blog before you activate SIG plugin.</strong> If you have development copy of your blog at the same webhost I recommend you to give a SIG plugin first try at the test environment as there are small amount of incidents when with redirection to site root option turned on automatically created empty index.php file caused endless redirect loop and prevents site loading.
It could be the case if you use child theme and theme_name-child folder has not its own index.php file and some other cases which are not isolated yet. 


== Installation ==

Installation procedure:
1. Deactivate plugin if you have the previous version installed. (It is important requirement for switching to this version from a previous one.)
2. Extract "silence-is-golden-guard.x.x.x.zip" archive content to the "/wp-content/plugins/silence-is-golden-guard" directory.
3. Activate "Silence is Golden Guard" plugin via 'Plugins' menu in WordPress admin menu. 
4. Go to the "Settings"-"SIG Guard" menu item and check/change your preferences to customize how this plugin will work for you.

== Frequently Asked Questions ==
* I activated Silence is Golden plugin and click Scan button. Now I have not access to my site, neither front-end, nor admin back-end. FireFox writes "Firefox has detected that the server is redirecting the request for this address in a way that will never complete." What is happend and how to fix it?
* This problem could be met if you use WP Super Cache plugin and turned on the redirection to site root option for SIG plugin. If you put index.php file with redirection to root directive into wp-super-cache/plugins/ folder you will get exactly that problem as it is described above.
To resolve this SIG plugins checks from v. 1.5 if WP Super Cache plugin is active, and in this case creates the empty index.php file in the wp-super-cache/plugins/ folder, that is SIG ignores redirection option for this folder.
Problem could be left if WP Super Cache is placed under another path, and WP Super Cache plugin root folder name differs from the default one "wp-super-cache".
To resolve endless redirection loop problem remove the 'header("Location: http://www...' line from wp-super-cache/plugins/index.php file. It will resolve your problem with the high level of probability.
If you can not resolve the problem yourself left a comment at <a href="http://www.shinephp.com/silence-is-golden-guard-wordpress-plugin/">SIG plugin page</a> and I will try to help you.


== Screenshots ==
1. screenshot-1.png Silence is Golden Guard plugin Settings page
2. screenshot-2.png SIG Guard plugin Scan results


== Special Thanks to ==
You are welcome! Help me with plugin translation, share with me new ideas about it further development and link to your site will appear here.

== Translations ==
* Arabic: [mr.Ahmad](http://egylovers.com)
* French: [Whiler](http://blogs.wittwer.fr/whiler/)
* German: [Tom](http://cash-india.info)
* Italian: [Alessandro Mariani](http://technodin.org)
* Lithuanian: [Vincent G](http://host1free.com)
* Russian: [Vladimir](http://shinephp.com)
* Spanish: [Omi](http://equipajedemano.info)

Dear plugin User,
if you wish to help me with this plugin translation I very appreciate it. Please send your language .po and .mo files to vladimir[at-sign]shinephp.com email. Do not forget include your site link in order I can show it with greetings for the translation help at shinephp.com, plugin settings page and in this readme.txt file.


== Changelog ==

= 1.10 =
* 03.05.2015
* Fix fatal error on plugin activation (used deprecated WordPress PHP constant WPLANG - does not exist on fresh WordPress installations).

= 1.9 =
* 04.10.2013
* Daily autoscan feature uses WordPress schedule API instead of internal one.
* Code was updated to provide compatibility with PHP version 5.3+.

= 1.8.1 =
* 15.04.2012
* Lithuanian translation is added, thanks to Vincent G.

= 1.8 =
* 12.11.2011
* Arabic translation is added.
* ShinePHP.com News section is removed from plugin's Settings page.

= 1.7 =
* 29.30.2010
* Italian translation is added.
* Technical update for WordPress 3.0 full compatibility. Staff deprecated since WordPress v.3.0 is excluded.

= 1.6 =
* 19.05.2010
* German translation is added.
* Minor bugs with usage of translation text-domain are fixed.

= 1.5 =
* 09.05.2010
* Endless redirection loop problem for blogs with active WP Super Cache plugin is resolved. See <a href="http://www.shinephp.com/silence-is-golden-guard-wordpress-plugin/#faq">FAQ</a> section for more details.
* Event log file is created only if correspondent option is turned on at the Settings page.

= 1.4 =
* 05.05.2010
* Checking if index.php file is SIG Guard plugin made file and can be rewritten is updated in try to exclude rare cases when SIG Guard rewrites important index.php file, e.g. in the current theme folder. SIG Guard own stamp string verification is added. File with more than 4 rows will not be changed too.
This update is critical for new plugin users only.
Note to former users: if you wish to rebuild all your dummy SIG index.php files you need to delete that files manually before click "Rebuild All" button. As old index.php file does not contain SIG Guard stamp string, plugin should not update such file.

= 1.3 =
* 12.04.2010
* Redirect to the site root for directory listing requests option is added.
* All index.php rebuild function is added (in case you change index.php type you use from empty page to redirection or back);
* Unused plugins files readme.txt, screenshot- remove options are added. Such file expose plugin verision to attacker easy.
* Remove WordPress version from your blog pages option is added.

= 1.2 =
* 25.03.2010
* Spanish translation is added.

= 1.1 = 
* 19.03.2010
* Minor bug with usage of the textdomain for the translation is fixed. Thanks to Whiler who found it.
* French translation is added. Thanks to Whiler again.

= 1.0 = 
* 16.03.2010
* 1st release.

== Additional Documentation ==

You can find more information about "Silence is Golden Guard" plugin at this page
http://www.shinephp.com/silence-is-golden-guard-wordpress-plugin/

I am ready to answer on your questions about plugin usage. Use ShinePHP forum at
http://shinephp.com/community/forum/silence-is-golden-guard/
or plugin page comments and site contact form for it please.
