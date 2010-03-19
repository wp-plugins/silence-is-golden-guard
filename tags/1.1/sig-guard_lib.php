<?php
/* 
 * * Silence Is Golden Guard plugin Lirary general staff
 * Author: Vladimir Garagulya vladimir@shinephp.com
 * 
 */


if (!defined("WPLANG")) {
  die;  // Silence is golden, direct call is prohibited
}

$sig_siteURL = get_option( 'siteurl' );

// Pre-2.6 compatibility
if ( !defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', $thanks_siteURL . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
      define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
      define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

$sig_guardPluginDirName = substr(dirname(__FILE__), strlen(WP_PLUGIN_DIR)+1, strlen(__FILE__)-strlen(WP_PLUGIN_DIR)-1);

define('SIG_GUARD_PLUGIN_URL', WP_PLUGIN_URL.'/'.$sig_guardPluginDirName);
define('SIG_GUARD_PLUGIN_DIR', WP_PLUGIN_DIR.'/'.$sig_guardPluginDirName);
define('SIG_GUARD_WP_ADMIN_URL', $sig_siteURL.'/wp-admin');
define('SIG_GUARD_ERROR', 'Error is encountered');


function sig_guard_logEvent($message) {
  include(ABSPATH .'wp-includes/version.php');

  $fileName = SIG_GUARD_PLUGIN_DIR.'/sig-quard.log';
  $fh = fopen($fileName,'a');
  $cr = "\n";
  $s = $cr.date("d-m-Y H:i:s").$cr.
      'WordPress version: '.$wp_version.', PHP version: '.phpversion().', MySQL version: '.mysql_get_server_info().$cr;
  fwrite($fh, $s);
  fwrite($fh, $message.$cr);
  fclose($fh);

}
// end of sig_guard_logEvent()


function sig_guard_scanSite($path, $recurs, &$folders) {

  $dir = @opendir($path);

  if ($dir) {
    while($fileName = readdir($dir)) {
      if ($fileName == '.' || $fileName == '..') {
        continue;
      }
      $fileName = $path . $fileName .'/';
      if (is_dir($fileName) && $recurs) {
        $folders[] = $fileName;
        sig_guard_scanSite($fileName, 1, $folders);
      }
    }
    closedir($dir);
  }
}
// end of sig_guard_scanSite()

function sig_guard_getBlogFolders() {

  $folders = array();

  sig_guard_scanSite(ABSPATH, 1, $folders);

  return $folders;
}
// end of sig_guard_getBlogFolders()


function sig_guard_optionChecked($value, $etalon) {
  $checked = '';
  if ($value==$etalon) {
    $checked = 'checked="checked"';
  }

  return $checked;
}


/*
function sig_guard_optionSelected($value, $etalon) {
  $selected = '';
  if ($value==$etalon) {
    $selected = 'selected="selected"';
  }

  return $selected;
}
*/


function sig_guard_htaccessUpdate($screenLog = false) {
  $br = '<br/>';
  $keyComment = '#Silence is Golden Guard plugin';
  $keyOption = 'Options -Indexes'."\n";
  $fileName = ABSPATH.'.htaccess';
  if (file_exists($fileName)) {
    $modifiedAlready = false;
    $fh = fopen($fileName,'r');
    if (!$fh) {
      $errMess = $fileName.' '.__('file open error');
      sig_guard_logEvent($errMess);
      if ($screenLog) {
        echo $errMess.$br;
      }
      return;
    }
    while (!feof($fh)) {
      $s = stream_get_line($fh, 4096);
      if ($s===false) {
        $errMess = $fileName.' '.__('file read error');
        sig_guard_logEvent($errMess);
        if ($screenLog) {
          echo $errMess.$br;
        }
        return;
      }
      if (strpos($s, $keyComment)!==false) {
        $modifiedAlready = true;
        break;
      }
    }
    if (!fclose($fh)) {
      $errMess = $fileName.' '.__('file close error');
      sig_guard_logEvent($errMess);
      if ($screenLog) {
        echo $errMess.$br;
      }
      return;
    }
    if ($modifiedAlready) {
      return;
    }
    if (!copy($fileName, $fileName.'.bak')) {
      $errMess = $fileName.' '.__('file backup copy error');
      sig_guard_logEvent($errMess);
      if ($screenLog) {
        echo $errMess.$br;
      }
      return;
    }
    if (!chmod($fileName, 0644)) {
      $errMess = $fileName.' '.__('file permissions change error');
      sig_guard_logEvent($errMess);
      if ($screenLog) {
        echo $errMess.$br;
      }
      return;
    }
    $fh = fopen($fileName,'a');
  } else {   
    $fh = fopen($fileName,'w');
  }
  if (!$fh) {
    $errMess = $fileName.' '.__('file open error');
    sig_guard_logEvent($errMess);
    if ($screenLog) {
      echo $errMess.$br;
    }
    return;
  }
  if (!fputs($fh, "\n")) {
    $errMess = $fileName.' '.__('file write error');
    sig_guard_logEvent($errMess);
    if ($screenLog) {
      echo $errMess.$br;
    }
    return;
  }
  if (!fputs($fh, $keyComment."\n")) {
    $errMess = $fileName.' '.__('file write error');
    sig_guard_logEvent($errMess);
    if ($screenLog) {
      echo $errMess.$br;
    }
    return;
  }
  if (!fputs($fh, $keyOption."\n")) {
    $errMess = $fileName.' '.__('file write error');
    sig_guard_logEvent($errMess);
    if ($screenLog) {
      echo $errMess.$br;
    }
    return;
  }
  if (!fclose($fh)) {
    $errMess = $fileName.' '.__('file close error');
    sig_guard_logEvent($errMess);
    if ($screenLog) {
      echo $errMess.$br;
    }
    return;
  }
  if (!chmod($fileName, 0644)) {
    $errMess = $fileName.' '.__('file permissions change error');
    sig_guard_logEvent($errMess);
    if ($screenLog) {
      echo $errMess.$br;
    }
    return;
  }
  if (screenLog) {
    echo $fileName.' '.__('is modified').'<br/>';
  }

}
// end of sig_guard_htaccessUpdate()


function sig_guard_Scan($screenLog = false) {

  $br = '<br/>';
  $sig_mess = "<?php
// Silence is golden.
?>";
  $okMess = '<span style="color: green;font-weight: bold;">OK</span>';
  $excludeFolders = get_option('sig_guard_exclude_folders');
  if ($excludeFolders) {
    $excludeFoldersList = get_option('sig_guard_exclude_folders_list');
  } else {
    $excludeFoldersList = array();
  }
  if ($screenLog) {
    echo $br;
  }
  $folders = sig_guard_getBlogFolders();
  $filesCreated = 0;
  foreach ($folders as $folder) {
    if ($excludeFolders) {
      $excludedFolder = false;
      foreach ($excludeFoldersList as $folderToExclude) {
        if ($folder==$folderToExclude) {
          $excludedFolder = true;
          break;
        }
      }
      if ($excludedFolder) {
        continue;
      }
    }
    $indexFile = $folder.'index.php';
    if (!file_exists($indexFile)) {
      $errMess = '';
      $fh = fopen($indexFile,'w');
      if (!$fh) {
        $errMess = $indexFile.' '.__('file create error');
        sig_guard_logEvent($errMess);
        if ($screenLog) {
          echo $errMess.$br;
        }
        continue;
      }
      if (!fwrite($fh, $sig_mess)) {
        $errMess = $indexFile.' '.__('file write error');
        sig_guard_logEvent($errMess);
        if ($screenLog) {
          echo $errMess.$br;
        }
        fclose($fh);
        continue;
      }
      if (!fclose($fh)) {
        $errMess = $indexFile.' '.__('file close error');
        sig_guard_logEvent($errMess);
        if ($screenLog) {
          echo $errMess.$br;
        }
        continue;
      }
      if (!chmod($indexFile, 0644)) {
        $errMess = $indexFile.' '.__('permissions change error');
        sig_guard_logEvent($errMess);
        if ($screenLog) {
          echo $errMess.$br;
        }
        continue;
      }
      if (!$errMess) {
        if ($screenLog) {
          echo $indexFile.' '.__('file is created').' '.$okMess.$br;
        }
        $filesCreated++;
      }
    }
  }

  $useHtaccess = get_option('sig_guard_use_htaccess');
  if ($useHtaccess) {
    sig_guard_htaccessUpdate($screenLog);
  }
  
  if ($screenLog) {
    echo __('"Silence is Golden" Scan is finished: '.$filesCreated.' index.php files are created').$br.$br;
    echo '<a href="'.SIG_GUARD_WP_ADMIN_URL.'/options-general.php?page=sig-guard.php">Return back to SIG Guard Settings Page</a>'.$br;
  }
  update_option('sig_guard_last_check', time());
}
// end of sig_guard_Scan()

?>
