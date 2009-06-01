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

require_once('inc/common.php');

doSetup();

@error_reporting(E_ERROR | E_PARSE);

if (!extension_loaded('mysql')){
	dl('mysql.so');
}

print "lylina $version, debug mode on\n";
print php_uname() . "\n\n";

$sql   = 'SELECT * FROM lylina_feeds';
$feeds = runSQL($sql);

foreach($feeds as $feed){
	print $feed['url'] . " | ";
	if(!file_exists("mirror/" . md5($feed['url']) . ".xml")) {
		echo "no cache";
	} else {
		$mod_time = filemtime("mirror/" . md5($feed['url']) . ".xml");
		echo $mod_time - time() + 300;
	}
	echo "\n";
}
