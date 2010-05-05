<?php
/* 
 * Silence Is Golden Guard plugin Settings form
 * Author: Vladimir Garagulya vladimir@shinephp.com
 */

if (!defined('SIG_GUARD_PLUGIN_URL')) {
  die;  // Silence is golden, direct call is prohibited
}

function sig_guard_displayBoxStart($title) {
?>
			<div class="postbox" style="float: left;">
				<h3 style="cursor:default;"><span><?php echo $title ?></span></h3>
				<div class="inside">
<?php
}
// 	end of thanks_displayBoxStart()

function sig_guard_displayBoxEnd() {
?>
				</div>
			</div>
<?php
}
// end of thanks_displayBoxEnd()

$mess = '';
$shinephpFavIcon = SIG_GUARD_PLUGIN_URL.'/images/vladimir.png';
if (isset($_GET['action']) && ($_GET['action']=='scan' || $_GET['action']=='rebuild')) {
  if ($_GET['action']=='scan') {
    sig_guard_Scan(true);
  } else {
    sig_guard_Scan(true, true);
  }
  return;
}
?>
  <form method="post" action="options.php">
<?php
    settings_fields('sig-quard-options');
?>
				<div id="poststuff" class="metabox-holder has-right-sidebar">
					<div class="inner-sidebar" >
						<div id="side-sortables" class="meta-box-sortabless ui-sortable" style="position:relative;">
									<?php sig_guard_displayBoxStart(__('About this Plugin:', 'sig-guard')); ?>
											<a class="sig_guard_rsb_link" style="background-image:url(<?php echo $shinephpFavIcon; ?>);" target="_blank" href="http://www.shinephp.com/"><?php _e("Author's website", 'sig-guard'); ?></a>
											<a class="sig_guard_rsb_link" style="background-image:url(<?php echo SIG_GUARD_PLUGIN_URL.'/images/sig-guard-icon.png'; ?>" target="_blank" href="http://www.shinephp.com/silence-is-golden-guard-wordpress-plugin/"><?php _e('Plugin webpage', 'sig-guard'); ?></a>
											<a class="sig_guard_rsb_link" style="background-image:url(<?php echo SIG_GUARD_PLUGIN_URL.'/images/changelog-icon.png'; ?>);" target="_blank" href="http://www.shinephp.com/silence-is-golden-guard-wordpress-plugin/#changelog"><?php _e('Changelog', 'sig-guard'); ?></a>
											<a class="sig_guard_rsb_link" style="background-image:url(<?php echo SIG_GUARD_PLUGIN_URL.'/images/faq-icon.png'; ?>)" target="_blank" href="http://www.shinephp.com/silence-is-golden-guard-wordpress-plugin/#faq"><?php _e('FAQ', 'sig-guard'); ?></a>
                      <a class="sig_guard_rsb_link" style="background-image:url(<?php echo SIG_GUARD_PLUGIN_URL.'/images/donate-icon.png'; ?>)" target="_blank" href="http://www.shinephp.com/donate"><?php _e('Donate', 'sig-guard'); ?></a>
									<?php sig_guard_displayBoxEnd(); ?>
									<?php sig_guard_displayBoxStart(__('Greetings:','sig-guard')); ?>
											<a class="sig_guard_rsb_link" style="background-image:url(<?php echo $shinephpFavIcon; ?>);" target="_blank" title="<?php _e("It's me, the author", 'sig-guard'); ?>" href="http://www.shinephp.com/">Vladimir</a>
                      <a class="sig_guard_rsb_link" style="background-image:url(<?php echo SIG_GUARD_PLUGIN_URL.'/images/whiler.png'; ?>);" target="_blank" title="<?php _e('for the help with French translation', 'sig-guard'); ?>" href="http://blogs.wittwer.fr/whiler/">Whiler</a>
                      <a class="sig_guard_rsb_link" style="background-image:url(<?php echo SIG_GUARD_PLUGIN_URL.'/images/omi.png'; ?>);" target="_blank" title="<?php _e('for the help with Spanish translation', 'thankyou'); ?>" href="http://equipajedemano.info/">Omi</a>
											<?php _e('Do you wish to see your name with link to your site here? You are welcome! Your help with translation and new ideas are very appreciated.', 'sig-guard'); ?>
									<?php sig_guard_displayBoxEnd(); ?>
						</div>
					</div>
					<div class="has-sidebar" >
						<div id="post-body-content" class="has-sidebar-content">
