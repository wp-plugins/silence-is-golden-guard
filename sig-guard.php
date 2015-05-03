<?php
/*
Plugin Name: Silence Is Golden Guard
Plugin URI: http://www.shinephp.com/silence-is-golden-guard-wordpress-plugin/
Description: It prevents your blog directories from full file listing if visitor types just directory name as the URL, e.g. http://yourdomain/wp-content/plugins/
Version: 1.10
Author: Vladimir Garagulya
Author URI: http://www.shinephp.com
Text Domain: sig-guard
Domain Path: /lang/
*/

/*
Copyright 2010  Vladimir Garagulya  (email: vladimir@shinephp.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if (!function_exists("get_option")) {
  die;  // Silence is golden, direct call is prohibited
}

global $wp_version;

$exit_msg = __('Silence is Golden Guard requires WordPress 3.0 or newer.').'<a href="http://codex.wordpress.org/Upgrading_WordPress">'.__('Please update!').'</a>';

if (version_compare($wp_version,"3.0","<"))
{
	return ($exit_msg);
}


require_once('sig-guard_lib.php');

load_plugin_textdomain('sig-guard','', $sig_guardPluginDirName.'/lang');


function sig_guard_optionsPage() {

  if (!current_user_can('activate_plugins')) {
    die('action is forbidden');
  }

  global $sig_siteURL;

  $sig_guard_use_htaccess = get_option('sig_guard_use_htaccess');
  $sig_guard_auto_monitor = get_option('sig_guard_auto_monitor');
  $sig_guard_monitor_period = get_option('sig_guard_monitor_period');
  $sig_guard_exclude_folders = get_option('sig_guard_exclude_folders');
  $sig_guard_exclude_folders_list = get_option('sig_guard_exclude_folders_list');
  $sig_guard_delete_readme = get_option('sig_guard_delete_readme');
  $sig_guard_delete_screenshot = get_option('sig_guard_delete_screenshot');
  $sig_guard_redirect_tohomepage = get_option('sig_guard_redirect_tohomepage');
  $sig_guard_hide_wordpress_version = get_option('sig_guard_hide_wordpress_version');
  $sig_guard_log_errors = get_option('sig_guard_log_errors');
  if (!empty($sig_guard_auto_monitor)) {
      if ( !wp_next_scheduled('sig_guard_daily_event') ) {
		  wp_schedule_event( time(), 'daily', 'sig_guard_daily_event');		
	  }
  } else {
	  $timestamp = wp_next_scheduled('sig_guard_daily_event');
	  if (!empty($timestamp)) {
		  wp_clear_scheduled_hook('sig_guard_daily_event');
		  wp_unschedule_event($timestamp, 'sig_guard_daily_event');
	  }
  }
?>

<div class="wrap">
  <div class="icon32" id="icon-options-general"><br/></div>
    <h2><?php _e('Silence Is Golden Guard Plugin', 'sig-guard'); ?></h2>
		<?php require ('sig-quard_options.php'); ?>
  </div>
<?php

}
// end of sig_guard_optionsPage()


// Install plugin
function sig_guard_install() {
	
  add_option('sig_guard_use_htaccess', 0);
  add_option('sig_guard_auto_monitor', 0);
  add_option('sig_guard_monitor_period', 2);
  add_option('sig_guard_exclude_folders', 0);
  add_option('sig_guard_exclude_folders_list', array());
  add_option('sig_guard_last_check', time());
  add_option('sig_guard_delete_readme', 0);
  add_option('sig_guard_delete_screenshot', 0);
  add_option('sig_guard_redirect_tohomepage', 0);
  add_option('sig_guard_hide_wordpress_version', 0);
  add_option('sig_guard_log_errors', 0);
  $logFileName = SIG_GUARD_PLUGIN_DIR.SIG_GUARD_DIR_SLASH.'sig-guard.log';
  if (SIG_WINDOWS_SERVER) {
    $logFileName = str_replace('/', SIG_GUARD_DIR_SLASH, $logFileName);
  }
  if (file_exists($logFileName)) {
    sig_fileRemove($logFileName);
  }
  
}
// end of sig_guard_install()


function sig_guard_init() {

  if(function_exists('register_setting')) {
    register_setting('sig-quard-options', 'sig_guard_use_htaccess');
    register_setting('sig-quard-options', 'sig_guard_auto_monitor');
    register_setting('sig-quard-options', 'sig_guard_monitor_period');
    register_setting('sig-quard-options', 'sig_guard_exclude_folders');
    register_setting('sig-quard-options', 'sig_guard_exclude_folders_list');
    register_setting('sig-quard-options', 'sig_guard_delete_readme');
    register_setting('sig-quard-options', 'sig_guard_delete_screenshot');
    register_setting('sig-quard-options', 'sig_guard_redirect_tohomepage');
    register_setting('sig-quard-options', 'sig_guard_hide_wordpress_version');
    register_setting('sig-quard-options', 'sig_guard_log_errors');
  }
}
// end of sig_guard_init()


function sig_guard_plugin_action_links($links, $file) {
    if ($file == plugin_basename(dirname(__FILE__).'/sig-guard.php')){
        $settings_link = "<a href='options-general.php?page=sig-guard.php'>".__('Settings','sig-quard')."</a>";
        array_unshift( $links, $settings_link );
    }
    return $links;
}
// end of sig_guard_plugin_action_links


function sig_guard_plugin_row_meta($links, $file) {
  if ($file == plugin_basename(dirname(__FILE__).'/sig-guard.php')){
		$links[] = '<a target="_blank" href="http://www.shinephp.com/silence-is-golden-guard-wordpress-plugin/#changelog">'.__('Changelog', 'sig-guard').'</a>';
	}
	return $links;
} // end of sig_guard_plugin_row_meta


function sig_guard_settings_menu() {
	if ( function_exists('add_options_page') ) {
    $sig_guard_page = add_options_page('Silence Is Golden Guard', 'SIG Guard', 'create_users', basename(__FILE__), 'sig_guard_optionsPage');
		add_action( "admin_print_styles-$sig_guard_page", 'sig_guard_adminCssAction' );
	}
}
// end of sig_guard_settings_menu()

function sig_guard_adminCssAction() {

  wp_enqueue_style('sig_guard_admin_css', SIG_GUARD_PLUGIN_URL.'/css/sig-guard_admin.css', array(), false, 'screen');

}
// end of sig_guard_adminCssAction()

/**
 * On deactivation, the remove scheduled action hook.
 */
