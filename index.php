<?php

/* 
lylina news aggregator

Copyright (C) 2005 Andreas Gohr
Copyright (C) 2006-2007 Eric Harmon

Contains code from 'lilina':
Copyright (C) 2004-2005 Panayotis Vryonis

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

// Include common functions and load config
require_once('inc/common.php');
require_once('inc/auth.php');

// Set the locale based on our language
@setlocale(LC_All, $lang['LOCALE']);

// Setup path
$directory = getcwd();

// Fetch the params from the URL
$page = $_REQUEST['page'];
$op = $_REQUEST['op'];

// If we have not been told what to do, default to displaying the main page
if(!$page && !$op) {
	$page = "main";
}

// If we've got to load a page
if($page) {
	// If we aren't setup yet, run the installer
	if(!isset($conf) || $conf == '')
		require_once('installer/install.php');
	elseif($conf['version'] != $version) {
		$upgrade_bit = true;
		require_once('installer/upgrade.php');
	}
	
	// Perform basic includes for all pages
	require_once("inc/smarty/libs/Smarty.class.php");
	require_once('inc/html.php');
	require_once('inc/display.php');

	$UID = checkAuth($_REQUEST);
	
	// Output page headers
	writeHeaders();
	
	// Setup the smarty output and include basic variables
	$output = new Smarty();
	$output->assign('lang', $lang); 		// Language
	$output->assign('conf', $conf); 		// Main configuration
	$output->assign('version', $version); 	// Lylina version
	$output->assign('user', $UID);			// User ID
	require_once("pages/$page.inc.php");
	
	// Run smarty display
	$output->display("$page.tpl");
} else { // If we've got to perform a function
	require_once(find_op($op));
}

?>