<script language="javascript" type="text/javascript">
  function sig_guard_hideShowDiv(checkbox) {
    var el = document.getElementById('foldersdiv')
    if (checkbox.checked) {
      el.style.display = 'block';
    } else {
      el.style.display = 'none';
    }
  }

  function sig_guard_Settings(action) {
    if (action=='cancel') {
      document.location = '<?php echo SIG_GUARD_WP_ADMIN_URL; ?>/options-general.php?page=sig-guard.php';
    } else if (action=='scan' || action=='rebuild') {
      document.location = '<?php echo SIG_GUARD_WP_ADMIN_URL; ?>/options-general.php?page=sig-guard.php&action='+ action;
    }
  }

</script>
<?php
						sig_guard_displayBoxStart(__('Settings', 'sig-guard')); ?>
        <table class="form-table" style="clear:none;" cellpadding="0" cellspacing="0">          
          <tr>
            <td style="vertical-align:top;width:200px;">
              <input type="checkbox" value="1" <?php echo sig_guard_optionChecked($sig_guard_exclude_folders, 1); ?>
                       name="sig_guard_exclude_folders" id="sig_guard_exclude_folders" onclick="sig_guard_hideShowDiv(this)"
                       title="<?php _e('Does not create index.php file in the checked folders'); ?>"/>
	             <label for="sig_guard_exclude_folders"><?php _e('Exclude Folders','sig-guard'); ?></label>
            </td>
            <td>                      
<?php
  _e('Checked folders are fully excluded from SIG Guard plugin activity','sig-guard');
  $folders = sig_guard_getBlogFolders();

?>
                <div id="foldersdiv" style="display:<?php echo ($sig_guard_exclude_folders) ? 'block':'none';?>">
                  <div id="folders-all" class="tabs-panel">
                  <ul>
<?php
  
  foreach ($folders as $folder) {
    $checked = '';
    if (is_array($sig_guard_exclude_folders_list) and count($sig_guard_exclude_folders_list)) {
      foreach($sig_guard_exclude_folders_list as $folderToExclude) {
        if ($folderToExclude==$folder) {
          $checked = 'checked="checked"';
          break;
        }
      }
    }
?>
   <li><input type="checkbox" name="sig_guard_exclude_folders_list[]" value="<?php echo $folder;?>" <?php echo $checked; ?> />
<?php echo $folder; ?>
   </li>
<?php    
  }
