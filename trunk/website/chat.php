<?php

$link = mysql_connect("127.0.0.1", "root", "");
$db = mysql_select_db('blacklodge', $link);
if(isset($_POST['id']))
{
	?>!!!<?php
	$sQuery = "INSERT INTO  `chat` (  `user` ,  `id` ,  `post` ,  `date` ) VALUES ('". $_POST['user'] . "', '" . $_POST['id'] . "', '" . $_POST['post'] . "', CURRENT_TIMESTAMP)";
	mysql_query($sQuery);
}
else{
	$sQuery = mysql_query("SELECT * FROM  `chat` LIMIT 0, 30");

	while($row = mysql_fetch_assoc($sQuery)){
	?>
	<div class="">
		<?php echo $row["user"];?>
		<?php echo $row["id"];?>
		<p><?php echo $row["post"];?></p>
		<?php echo $row["date"];?>
		<hr/>
	</div>
	<?php
	}
}
mysql_close($link);

?>