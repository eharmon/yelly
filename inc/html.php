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

define('NL',"\n");

/**
 * Prints the global message array
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */
function html_msgarea() {
	global $MSG;

	if(!isset($MSG)) return;

	foreach($MSG as $msg) {
		print '<div class="'.$msg['lvl'].'"><img src="img/information-trans.png" alt="" /> ';
		print $msg['msg'];
		print '</div>'; 
  	} 
} 


function html_head($news_page, $hours = -1) {
	global $conf;
	global $lang;
	global $UID;
	print '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'.NL;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/1">
    <title><? echo $conf['page_title']; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['CHARSET']; ?>" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="-1" />
    <!--[if lt IE 7]>
    <script language="JavaScript" type="text/javascript" src="js/ie7-standard-p.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="style/<?php echo $conf['page_style']; ?>" media="screen" />
    <link rel="stylesheet" type="text/css" href="style/mobile.css" media="handheld" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link rel="alternate" type="application/rss+xml" title="RSS" href="rss.php/<?php echo md5($UID) . '/' . $UID . '.xml'; ?>" />
    <script language="JavaScript" type="text/javascript">
    <!--
        var showDetails = false;
        var markID = '' ;
    //-->
    </script>
    <script language="JavaScript" type="text/javascript" src="js/engine.js"></script>
    <?php if($news_page) { ?>
    <script language="JavaScript" type="text/javascript">
    <!--
        var feed = "rss.php/<?= md5($UID) . '/' . $UID . '.xml' ?>";
        var hours = <?php echo "$hours"; ?>;
    //-->
    </script>
<!--    <script language="JavaScript" type="text/javascript" src="js/ajax.js"></script> -->
    <?php } ?>
</head>
<body onload="visible_mode(showDetails)">

<?php
	html_navigation($news_page);
	html_msgarea();
	print '<div id="main">';
}

function html_foot() {
	global $lang;
	global $version;
	global $UID;
	print '</div>';
	// We're sure you'd love to remove the 'powered by' logo, but please consider leaving it here to support the project!
	print '<div id="footer">'.$lang['powered'].' <a href="http://lylina.sf.net"><img src="img/logo-trans.png" alt="lylina" /></a> v'.$version;
	print '<div class="feedico"><a href="rss.php/' . md5($UID) . '/' . $UID . '.xml'. '"><img src="img/feed-trans.png" alt="RSS feed" /></a> </div>';
	print '</div>';
	print '</body>';
	print '</html>';
}

function html_navigation($news_page) {
	global $conf;
	global $UID;
	global $lang;

	print '<div id="navigation">'.NL;

	print '<a href="index.php"><img src="'.$conf['page_logo'].'" alt="'.$conf['page_title'].'" /></a> ';
	print '<img src="img/spacer.gif" width="10" height="1" alt="" class="spacer" />';
	print '<img src="img/calendar-trans.png" alt="" /> ';
	
	print '<a href="index.php?hours=4">';
	print $lang['4hours'];
	print '</a>'.NL;

	print '<a href="index.php?hours=8">';
	print $lang['8hours'] . ' (' . $lang['default'] . ')';
	print '</a>'.NL;

	print '<a href="index.php?hours=16">';
	print $lang['16hours'];
	print '</a>'.NL;

	print '<a href="index.php?hours=24">';
	print $lang['1day'];
	print '</a>'.NL;

	print '<a href="index.php?hours=168">';
	print $lang['1week'];
	print '</a>'.NL;

	print '<a href="javascript:forceRefresh()">Refresh</a>';

	if($news_page == true) {
		print '<img src="img/spacer.gif" width="50" height="1" alt="" class="spacer" />';

		print '<img src="img/toggle-trans.png" alt="" /> ';

		print '<a href="javascript:visible_mode(true);">';
		print $lang['expand'];
		print '</a>'.NL;

		print '<a href="javascript:visible_mode(false);">';
		print $lang['collapse'];
		print '</a>'.NL;
	}
		
	if($UID) {
		print '<img src="img/spacer.gif" width="50" height="1" alt="" class="spacer" />';
		print '<img src="img/preferences-trans.png" alt="" /> ';

        	print '<a href="edit.php">';
        	print $lang['preferences'];
       		print '</a>'.NL;

		print '<img src="img/spacer.gif" width="50" height="1" alt="" class="spacer" />';
		print '<img src="img/logout-trans.png" alt="" /> ';

        	print '<a href="index.php?logout=1">';
        	print $lang['logout'];
       		print '</a>'.NL;
	}

	print '<img src="img/spacer.gif" width="50" height="1" alt="" class="spacer" />';

	if(!$UID) {
		print '<form method="post" action="index.php" class="login">'.NL;
		print '<img src="img/users-trans.png" alt="user" /> <input type="text" name="u" />'.NL;
		print '<img src="img/password-trans.png" alt="password" /> <input type="password" name="p" />'.NL;
		print '<input type="submit" value="'.$lang['login'].'" />'.NL;
		print '</form>'.NL;
	} else {
		print '<form method="get" action="search.php" class="login">'.NL;
		print '<img src="img/search-trans.png" alt="search" /> <input type="text" name="q" />'.NL;
		print '<input type="submit" value="'.$lang['search'].'" />'.NL;
		print '</form>'.NL;
	}

	print '</div>';
}

//Setup VIM: ex: et ts=4 enc=utf-8 :
?>