?>
                  </ul>
                  </div>
                </div>                
            </td>
          </tr>
        <tr>
          <td style="vertical-align:top;">
            <input type="checkbox" value="1" <?php echo sig_guard_optionChecked($sig_guard_redirect_tohomepage, 1); ?>
                   name="sig_guard_redirect_tohomepage" id="sig_guard_redirect_tohomepage" />
            <label for="sig_guard_redirect_tohomepage"><?php _e('index.php Redirect','sig-guard'); ?></label><br/>
            <div class="submit" style="margin-left:10px;">
              <input type="button" name="rebuild" value="<?php _e('Rebuild All', 'sig-guard') ?>" title="<?php _e('Rebuild All subfolder index.php files. It is useful if you changed redirection option above.','sig-guard');?>" onclick="sig_guard_Settings('rebuild');"/>
            </div>
          </td>
          <td>
            <?php echo __('If it does not checked, subfolder index.php will show empty page only. If it is checked, any request for subfolder content listing will be redirected to your blog root','sig-guard').' '.$sig_siteURL; ?>
          </td>
        </tr>
        <tr>
          <td style="vertical-align: top;">
            <input type="checkbox" name="sig_guard_use_htaccess" id="sig_guard_use_htaccess" value="1" <?php echo sig_guard_optionChecked($sig_guard_use_htaccess, 1); ?> />
            <label for="sig_guard_use_htaccess"><?php _e('Modify Apache .htaccess', 'sig-guard'); ?></label>
          </td>
          <td>
            <?php _e('Modify Apache .htaccess file in the site root folder. Add "Options -Indexes" line to prevent directory listing by Apache Web server.
                      If it is turned on (+Indexes) or absent, then if a URL which maps to a directory is requested, and there is no DirectoryIndex (e.g., index.html or index.php) file in that directory, then Web server will return a formatted listing of the directory.', 'sig-guard')?>
          </td>
        </tr>
        <tr>
          <td style="vertical-align:top;">
            <input type="checkbox" value="1" <?php echo sig_guard_optionChecked($sig_guard_delete_readme, 1); ?>
                   name="sig_guard_delete_readme" id="sig_guard_delete_readme" />
            <label for="sig_guard_delete_readme"><?php _e('Delete readme.txt','sig-guard'); ?></label>
          </td>
          <td>
            <?php _e('If it is checked, plugin will delete unused readme.txt, documentation.txt, changelog.txt files from every plugin folder','sig-guard'); ?>
          </td>
        </tr>
        <tr>
          <td style="vertical-align:top;">
            <input type="checkbox" value="1" <?php echo sig_guard_optionChecked($sig_guard_delete_screenshot, 1); ?>
                   name="sig_guard_delete_screenshot" id="sig_guard_delete_screenshot"/>
            <label for="sig_guard_delete_screenshot"><?php _e('Delete screenshot files','sig-guard'); ?></label>
          </td>
          <td>
            <?php _e('If it is checked, plugin will delete unused screenshot-1.gif, screenshot-2.gif, etc. files (or .png, .jpg) from every plugin folder','sug-guard'); ?>
          </td>
        </tr>
        <tr>
          <td>
            <input type="checkbox" name="sig_guard_auto_monitor" id="sig_guard_auto_monitor" value="1" <?php echo sig_guard_optionChecked($sig_guard_auto_monitor, 1); ?>/>
            <label for="sig_guard_auto_monitor"><?php _e('Auto Monitor','sig-guard'); ?></label>
          </td>
          <td class="submit">
            <?php _e('Check folders state automatically once a day'); ?>
          </td>
        </tr>
        <tr>
          <td style="vertical-align:top;">
            <input type="checkbox" value="1" <?php echo sig_guard_optionChecked($sig_guard_hide_wordpress_version, 1); ?>
                   name="sig_guard_hide_wordpress_version" id="sig_guard_hide_wordpress_version" />
            <label for="sig_guard_hide_wordpress_version"><?php _e('Hide WordPress version','sig-guard'); ?></label>
          </td>
          <td>
            <?php _e('If it is checked, plugin will remove WordPress version information, e.g. &lt;meta name="generator" content="WordPress 2.9.2"/&gt; from your blog pages','sig_guard'); ?>
          </td>
        </tr>
      </table>
      <span style="color: green;">Note: Save your changes in options by press "Update" button before take any Rebuild or Scan actions.</span>
			<?php sig_guard_displayBoxEnd();?>
      <div class="fli submit" style="padding-top: 0px;">
          <input type="submit" name="submit" value="<?php _e('Update', 'sig-guard'); ?>" title="<?php _e('Save Changes', 'sig-guard'); ?>" />
          <input type="button" name="cancel" value="<?php _e('Cancel', 'sig-guard') ?>" title="<?php _e('Cancel not saved changes','sig-guard');?>" onclick="sig_guard_Settings('cancel');"/>
          <input type="button" name="scanNow" value="<?php _e('Scan Now', 'sig-guard') ?>" title="<?php _e('Scan and Fix directories, plugins version listing related problems Now','sig-guard');?>" onclick="sig_guard_Settings('scan');"/>
      </div>
						</div>
					</div>
				</div>
    </form>