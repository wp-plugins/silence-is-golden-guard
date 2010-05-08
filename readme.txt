=== Silence is Golden Guard ===
Contributors: ShinePHP
Donate link: http://www.shinephp.com/donate/
Tags: index, htaccess, silence, security, guard, DirectoryIndex
Requires at least: 2.8
Tested up to: 3.0
Stable tag: 1.4

Silence is Golden Guard WordPress plugin prevents your blog directories from listing with redirect to home page, removes unused files from plugin folders, which could expose plugin versions to attacker.

== Description ==

Silence is Golden Guard plugin prevents WordPress blog directories from listing if visitor types just directory name as the URL, e.g. 
http://yourdomain/wp-content/plugins/
Plugin can automatically check WordPress site state and fix it if needed once a day.
The checking are:
* .htaccess file. If it has not Options -Index line, plugins makes
backup copy and adds Options -Indexes line into .htaccess file in the WordPress
root directory.
* Plugin can check if empty index.php file exists in the every directory. If index.php file doesn't exist,
plugin creates one with comments line only 'Silence is golden' exactly as WordPress does.
This check and fix are useful as many plugin author do not put index.php files into their plugin directories.
Redirect to the site root option is available.
* Other functions:
* remove unused files (readme.txt, screenshot-*.*)  from plugin folders, which could expose plugin versions to attacker.
* remove WordPress version information from blog pages.

To read more about 'Silence is Golden Guard' visit this link http://www.shinephp.com/silence-is-golden-guard-wordpress-plugin


<strong>Attention!</strong> In rare cases (4 users reported this problem) activation of this plugin can block the site with so-called redirection loop. Important index.php file can be rewritten by SIG plugin due to difficult catching bug exists. For this time I can't repeat and reproduce such situation neither at my test environment nor at the working site. Yes, http://shinephp.com uses SIG plugin.
<strong>There is a very strong recommendation to make full backup of your blog before you activate SIG plugin.</strong> If you have developement copy of your blog at the same webhost I recommend you to give a SIG plugin first try at the test environment.

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
2. screenshot-2.png SIG Guard plugin Scan results

== Translations ==
* French: [Whiler](http://blogs.wittwer.fr/whiler/)
* Russian: [Vladimir](http://shinephp.com)
* Spanish: [Omi](http://equipajedemano.info)

Dear plugin User,
if you wish to help me with this plugin translation I very appreciate it. Please send your language .po and .mo files to vladimir[at-sign]shinephp.com email. Do not forget include your site link in order I can show it with greetings for the translation help at shinephp.com, plugin settings page and in this readme.txt file.

== Special Thanks to ==
You are welcome! Help me with plugin translation, share with me new ideas about it further development and link to your site will appear here.

== Changelog ==

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
