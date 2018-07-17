<?php
error_reporting(0);
if (!isset($_GET['c'])) {
	die();
}
$c = (int)$_GET['c'];
$m = (!isset($_GET['m']) || $_GET['m'] < 0 || $_GET['m'] > 3) ? 0 : (int)$_GET['m'];
require_once("../../osu-score/include.php");
require_once("../../osu-score/web/include.db.php");
setGameMode($m);
function getusername($userid) {
	global $conn;
	return $conn->queryOne("SELECT username FROM osu_users WHERE user_id = {$userid} LIMIT 1");
}

list($beatmapid,$mods,$count50,$count100,$count300,$countkatu,$countgeki,$countmiss,$maxcombo,$perfect,$userid,$score,$rank,$date)=$conn->queryRow("SELECT beatmap_id,enabled_mods,count50,count100,count300,countkatu,countgeki,countmiss,maxcombo,perfect,user_id,score,rank,date FROM {$highScoreTable} WHERE score_id = {$c} LIMIT 1",true);
if (!isset($beatmapid)) {
	die();
}
$username=getusername($userid);
require_once('../../osu-score/web/include.cache.php');
getbeatmapinfo("m={$m}&b={$beatmapid}","mode = {$m} AND beatmap_id = {$beatmapid}");
$filename="../../osu-score/replays/$userid/replay-{$m}_{$c}.osr";
if (file_exists($filename)) {
	require_once('include.replay.php');
	header("content-disposition:attachment;filename={$c}.osr");
	header("content-type:zip");
	echo packreplay($m,$username,$fileChecksum,$mods,$score,$maxcombo,$perfect,$rank,$countmiss,$count50,$count100,$count300,$countkatu,$countgeki,file_get_contents($filename),strtotime($date));
}
?>
