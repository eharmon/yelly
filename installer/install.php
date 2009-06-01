<?php
/* 
lylina news aggregator

Copyright (C) 2006 Eric Harmon

lylina is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

lylina is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with lylina; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

require_once('installer/common.php');

writeInstallHead();

switch($_REQUEST['do']) {
	case 'install':
		doInstall();
		break;
	default:
		doSelect();
		break;
}

function doSelect() {
	print '<div class="notice">Welcome to the lylina installer. This system will guide the setup process and configure lylina for your use.<br /><br />Please configure lylina to your preferences. If you do not understand any of these options, it\'s probably best to leave it at the default value.</div>';
	if(!is_writable('conf.php'))
		if(is_callable('chmod'))
			@chmod("conf.php",0777) or die('<div class="error">Unable to gain write access to conf.php! Please use chmod to set permissions 0777! <a href="index.php">Click here to continue</a></div>');
		else
			die('<div class="error">Unable to gain write access to conf.php! Please use chmod to set permissions 0777! <a href="index.php">Click here to continue</a></div>');
	if(!is_writable('lockfile'))
		if(is_callable('chmod'))
			@chmod("lockfile",0777) or die('<div class="error">Unable to gain write access to lockfile! Please use chmod to set permissions 0777! <a href="index.php">Click here to continue</a></div>');
		else
			die('<div class="error">Unable to gain write access to lockfile! Please use chmod to set permissions 0777! <a href="index.php">Click here to continue</a></div>');
?>
<form action="index.php" method="post">
<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
	<td><span class="title">MySQL Host:</span></td>
	<td><input name="host" type="text" value="localhost" /></td>
</tr>
<tr>
	<td><span class="title">MySQL User:</span></td>
	<td><input name="user" type="text" value="" /></td>
</tr>
<tr>
	<td><span class="title">MySQL Password:</span></td>
	<td><input name="pass" type="password" value="" />
</td>
</tr>
<tr>
	<td><span class="title">MySQL Database:</span></td>
	<td><input name="database" type="text" value="" /></td>
</tr>
<tr>
	<td><span class="title">Display Mode:</span></td>
	<td><select name="mode">
  		<option value="normal" selected="selected">Multiuser</option>
  		<option value="login">Multiuser (required login)</option>
  		<option value="single">Single user</option>
	</select></td>
</tr>
<tr>
	<td><span class="title">Page Title:</span></td>
	<td><input name="title" type="text" value="lylina rss aggregator" /></td>
</tr>
<tr>
	<td><span class="title">RSS feed description:</span></td>
	<td><input name="feed" type="text" value="lylina rss aggregator" /></td>
</tr>
<tr>
	<td><span class="title">Path to logo:</span></td>
	<td><input name="logo" type="text" value="img/logo_twooh-trans.png" /></td>
</tr>
<tr>
	<td><span class="title">Language:</span></td>
	<td><select name="language">
<?php

$languages = getDirFiles('lang');
foreach($languages as $language) {
	require('lang/' . $language);
	$temp = explode('.',$language);
	$language = $temp[0];
	if($language != 'en')
		print '  <option value="' . $language .'">'. $lang['NAME'] . '</option>';
	else
		print '  <option value="en" selected="selected">English</option>';
}
?>
	</select></td>
</tr>
<tr>
	<td><span class="title">Use Cron Jobs:</span></td>
	<td><input name="usecron" type="checkbox" value="true" /></td>
</tr>
<tr>
	<td><span class="title">Page style:</span></td>
	<td><select name="style">
<?php

$styles = getDirFiles('style');
foreach($styles as $style) {
	$temp = file('style/'.$style);
	foreach($temp as $line) {
		if(stripos($line,"Style: ") !== false) {
			$out = explode(": ", $line);
			print '  <option value="' . $style . '">'. $out[1] . '</option>';
			break;
		}
	}
}
?>
	</select></td>
</tr>
<tr>
	<td><span class="title">Show Sources:</span></td>
	<td><input name="sources" type="checkbox" value="true" /></td>
</tr>
<tr>
	<td><span class="title">Show Digg Integration:</span></td>
	<td><input name="digg" type="checkbox" value="true" checked="checked" /></td>
</tr>
<tr>
	<td><span class="title">Show del.icio.us Integration:</span></td>
	<td><input name="delicious" type="checkbox" value="true" checked="checked" /></td>
</tr>
<tr>
	<td><span class="title">Show reddit Integration:</span></td>
	<td><input name="reddit" type="checkbox" value="true" /></td>
</tr>
<tr>
	<td><span class="title">Show furl Integration:</span></td>
	<td><input name="furl" type="checkbox" value="true" checked="checked" /></td>
</tr>
<tr>
	<td><span class="title">Show Newsvine Integration:</span></td>
	<td><input name="newsvine" type="checkbox" value="true" /></td>
</tr>
<tr>
	<td><span class="title">Open Links in New Window:</span></td>
	<td><input name="newwindow" type="checkbox" value="true" checked="checked" /></td>
</tr>
<tr>
	<td><span class="title">Allow Searching:</span></td>
	<td><input name="search" type="checkbox" value="true" /></td>
</tr>
<tr>
	<td><input name="reset" type="reset" value="Reset Form" style="float:none;" /></td>
	<td><input name="do" type="hidden" value="install" /><input name="Save" type="submit" value="Install" /></td>
</tr>
</table>
</form>
<?php
}

function doInstall() {
	global $version;
	print '<div class="notice">Please wait while the installer configures lylina...</div>';
	$link = @mysql_connect($_POST['host'], $_POST['user'], $_POST['pass']) or
                	die('<div class="error">Unable to connect to MySQL with user ' . $_POST['user'] . '@' . $_POST['host'] .'! Please <a href="javascript:history.go(-1);">go back</a> and check your settings.</div>');
	@mysql_select_db($_POST['database']) or
					die('<div class="error">Unable to access database with user ' . $_POST['user'] . '@' . $_POST['host'] .'! Please <a href="javascript:history.go(-1);">go back</a> and check your settings.</div>');
	print '<div class="success">MySQL connection success...</div>';
	
	print '<div class="notice">Adding data to MySQL database...</div>';
	
	$data = file_get_contents('installer/lylina.sql');
	
	$data = explode(';',$data);
	
	foreach($data as $item)
		if($item != '')
			mysql_query($item) or die('<div class="error">There was a problem adding data to the MySQL database. ' . mysql_error() . '</div>');
	
	print '<div class="success">MySQL data added...</div>';
	
	if(!$_POST['usecron'])
		$_POST['usecron'] = 'false';
	if(!$_POST['sources'])
		$_POST['sources'] = 'false';
	if(!$_POST['digg'])
		$_POST['digg'] = 'false';
	if(!$_POST['delicious'])
		$_POST['delicious'] = 'false';
	if(!$_POST['reddit'])
		$_POST['reddit'] = 'false';
	if(!$_POST['furl'])
		$_POST['furl'] = 'false';
	if(!$_POST['newsvine'])
		$_POST['newsvine'] = 'false';
	if(!$_POST['newwindow'])
		$_POST['newwindow'] = 'false';
	if(!$_POST['search'])
		$_POST['search'] = 'false';
		
	print '<div class="notice">Writing configuration file...</div>';
	$file = fopen('conf.php','w+');
	fwrite($file,'<?php
// Welcome to lylina

// This file was automatically generated by the installer.

// =============================================================
//
// The following options MUST be correct in order for lylina to
// operate properly.
//
// =============================================================

// -------------------- MySQL Configuration --------------------
// This tells lylina which database to store information in.

$conf[\'db_host\']     = \'' . $_POST['host'] . '\';
$conf[\'db_user\']     = \'' . $_POST['user'] . '\';
$conf[\'db_pass\']     = \'' . $_POST['pass'] . '\';
$conf[\'db_database\'] = \'' . $_POST['database'] . '\';

// =============================================================
//
// The following options allow you to futher customize lylina
//
// =============================================================

// ----------------------- Display Mode  -----------------------
// This will allow you to configure which "display mode" to use
// with lylina. Valid settings are: "normal" - multiuser system,
// "login" - multiuser requiring login to use, "single" - a
// single user system requiring login only to edit sources.

$conf[\'mode\']        = \'' . $_POST['mode'] . '\';

// ------------------------ Page Title -------------------------
// Sets the titlebar for the page

$conf[\'page_title\']  = \'' . $_POST['title'] . '\';

// ---------------------- RSS Description ----------------------
// Chooses the description for the RSS feed

$conf[\'rss_desc\']    = \'' . $_POST['feed'] . '\';

// ------------------------- Page Logo -------------------------
// Allows you to change the logo seen in the page

$conf[\'page_logo\']   = \'' . $_POST['logo'] . '\';

// -------------------------- Language -------------------------
// Contols the language used by lylina.
// Languages must be in the \'lang\' folder, and must use the
// ISO language code, ie: \'en.inc.php\'

$conf[\'language\']    = \'' . $_POST['language'] . '\';

// -------------------------- Use Cron -------------------------
// Set this option to false if you are not using a cron job.
// Note that this slows page loading speeds drastically.

$conf[\'usecron\']     = \'' . $_POST['usecron'] . '\';

// ------------------------- Page Style ------------------------
// Allows you to choose the CSS skin used to theme the page.
// This file MUST be in the \'style\' folder.

$conf[\'page_style\']  = \'' . $_POST['style'] . '\';

// -------------------------- Sources --------------------------
// Controls the display of sources at the bottom of the page

$conf[\'sources\']     = \'' . $_POST['sources'] . '\';

// -------------------- Digg.com integration -------------------
// Adds a "submit to digg" link to each post, letting you
// quickly post it to digg.com

$conf[\'digg\']        = \'' . $_POST['digg'] . '\';

// ------------------- del.icio.us integration -----------------
// Adds an "add to del.icio.us" link to each post, letting you
// post it quickly to your social bookmarks.

$conf[\'del.icio.us\'] = \'' . $_POST['delicious'] . '\';

// --------------------- reddit integration --------------------
// Adds a "submit to reddit" link to each post, letting you
// post it quickly to reddit

$conf[\'reddit\']      = \'' . $_POST['reddit'] . '\';

// ---------------------- furl integration ---------------------
// Adds an "add to furl" link to each post, letting you submit
// the item to your furl bookmarks

$conf[\'furl\']        = \'' . $_POST['furl'] . '\';

// -------------------- newsvine integration -------------------
// Adds a "seed at newsvine" link to each post, letting you 
// easy seed the newsvine.

$conf[\'newsvine\']        = \'' . $_POST['newsvine'] . '\';

// ---------------- Open News Links in New Window --------------
// Turn this on to force links to news items to open in new
// browser windows.

$conf[\'new_window\']  = \'' . $_POST['newwindow'] . '\';

// -------------------------- Search ---------------------------
// Enabled or disable the search function

$conf[\'search\']      = \'' . $_POST['search'] . '\';

// =============================================================
//
// The following options ARE FOR ADVANCED USERS ONLY.
// These options allow for powerful adjustments to how lylina
// works, and should only be used by advanced users.
//
// =============================================================

// ------------------------- Debug Mode ------------------------
// This setting will turn on debugging mode for item fetching.

$conf[\'debug\']       = \'false\';

// --------------------- Password Encryption -------------------
// This sets the password encryption scheme in lylina. DO NOT
// CHANGE THIS SETTING AFTER YOU SETUP USERS IN LYLINA. No users
// will be able to login. By default lylina sets up the admin
// user with a smd5 encrypted password, thus, you will have to
// change this manually in MySQL. It is not suggested you change
// this setting.
// 
// Valid encryption schemes: smb5, md5, sha1, ssha, crypt, mysql,
// my411

$conf[\'passcrypt\']   = \'smd5\';

// ---------------------- HTMLSax3 location --------------------
// Specifies the location of the HTMLSax3 library. This is
// installed by lylina, thus, should not require modification.

define(\'XML_HTMLSAX3\',\'inc/safehtml/\');

// ------------------ MagpieRSS Cache Directory ----------------
// Specifies the location where MagpieRSS should cache feeds.
// This should never require modification.

define(\'MAGPIE_CACHE_DIR\', \'cache\');

// -------------------------- Version --------------------------
// This tells the lylina installer which version of lylina you
// currently have installed.

$conf[\'version\']     = \'' . $version . '\';

?>
');
	fclose($file);
	print '<div class="success">Configuration file written...</div>';
	print '<div class="notice">Done! <a href="index.php">Click here to continue</a></div>';
}

die();
?>
