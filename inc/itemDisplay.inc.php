<?php

function printItems($UID,$hours) {
//	date_default_timezone_set("America/New_York");
//	$sql = "SELECT A.id, A.url, A.title, A.body, A.viewed, B.name, B.url as feedurl,
//			 UNIX_TIMESTAMP(A.dt) as unix_time

//	$sql = "SELECT A.id, A.url, A.title, A.body, A.viewed, B.name, B.url as feedurl,
//                       DATE_FORMAT(A.dt, '%H:%i') as time,
//                       DATE_FORMAT(A.dt, '%W %D %M %Y') as date
	$sql = "SELECT A.id, A.url, A.title, A.body, A.viewed, B.name, B.url as feedurl,
			 UNIX_TIMESTAMP(A.dt) as timestamp
                 FROM lylina_items A, lylina_feeds B, lylina_userfeeds C
                WHERE B.id = A.feed_id
                  AND B.id = C.feed_id
                  AND C.user_id = $UID
                  AND UNIX_TIMESTAMP(A.dt) > UNIX_TIMESTAMP()-($hours*60*60)
             ORDER BY A.dt DESC, A.title";
	$items = runSQL($sql);
//    foreach($items as $item){
//        formatItem($item);
//    }
	for($n = 0; $n < count($items); $n++) {
//		$items[$n]['time'] = date("H:i", $items[$n]['unix_time'] + date('Z'));
//		$items[$n]['date'] = date("l F j, Y", $items[$n]['unix_time'] + date('Z'));
		formatItem($items[$n],$n);
	}
		
}

?>
