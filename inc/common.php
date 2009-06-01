<?php
/* 
lylina news aggregator

Copyright (C) 2005 Andreas Gohr
Copyright (C) 2006-2007 Eric Harmon

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

require_once('inc/simplepie.inc');
define('SMARTY_DIR', 'inc/smarty/libs/');
require_once(SMARTY_DIR . 'Smarty.class.php');

if(version_compare("4.3.0",phpversion(),">"))
	die('Error: lylina requires PHP version 4.3.0 or higher, you are using PHP version '. phpversion() . '.');

$version = "1.21";

require_once('conf.php');
if(isset($conf['language']) && $conf['language'] != '' && @file_exists('lang/'.$conf['language'].'.inc.php'))
	require_once('lang/'.$conf['language'].'.inc.php');
else
	require_once('lang/en.inc.php');

$link = null;

/**
 * Executes an SQL query
 *
 * @returns two-dim array on select, insertID on insert 
 */
 
function runSQL($sql_string,$simple=false) {
	global $conf;
	global $link;

	$resultarray = array();

	if(!$link) {
		$link = mysqli_connect ($conf['db_host'], $conf['db_user'], $conf['db_pass']) or 
			die("DB Connection Error");
		mysqli_select_db($link, $conf['db_database']) or
			die("DB Selection Error");
	}

	if($simple) {
		return mysqli_query($link, $sql_string);
	}

//	$result = mysql_db_query($conf['db_database'],$sql_string,$link) or
//		die('Database Problem: '.mysqli_error($link)."\n<br />\n".$sql_string);

	$result = mysqli_query($link, $sql_string) or
		die('Database Problem: '.mysqli_error($link)."\n<br />\n".$sql_string);

	// mysql_db_query returns 1 on a insert statement -> no need to ask for results
	if (is_object($result)) {
		for($i=0; $i< mysqli_num_rows($result); $i++) {
			$temparray = mysqli_fetch_assoc($result);
			$resultarray[]=$temparray;
		}
		mysqli_free_result ($result);

//	} elseif ($result == 1 && mysqli_insert_id($link)) {
	} elseif ($result === true && mysqli_insert_id($link)) {
		$resultarray = mysqli_insert_id($link); //give back ID on insert
	}

	return $resultarray;
}

function prepairSQL($sql_string) {
	global $link;
	return mysqli_prepare($link, $sql_string);
}

/**
 * Build an string of URL parameters
 *
 * Handles URL encoding
 */
function buildURLparams($params,$sep='&amp;') {
	$url = '';
	$amp = false;
	foreach($params as $key => $val) {
		if($amp) $url .= $sep;

 		$url .= $key.'=';
		$url .= urlencode($val);
		$amp = true;
	}
	return $url;
}

/**
 * Build an string of html tag attributes
 *
 * Handles html encoding
 */
function buildAttributes($params) {
	$url = '';
	foreach($params as $key => $val) {
		$url .= $key.'="';
		$url .= htmlspecialchars($val);
		$url .= '" ';
	}
	return $url;
}

/**
 * Helper to print a variable's content - just for debugging
 */
function dbg($val) {
	print '<pre class="cms debug">';
	print_r($val);
	print '</pre>';
}

// http://www.php.net/manual/en/function.html-entity-decode.php
function unhtmlentities($string) {
	// replace numeric entities
	$string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
	$string = preg_replace('~&#([0-9]+);~e', 'chr(\\1)', $string);
	// replace literal entities
	$trans_tbl = get_html_translation_table(HTML_ENTITIES);
	$trans_tbl = array_flip($trans_tbl);
	$trans_tbl['&apos;'] = "'";
	return strtr($string, $trans_tbl);
}

/**
 * print a message
 *
 * If HTTP headers were not sent yet the message is added 
 * to the global message array else it's printed directly
 * using html_msgarea()
 * 
 *
 * Levels can be: 
 *  
 * -1 error
 *  0 info
 *  1 success
 *  
 * @author Andreas Gohr <andi@splitbrain.org>
 * @see    html_msgarea
 */ 
function msg($message,$lvl=0) {
	global $MSG;
	$errors[-1] = 'error';
	$errors[0]  = 'info';
	$errors[1]  = 'success';

	if(!headers_sent()) {
		if(!isset($MSG)) $MSG = array();
		$MSG[]=array('lvl' => $errors[$lvl], 'msg' => $message);
	} else {
		$MSG = array();
		$MSG[] = array('lvl' => $errors[$lvl], 'msg' => $message);
		if(function_exists('html_msgarea'))
			html_msgarea();
		else
			print "ERROR($lvl) $message";
	}
}

function getHost($location) {
	$temp = parse_url($location);
	return $temp['host'];
}

function writeHeaders() {
	global $lang;
	header('Content-Type: text/html; charset='. $lang['CHARSET']);
	header("X-Powered-By: lylina $version (+http://lylina.sourceforge.net)");
}

function doSetup() {
	global $lang;
	@setlocale(LC_All, $lang['LOCALE']);
}

function getFaviconURL($location) {
	if(!$location) {
		return false;
	} else {
		$temp = new SimplePie();
		$temp->set_feed_url($location);
		$temp->init();
		if($temp->get_favicon())
			return $temp->get_favicon();
		$url_parts = parse_url($location);
		$full_url = "http://$url_parts[host]";
		if(isset($url_parts['port']))
			$full_url .= ":$url_parts[port]";
		$favicon_url = $full_url . "/favicon.ico" ;
	}
	return $favicon_url;
}

function find_op($name) {
	return "ops/$name.inc.php";
}

//Setup VIM: ex: et ts=4 enc=utf-8 :
