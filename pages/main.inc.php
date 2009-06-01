<?php

// Include the functions for item display
require_once('inc/itemDisplay.inc.php');

// Create a second smarty instance so we can magically flush the header out
$header = new Smarty();
$header->assign('lang', $lang);
$header->assign('conf', $conf);
$header->assign('user', $UID);
$header->assign('rss_id', md5($UID));
$header->assign('msg', $msg);
$header->display('head.tpl');

// Flush the header
ob_flush();
sleep(1);
flush();
ob_flush();

$conf['debug'] = false;

// Run the fetch operation
require(find_op("fetch"));

$hours = $_REQUEST['hours'];
if(!is_numeric($hours)) $hours = 0;
if(!$hours) $hours = 8;

printItems($UID,$hours);

$output->assign('rss_id', md5($UID));

?>