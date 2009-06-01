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

function channelFavicon($location) {
	$empty_ico_data = base64_decode(
	'AAABAAEAEBAAAAEACABoBQAAFgAAACgAAAAQAAAAIAAAAAEACAAAAAAAQAEAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAA////AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA' .
	'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAP//AAD//wAA//8AAP//AAD//wAA//8AAP//' .
	'AAD//wAA//8AAP//AAD//wAA//8AAP//AAD//wAA//8AAP//AAA=') ;
	
//	$ico_url = getFaviconURL($location) ;
//	if(!$ico_url) {
//		return false ;
//	}
	$sql = "SELECT favicon_url FROM lylina_feeds WHERE url = '".$location."'";
	$result = runSQL($sql);

//	$cached_ico = './favicons/' . md5($ico_url) . ".ico" ;
	$cached_ico = './favicons/' . md5($result[0]['favicon_url']) . ".ico";
	$cachetime = 7 * 24 * 60 * 60; // 7 days
    	// echo "<br> $ico_url , $cached_ico " ;
	// Serve from the cache if it is younger than $cachetime
	clearstatcache(); // Clear the file stat cache in case we already recached the ico
	if (file_exists($cached_ico) && (time() - filemtime($cached_ico) < $cachetime)) {
		return $cached_ico;
	} else {
		$ico_url = getFaviconURL($location);
		if(!$ico_url) {
			return false;
		}
		if($ico_url != $result['favicon_url']) {
			$sql = "UPDATE lylina_feeds SET favicon_url = '".$ico_url."' WHERE url ='".$location."'";
			runSQL($sql);
		}
		$cached_ico = './favicons/' . md5($ico_url) . ".ico";
		if (!$data = @file_get_contents($ico_url)) $data=$empty_ico_data ;
		if (stristr($data,'html')) $data=$empty_ico_data ;
		$fp = fopen($cached_ico,'w');
		fputs($fp,$data);
		fclose($fp);
		return $cached_ico;
	}
}

function formatItem($item,$number) {
	global $conf;
	global $lang;
	global $smarty;
	static $date=0;

	date_default_timezone_set("America/New_York");

/*	if($item['date'] != $date) {
		$date = $item['date'];
		$timestamp = strtotime($date);
		$pdate = strftime($lang['DATEFORMAT'],$timestamp);
		print '<h1>'.$pdate.'</h1>';
	}*/

	if(date('j', $item['timestamp']) != date('j', $date)) {
		$date = $item['timestamp'];
		$pdate = date("l F j, Y", $date);
		print '<h1>'.$pdate.'</h1>';
	}

/*	$smarty->assign('conf', $conf);
	$smarty->assign('lang', $lang);
	$smarty->assign('iitem', md5($item['url']));
	$smarty->assign('number', $number);
	$smarty->assign('item', $item);
	$smarty->assign('channelFavicon', channelFavicon($item['feedurl']));
	$sources = array();
	$sources['digg'] = ($conf['digg'] == 'true' && !stristr(getHost($item['url']),'digg'));
	$sources['del.icio.us'] = ($conf['del.icio.us'] == 'true');
	$sources['reddit'] = ($conf['reddit'] == 'true' && !stristr(getHost($item['feedurl']),'reddit'));
	$sources['furl'] = ($conf['furl'] == 'true');
	$sources['newsvine'] = ($conf['newsvine'] == 'true');
	$smarty->assign('sources', $sources);

	$smarty->display('item.tpl');*/

	print '<div class="feed">';
//	print '<div class="item" id="IITEM-'.$item['id'].'">';
//	print '<div class="item" id="IITEM-'.md5($item['url']).'">';
	if($item['viewed'])
		print '<div class="item read" id="'.$item['id'].'">';
	else
		print '<div class="item" id="'.$item['id'].'">';
	print '<img src="'.channelFavicon($item['feedurl']).'" width="16" height="16" class="icon" alt="" />';
//	print '<span class="time">'.$item['time'].'</span> ';
	print '<span class="time">'.date('H:i',$item['timestamp']).'</span> ';
	print '<span class="title" id="TITLE'.$number.'">';
	//print htmlspecialchars($item['title']);
	print $item['title'];
	print '</span> ';
	print '<span class="source">';
	if($conf['new_window'] == 'true') 
		print '<a href="'.$item['url'].'" target="_blank">&raquo;';
	else 
		print '<a href="'.$item['url'].'">&raquo;';
//	print htmlspecialchars($item['name']);
	print $item['name'];
	print '</a>';
	print "</span>\n";
	print '<div class="excerpt" id="ICONT'.$number.'">';
//	$safehtml =& new safehtml();
//	print $safehtml->parse($item['body']);
	
	$body = $item['body'];

	if($conf['new_window'] == 'true') {
		$new_window_regex = '/<a href([^>]+)>/i';
		$new_window_replace = '<a href\\1 target="_blank">';
		$body = preg_replace($new_window_regex, $new_window_replace, $body);
	}

	print $body;
	print '<div class="integration">';

	if($conf['digg'] == 'true' && !stristr(getHost($item['url']),'digg'))
		print '<a href="http://digg.com/submit?phase=3&amp;url='.$item['url'].'&amp;title='.$item['title'].'" target="_new"><img src="img/digg.gif" alt="" /> '.$lang['digg'].'</a> ';
		
	if($conf['del.icio.us'] == 'true')
		print '<a href="http://del.icio.us/post?url='.$item['url'].'&amp;title='.$item['title'].'" target="_new"><img src="img/del.icio.us.gif" alt="" /> '.$lang['delicious'].'</a> ';
		
	if($conf['reddit'] == 'true' && !stristr(getHost($item['feedurl']),'reddit'))
		print '<a href="http://www.reddit.com/submit?url='.$item['url'].'&amp;title='.$item['title'].'" target="_new"><img src="img/reddit.png" alt="" /> '.$lang['reddit'].'</a> ';
		
	if($conf['furl'] == 'true')
		print '<a href="javascript:furlPost(\''.$item['url'].'\',\''.$item['title'].'\');"><img src="img/furl.gif" alt="" /> '.$lang['furl'].'</a> ';
		
	if($conf['newsvine'] == 'true')
		print '<a href="javascript:void(window.open(\'http://www.newsvine.com/_wine/save?u='.$item['url'].'&h='.$item['title'].'\',\'newsvine\',\'toolbar=no,width=590,height=480\'));"><img src="img/newsvine.gif" alt="" /> '.$lang['newsvine'].'</a> ';
		
	print '</div>';
	print '</div>';
	print '</div>';
	print "</div>\n";
//	flush();
}

?>
