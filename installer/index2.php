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

require_once('../conf.php');
require_once('../inc/common.php');
require_once('../inc/auth.php');

if(!isset($conf))
	$conf = array();
else {
	$UID = checkAuth($_REQUEST);
	if($UID != 1)
		die('Sorry, you cannot reconfigure lylina');
}
	
doHeader();

if($_REQUEST['do'] == 'write')
	setPrefs();
else
	readPrefs();
	
doFooter();

function doHeader() {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>lylina installer</title>
</head>

<body>
<img src="../img/logo.png" width="40" height="25" />
<?php echo "You are $UID!"; ?>
<?
}

function doFooter() {
	print '</body></html>';
}

function readPrefs() { }
	