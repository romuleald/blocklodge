<?php
	include "../inc/tehbd.php";
	$link = mysql_connect('db2452.1and1.fr', $sUsr, $sPsswrd);
	$db = mysql_select_db($sDbName, $link);
//	$link = mysql_connect("127.0.0.1", "root", "");
//	$db = mysql_select_db('blacklodge', $link);
if(isset($_POST['id']))
{
	date_default_timezone_set('Europe/Paris');
	$currentDate = date('Y-m-d H:i:s',time());
	$sQuery = "INSERT INTO  `user` (  `user` ,  `id` ,  `mdp` ,  `join`,  ) VALUES ('". strip_tags(addslashes($_POST['user'])) . "', '" . $_POST['id'] . "', '" . strip_tags(addslashes($_POST['post'])) . "', '" . $currentDate . "')";
//	echo $sQuery;
		mysql_query($sQuery);
}
?>