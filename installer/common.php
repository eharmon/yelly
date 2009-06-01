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

function getDirFiles($dirPath)
  {
    if ($handle = opendir($dirPath)) 
     {
         while (false !== ($file = readdir($handle))) 
             if ($file != "." && $file != "..") 
                $filesArr[] = trim($file);
                 
        closedir($handle);
     }  
     
     return $filesArr; 
 }

if (!function_exists("stripos")) {
  function stripos($str,$needle) {
   return strpos(strtolower($str),strtolower($needle));
  }
}

function check_version($v1,$v2) {
	return version_compare($v1,$v2);
}

function verify_upgrade($upgrade_bit) {
	if(!$upgrade_bit)
		die('Upgrade error, upgrade cannot be run directly');
}

function writeInstallHead() {
	header("X-Powered-By: lylina $version (+http://lylina.sourceforge.net)");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/1">
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="-1" />
<title><? echo $conf['page_title']; ?>lylina installation system</title>
<link rel="stylesheet" type="text/css" href="installer/install.css" media="screen" />
</head>
<body>
<div class="logo"><img src="installer/logo.png"></div>
<div class="main">
<?php
}




?>