function sig_guard_deactivation() {
	$timestamp = wp_next_scheduled('sig_guard_daily_event');
	if (!empty($timestamp)) {
		wp_clear_scheduled_hook('sig_guard_daily_event');
		wp_unschedule_event($timestamp, 'sig_guard_daily_event');
	}
}


if (is_admin()) {
  // activation action
  register_activation_hook(__FILE__, 'sig_guard_install');
  register_deactivation_hook(__FILE__, 'sig_guard_deactivation');
  
  add_action('admin_init', 'sig_guard_init');
  // add a Settings link in the installed plugins page
  add_filter('plugin_action_links', 'sig_guard_plugin_action_links', 10, 2);
  add_filter('plugin_row_meta', 'sig_guard_plugin_row_meta', 10, 2);
}

add_action('admin_menu', 'sig_guard_settings_menu');

$sig_guard_auto_monitor = get_option('sig_guard_auto_monitor');
if ($sig_guard_auto_monitor) {
	add_action('sig_guard_daily_event', 'sig_guard_Scan');
}


$sig_guard_hide_wordpress_version = get_option('sig_guard_hide_wordpress_version');
if ($sig_guard_hide_wordpress_version) {
  // exclude WP version from the HTML header
  add_filter( 'the_generator', create_function('$sig_guard_hide_wordpress_version', 'return null;'));
}
