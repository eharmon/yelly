<?php

$operation = $_REQUEST['ajax'];

switch($operation) {
	case 'read':
		$item_id = $_POST['id'];
		// Currently a huge security hole! Fix later!
		$sql = "UPDATE lylina_items
			   SET viewed = 1
			 WHERE     id = $item_id";
		$result = runSQL($sql);
		break;
	case 'update':
		$conf['debug'] = 'false';
		require(find_op("fetch"));
		$newest = $_REQUEST['newest'];
		// Also stupid and should be fixed
		$sql = "SELECT COUNT(*)
                          FROM lylina_items
                         WHERE id > $newest
                           AND UNIX_TIMESTAMP(dt) > UNIX_TIMESTAMP()-(8*60*60)";
		$result = runSQL($sql);
		echo $result[0]["COUNT(*)"];
		break;
	case 'items':
		require_once('inc/display.php');
		require_once('inc/itemDisplay.inc.php');
//		echo '<div id="new">Get new items</div>';
		printItems(0, 8);
		break;
}

?>
