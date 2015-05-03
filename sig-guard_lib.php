<?php
/* 
 * * Silence Is Golden Guard plugin Lirary general staff
 * Author: Vladimir Garagulya vladimir@shinephp.com
 * 
 */


if (!function_exists("get_option")) {
  die;  // Silence is golden, direct call is prohibited
}

require_once(ABSPATH.WPINC.'/class-simplepie.php');

$sig_siteURL = get_option( 'siteurl' );

// Pre-2.6 compatibility
if ( !defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', $ure_siteURL . '/wp-content' );
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
define('SIG_GUARD_SIG_MESS', '// Silence is golden.');
define('SIG_GUARD_PLUGIN_STAMP', '// This file was created automatically by Silence is Golden Guard plugin');

$tmp = dirname(__FILE__);
if(strpos($tmp, '/', 0)!==false) {
	define('SIG_WINDOWS_SERVER', false);
  define('SIG_GUARD_DIR_SLASH', '/');
} else {
	define('SIG_WINDOWS_SERVER', true);
  define('SIG_GUARD_DIR_SLASH', '\\');
}

define('SIG_FAILURE_MESS', ' <span style="color: red;font-weight: bold;">:(</span><br/>');
define('SIG_SUCCESS_MESS', ' <span style="color: green;font-weight: bold;">OK</span><br/>');

$sig_guard_log_errors = get_option('sig_guard_log_errors');


function sig_guard_logEvent($message) {
  global $sig_guard_log_errors;
  if (!$sig_guard_log_errors) {
    return;
  }

  include(ABSPATH .'wp-includes/version.php');

  $fileName = SIG_GUARD_PLUGIN_DIR.'/sig-quard-'.md5(WP_PLUGIN_DIR).'.log';
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
      $fileName = $path . $fileName .SIG_GUARD_DIR_SLASH;
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
  $path = ABSPATH;

  if (SIG_WINDOWS_SERVER && strpos($path,'/')!==false) {
    $path = str_replace('/', SIG_GUARD_DIR_SLASH, $path);
  }
  sig_guard_scanSite($path, 1, $folders);

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
// end of sig_guard_optionChecked()


function sig_guard_htaccessUpdate($screenLog = false) {

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
        echo $errMess.SIG_FAILURE_MESS;
      }
      return;
    }
    while (!feof($fh)) {
      $s = stream_get_line($fh, 4096);
      if ($s===false) {
        $errMess = $fileName.' '.__('file read error');
        sig_guard_logEvent($errMess);
        if ($screenLog) {
          echo $errMess.SIG_FAILURE_MESS;
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
        echo $errMess.SIG_FAILURE_MESS;
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
        echo $errMess.SIG_FAILURE_MESS;
      }
      return;
    }
    if (!chmod($fileName, 0644)) {
      $errMess = $fileName.' '.__('file permissions change error');
      sig_guard_logEvent($errMess);
      if ($screenLog) {
        echo $errMess.SIG_FAILURE_MESS;
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
      echo $errMess.SIG_FAILURE_MESS;
    }
    return;
  }
  if (!fputs($fh, "\n")) {
    $errMess = $fileName.' '.__('file write error');
    sig_guard_logEvent($errMess);
    if ($screenLog) {
      echo $errMess.SIG_FAILURE_MESS;
    }
    return;
  }
  if (!fputs($fh, $keyComment."\n")) {
    $errMess = $fileName.' '.__('file write error');
    sig_guard_logEvent($errMess);
    if ($screenLog) {
      echo $errMess.SIG_FAILURE_MESS;
    }
    return;
  }
  if (!fputs($fh, $keyOption."\n")) {
    $errMess = $fileName.' '.__('file write error');
    sig_guard_logEvent($errMess);
    if ($screenLog) {
      echo $errMess.SIG_FAILURE_MESS;
    }
    return;
  }
  if (!fclose($fh)) {
    $errMess = $fileName.' '.__('file close error');
    sig_guard_logEvent($errMess);
    if ($screenLog) {
      echo $errMess.SIG_FAILURE_MESS;
    }
    return;
  }
  if (!chmod($fileName, 0644)) {
    $errMess = $fileName.' '.__('file permissions change error');
    sig_guard_logEvent($errMess);
    if ($screenLog) {
      echo $errMess.SIG_FAILURE_MESS;
    }
    return;
  }
  if (screenLog) {
    echo $fileName.' '.__('is modified').'<br/>';
  }

}
// end of sig_guard_htaccessUpdate()


function sig_fileRemove($fileName, $screenLog=false) {

  if (!chmod($fileName, 0777)) {
    $errMess = $fileName.' '.__('permissions change error');
    sig_guard_logEvent($errMess);
    if ($screenLog) {
      echo $errMess.SIG_FAILURE_MESS;
    }
    return false;
  }
  $deleteError = 0;
  if (!SIG_WINDOWS_SERVER) {
    if (!unlink($fileName)) {
      $deleteError = 1;
    }
  } else {
    $lines = array();
    exec("DEL /F/Q \"$fileName\"", $lines, $deleteError);
  }
  if ($deleteError) {
    chmod($fileName, 0755);
    $errMess = $indexFile.' '.__('file delete error');
    sig_guard_logEvent($errMess);
    if ($screenLog) {
      echo $errMess.SIG_FAILURE_MESS;
    }
    return false;
  }

  return true;
}
// end of sig_fileRemove()


function sig_indexFileCheck($indexFile, $sig_mess, $rebuildIndexFile, $screenLog, &$filesCreated) {

  $fileExists = file_exists($indexFile);
  if ($fileExists && !$rebuildIndexFile) {
    return true;
  }

  $errMess = '';
  if ($fileExists) {
// check if it is Silence is golden dummy index.php to not rewrite important file which can belong to other application
    $fh = fopen($indexFile,'r');
    if (!$fh) {
      $errMess = $indexFile.' '.__('file open error');
      sig_guard_logEvent($errMess);
      if ($screenLog) {
        echo $errMess.SIG_FAILURE_MESS;
      }
      return false;
    }
    $itsMyFile = false;
    $rows = 0;
    while (!feof($fh)) {
      $s = fgets($fh);
      $rows++;
      if (strpos($s, SIG_GUARD_PLUGIN_STAMP)!==false) {
        $itsMyFile = true;
      }
      if ($rows>4) { // SIG-made index.php can contain only 4 lines, not more
        $itsMyFile = false;
        break;
      }
    }
    if (!$itsMyFile) {
      return true;
    }
    if (!sig_fileRemove($indexFile, $screenLog)) {
      return false;
    }
  }  // if ($fileExists) ...

  $fh = fopen($indexFile,'w');
  if (!$fh) {
    $errMess = $indexFile.' '.__('file create error');
    sig_guard_logEvent($errMess);
    if ($screenLog) {
      echo $errMess.SIG_FAILURE_MESS;
    }
    return false;
  }

// WP Super Cache compatability issue fix: redirection directive in /wp-super-cache/plugins/index.php file leads to endless redirection loop, so it should be excluded
  if (defined('WPCACHEHOME')) {
    if (strpos($indexFile, 'wp-super-cache'.SIG_GUARD_DIR_SLASH.'plugins'.SIG_GUARD_DIR_SLASH)!==false) {
      $sig_mess = "<?php\r\n".SIG_GUARD_PLUGIN_STAMP."\r\n?>";
    }
  }

  if (!fwrite($fh, $sig_mess)) {
    $errMess = $indexFile.' '.__('file write error');
    sig_guard_logEvent($errMess);
    if ($screenLog) {
      echo $errMess.SIG_FAILURE_MESS;
    }
    fclose($fh);
    return false;
  }
  if (!fclose($fh)) {
    $errMess = $indexFile.' '.__('file close error');
    sig_guard_logEvent($errMess);
    if ($screenLog) {
      echo $errMess.SIG_FAILURE_MESS;
    }
    return false;
  }
  if (!chmod($indexFile, 0644)) {
    $errMess = $indexFile.' '.__('permissions change error');
    sig_guard_logEvent($errMess);
    if ($screenLog) {
      echo $errMess.SIG_FAILURE_MESS;
    }
    return false;
  }
  if (!$errMess) {
    if ($screenLog) {
      echo $indexFile.' '.__('file is created').SIG_SUCCESS_MESS;
    }
    $filesCreated++;
  }

  return true;
}
// end of sig_IndexFileCheck()


function sig_deleteReadMeFile($folder, $wp_plugin_dir, $screenLog, &$filesDeleted) {

  if (strlen($wp_plugin_dir)<=strlen($folder) && strpos($folder, $wp_plugin_dir)!==false) {
    $fileNames = array('readme.txt', 'README.TXT', 'README.txt', 'version.txt', 'VERSION.txt', 'VERSION.TXT');
    $fileDeleteError = false;
    foreach ($fileNames as $fileName) {
      $fileToDelete = $folder.$fileName;
      if (file_exists($fileToDelete)) {
        if (!sig_fileRemove($fileToDelete, $screenLog)) {
          $fileDeleteError = true;
        } else {
          $filesDeleted++;
          if ($screenLog) {
            echo $fileToDelete.' <span style="color: red;">'.__('is deleted', 'sig-guard').'</span>'.SIG_SUCCESS_MESS;            
          }
        }
        break;
      }
    }
    if ($fileDeleteError) {
      return false;
    }
  } // if (strlen($wp_plugin_dir) ...

  return true;
}
// end of sig_deleteReadMeFile()


// delete all plugins screenshots screenshot*.png, screenshot*.gif, screenshot*.jpg
function sig_deleteScreenShots($folder, $wp_plugin_dir, $screenLog, &$filesDeleted) {

  if (strlen($wp_plugin_dir)>strlen($folder) || strpos($folder, $wp_plugin_dir)===false) {
    return true;
  }

	$fileDeleteError = false;
  $dir = @opendir($folder);
  while($fileName = readdir($dir)) {
    if ($fileName == '.' || $fileName == '..') {
      continue;
    }
    $fileName1 = strtolower($fileName);
    if (strpos($fileName1,'screenshot')===false) {
      continue;
    }
    $tmp = explode('.', $fileName1);
    $extension = end($tmp);
    if ($extension=='png' || $extension=='gif' || $extension=='jpg') {
      $fileName = $folder . $fileName;
      if (!sig_fileRemove($fileName, $screenLog)) {
        $fileDeleteError = true;
        break;
      } else {
        $filesDeleted++;
        if ($screenLog) {
          echo $fileName.' <span style="color: red;">'.__('is deleted', 'sig-guard').'</span>'.SIG_SUCCESS_MESS;
        }
      }
    }
  }
  closedir($dir);

  if ($fileDeleteError) {
    return false;
  }
  
  return true;
}
// end of sig_deleteScreenShots()


function sig_guard_Scan($screenLog = false, $rebuildIndexFile = false) {
  
  global $sig_siteURL;

  $wp_plugin_dir = WP_PLUGIN_DIR;
  if (SIG_WINDOWS_SERVER) {
    $wp_plugin_dir = str_replace('/', SIG_GUARD_DIR_SLASH, $wp_plugin_dir);
  }
  $wp_plugin_dir .= SIG_GUARD_DIR_SLASH;
  
  $sig_guard_redirect_tohomepage = get_option('sig_guard_redirect_tohomepage');
  $sig_guard_delete_readme = get_option('sig_guard_delete_readme');
  $sig_guard_delete_screenshot = get_option('sig_guard_delete_screenshot');

  $br = '<br/>'; 
  $sig_mess = "<?php\r\n".SIG_GUARD_PLUGIN_STAMP."\r\n";

  if ($sig_guard_redirect_tohomepage) {
    $sig_mess .= 'header("Location: '.$sig_siteURL.'");'."\r\n";
  } else {
    $sig_mess .= SIG_GUARD_SIG_MESS."\r\n";
  }
  $sig_mess .= '?>';
  
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
  $filesDeleted = 0;
  foreach ($folders as $folder) {
    if ($excludeFolders && is_array($excludeFoldersList)) {
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
// index.php check/create
    $fileName = $folder.'index.php';
    if (!sig_indexFileCheck($fileName, $sig_mess, $rebuildIndexFile, $screenLog, $filesCreated)) {
      continue;
    }
// readme.txt delete - from wp-content directory only
    if ($sig_guard_delete_readme) {
      if (!sig_deleteReadMeFile($folder, $wp_plugin_dir, $screenLog, $filesDeleted)) {
        continue;
      }
    }  // if ($sig_guard_delete_readme ...

// plugin screenshots delete - from wp-content directory only    
    if ($sig_guard_delete_screenshot) {
      sig_deleteScreenShots($folder, $wp_plugin_dir, $screenLog, $filesDeleted);
    }
    
  }  // foreach ($folders ...

  $useHtaccess = get_option('sig_guard_use_htaccess');
  if ($useHtaccess) {
    sig_guard_htaccessUpdate($screenLog);
  }
  
  if ($screenLog) {
    echo $br.__('"Silence is Golden" scan is finished:', 'sig-guard').$br;
    echo sprintf(__('%s index.php files are created', 'sig-guard'), $filesCreated).$br.
         sprintf(__('%s unused files are deleted', 'sig-guard'), $filesDeleted).$br.$br;
    echo '<a href="'.SIG_GUARD_WP_ADMIN_URL.'/options-general.php?page=sig-guard.php">'.__('Return back to SIG Guard Settings Page','sig-guard').'</a>'.$br;
  }
  update_option('sig_guard_last_check', time());
}
// end of sig_guard_Scan()


?>
