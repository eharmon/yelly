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
//require_once('inc/simplepie_cache_extras.inc');
require_once('inc/null_sanitize.inc.php');
require_once('inc/htmlpurifier/HTMLPurifier.standalone.php');
require_once('inc/htmlpurifier/standalone/HTMLPurifier/Filter/YouTube.php');

// This will prevent scripts from colliding with each other, as this system now *waits* for previous update to finish.
//ini_set("max_execution_time",600);

if($conf['debug'] == 'false')
	@error_reporting(E_ERROR | E_PARSE);

$fp = fopen('lockfile','w') or die($lang['lockdir']);
flock($fp,LOCK_EX) or die($lang['lockdir']);

/*if (!extension_loaded('mysql')){
	dl('mysql.so');
}*/

/*if(is_callable('set_time_limit'))
	set_time_limit(0);
if(is_callable('ignore_user_abort'))
	ignore_user_abort(true);*/

if($conf['debug'] == 'true') {
	print "lylina $version, debug mode on\n";
	print php_uname() . "\n\n";
}

$newItems = 0;

$config = HTMLPurifier_Config::createDefault();
$config->set('Cache', 'SerializerPath', 'cache');
$config->set('AutoFormat', 'Linkify', true);
$config->set('Filter', 'YouTube', true);
// Block pheedo tracking, it doesn't work properly on this style of news aggregator and it slows things down
$config->set('URI', 'HostBlacklist', array('www.pheedo.com'));
$purifier = new HTMLPurifier($config);

$sql   = 'SELECT * FROM lylina_feeds';
$feeds = runSQL($sql);

$feeds_parse = array();
$feed_count = 0;

//$feeds_parse['url'] = array();
//$feeds_parse['curl'] = array();
//$feeds_parse['data'] = array();
//$feeds_parse['id'] = array();
//$feeds_parse['mirror_url'] = array();

$master_curl = curl_multi_init();

//$data = new SimplePie_Cache_Extras();
$data = new SimplePie();
$data->set_cache_duration(300);
$data->set_cache_location(MAGPIE_CACHE_DIR);
$data->enable_cache(true);
$data->set_sanitize_class('SimplePie_Sanitize_Null');
$data->set_autodiscovery_level(SIMPLEPIE_LOCATOR_ALL);
//$data->set_stupidly_fast(true);
// Don't need this
$data->enable_order_by_date(false);

foreach($feeds as $feed){
	if($conf['debug'] == 'true') print 'Fetching '. $feed['url'] . " ";
//	if($conf['debug'] == 'true') flush();
	$enc = '';
//	$data = fetch_rss($feed['url']);
/*	if(file_exists("mirror/" . md5($feed['url']) . ".xml")) {
//		$data = new SimplePie_Cache_Extras("mirror/" . md5($feed['url']) . ".xml");
		$data->set_feed_url("mirror/" . md5($feed['url']) . ".xml");
//		$data->set_cache_duration(300);
		$cache_time = $data->get_cache_time_remaining();
		if($conf['debug'] == 'true') echo $cache_time . "\n";
		if($conf['debug'] == 'true') flush();
	}*/
	$mod_time = -1;
	if(file_exists("mirror/" . md5($feed['url']) . ".xml")) {
		$mod_time = @filemtime("mirror/" . md5($feed['url']) . ".xml");
		$filesize = @filesize("mirror/" . md5($feed['url']) . ".xml");
	}
	$cache_time = $mod_time - time() + 300;
//	if(!file_exists("mirror/" . md5($feed['url']) . ".xml") || ($cache_time <= 0 && rand(0, 1)) || $cache_time < -600) {
	if(!file_exists("mirror/" . md5($feed['url']) . ".xml") || $cache_time < -300) {
//	if(true) {
//	$data = new SimplePie();
//	$data->set_feed_url($feed['url']);
		if($conf['debug'] == 'true') echo $cache_time . "\n";
		if($conf['debug'] == 'true') flush();
		$feeds_parse[$feed_count] = array();
		$feeds_parse[$feed_count]['url'] = $feed['url'];
		$feeds_parse[$feed_count]['id'] = $feed['id'];
		$feeds_parse[$feed_count]['name'] = $feed['name'];
		$feeds_parse[$feed_count]['mirror_url'] = "mirror/" . md5($feed['url']) . ".xml";
		$feeds_parse[$feed_count]['curl'] = curl_init();
		curl_setopt($feeds_parse[$feed_count]['curl'], CURLOPT_URL, $feed['url']);
		curl_setopt($feeds_parse[$feed_count]['curl'], CURLOPT_RETURNTRANSFER, true);
		curl_setopt($feeds_parse[$feed_count]['curl'], CURLOPT_HEADER, false);
		curl_setopt($feeds_parse[$feed_count]['curl'], CURLOPT_TIMEOUT, 15);
		curl_setopt($feeds_parse[$feed_count]['curl'], CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($feeds_parse[$feed_count]['curl'], CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($feeds_parse[$feed_count]['curl'], CURLOPT_USERAGENT, "lylina/dev (http://lylina.sf.net)");
		//curl_setopt($feeds_parse[$feed_count]['curl'], CURLOPT_MAXREDIRS, 4);
		if($mod_time != -1) {
			curl_setopt($feeds_parse[$feed_count]['curl'], CURLOPT_TIMECONDITION, CURL_TIMECOND_IFMODSINCE);
			curl_setopt($feeds_parse[$feed_count]['curl'], CURLOPT_TIMEVALUE, $mod_time);
		}
		curl_multi_add_handle($master_curl, $feeds_parse[$feed_count]['curl']);
		$feed_count++;
	} else {
		if($conf['debug'] == 'true') echo " $cache_time - cache fresh or we are ignoring, not updating...\n";
	}
}

$running = NULL;

do {
	curl_multi_exec($master_curl, $running);
} while ($running > 0);

$urls = array();

$count = 0;

foreach($feeds_parse as &$feed) {
	$feed['data'] = curl_multi_getcontent($feed['curl']);
	// If curl errors (or times out for that matter) lets record the error and not do anything else so that we can try again next refresh
	if(curl_errno($feed['curl'])) {
		if($conf['debug'] == 'true') echo "[" . $feed['url'] . ": " . curl_error($feed['curl']) . "]\n";
	}
	// If the feed is null most likely curl detected that there were no updates needed
	elseif($feed['data'] != NULL) {
		file_put_contents($feed['mirror_url'], $feed['data']);
	}
	// If the HTTP code is 0 something is fucked, for now let's just file_get_contents it
	elseif(curl_getinfo($feed['curl'], CURLINFO_HTTP_CODE) == 0) {
		@ini_set('user_agent', 'lylina/dev (http://lylina.sf.net)');
		$feed['data'] = @file_get_contents($feed['url']);
		if($conf['debug'] == 'true') echo "fallback to file_get_contents\n";
	}
	// So if it is null lets freshen the cache and continue on
	else {
		touch($feed['mirror_url']);
	}
	$url = curl_getinfo($feed['curl'], CURLINFO_EFFECTIVE_URL);
	if($conf['debug'] == 'true') echo $url . "\n";
	$code = curl_getinfo($feed['curl'], CURLINFO_HTTP_CODE);
	if($conf['debug'] == 'true') echo $code . "\n";
	curl_multi_remove_handle($master_curl, $feed['curl']);
}

curl_multi_close($master_curl);

// Only works by reference, otherwise last item is duplicated, not sure why
foreach($feeds_parse as &$feed) {
//	mysqli_bind_param($statement, "ssi", $data->feed_url
	if($conf['debug'] == 'true') echo "\n[" . $feed['url'] . " => " . $feed['mirror_url'] . "] ";
	if($feed['data'] != NULL && filesize("mirror/" . md5($feed['url']) . ".xml") != $filesize) {
		if($conf['debug'] == 'true') echo "#";
//		$data = new SimplePie($feed['mirror_url']);
//		$data->set_cache_location(MAGPIE_CACHE_DIR);
//		$data->set_cache_duration(300);
		$data->set_feed_url($feed['mirror_url']);
		$data->init();
		// If simplepie found the real feed URL, lets use it instead, but save the old one to fallback in case the URL changes
		if($data->feed_url != $feed['mirror_url']) {
			$sql = "UPDATE lylina_feeds
				 SET url = '".$data->feed_url."',
			    fallback_url = '".$feed['url']."'
			        WHERE id = '".$feed['id']."'";
			runSQL($sql);
		}
//	$data->handle_content_type();
		if($data->get_items())
			updateFeedItems($feed,$data,$purifier);
	} else {
		if($conf['debug'] == 'true') {
			echo "X";
			if($feed['data'] == NULL)
				echo "n";
			elseif(filesize("mirror/" . md5($feed['url']) . ".xml") == $filesize)
				echo "f";
		}
		
	}
}

fclose($fp);

echo "\n";

function emptyNoPHPBug($var) {
	return empty($var);
}

function updateFeedItems($feed,$rss,$purifier){
	global $conf;
	global $newItems;
	if(emptyNoPHPBug($feed['name']) && !emptyNoPHPBug($rss->get_title())) {
		$sql = "UPDATE lylina_feeds
			   SET name = '".addslashes($rss->get_title())."'
			 WHERE   id = '".addslashes($feed['id'])."'";
        	runSQL($sql);
	}

//	$config = HTMLPurifier_Config::createDefault();
//        $config->set('Cache', 'SerializerPath', 'cache');
//        $purifier = new HTMLPurifier($config);

	$items = $rss->get_items();

	$sql = "SELECT * FROM lylina_items
		WHERE feed_id = '".$feed['id']."'
		ORDER BY id DESC
		LIMIT " . count($items);
	$recent_items = runSQL($sql);

	$statement = prepairSQL("INSERT IGNORE INTO lylina_items
					SET feed_id = ?,
					post_id = ?,
					url = ?,
					title = ?,
					body = ?,
					dt = FROM_UNIXTIME(?)");
	mysqli_stmt_bind_param($statement, "issssi", $feed_id, $post_id, $url, $title, $body, $dt);
	foreach($items as $item){
	//	print_r($item);
//		if(!$item->get_date())
//			$date = time();

//		if(!$item['summary'])
//			if($item['description'])
//				$item['summary'] = $item['description'];
//			else
//				$item['summary'] = '';

//		$temp = $purifier->purify($item->get_content());
//		preg_replace('/(<a href=".*?".*?)>(.*?<\/a>)/','\\1 target="_blank">\\2', $temp);
//		echo $temp;

/*		$sql = "SELECT * FROM lylina_items
			  WHERE feed_id = '".$feed['id']."'
			    AND post_id = '".$item->get_id()."'";
		$matching_item = runSQL($sql);*/
		
		// get_id() is slow, let's only call it once
		$item_id = $item->get_id();

		$matching_item = false;
		foreach($recent_items as $potential_match) {
//			echo $potential_match['post_id'] . " : " . $item_id . "\n";
//			echo $potential_match['title'] . " : " . $item->get_title() . "\n";
			if($potential_match['post_id'] == $item_id) {
				$matching_item = true;
				break;
			}
		}

		if($item->get_date('U'))
			$date = $item->get_date('U');// - date('Z'); // Subtract local timezone to get UTC because of STUPID
		else
			$date = time();

		if(!$matching_item) {
//			$sql = "INSERT IGNORE INTO lylina_items
//				   SET feed_id = '".$feed['id']."',
//				       post_id = '".$item_id."',
//					   url = '".addslashes($item->get_link())."',
//					 title = '".addslashes(htmlspecialchars(strip_tags($item->get_title())))."',
//					  body = '".addslashes($purifier->purify($item->get_content()))."',
//					    dt = FROM_UNIXTIME(".addslashes($date).")";
//			runSQL($sql);
			$feed_id = $feed['id'];
			$post_id = $item_id;
			$url = htmlentities2($item->get_link());
			$title = strip_tags($item->get_title());
			$body = $purifier->purify($item->get_content());
			$dt = $date;
			mysqli_stmt_execute($statement);
			if($conf['debug'] == 'true') {
				if(mysqli_stmt_affected_rows($statement))
					print "+";
				else
					print "-";
			}
			if(mysqli_stmt_affected_rows($statement))
				$newItems++;
		} else {
			if($conf['debug'] == 'true') print ".";
		}
		if($conf['debug'] == 'true') flush();
    	}
}

// From PHP.net user notes
// htmlentities() which preserves originals
function htmlentities2($html) {
	$translation_table=get_html_translation_table (HTML_ENTITIES,ENT_QUOTES);
	$translation_table[chr(38)] = '&';
	return preg_replace("/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,3};)/","&amp;" , strtr($html, $translation_table));
}

//Setup VIM: ex: et ts=4 enc=utf-8 :
?>
