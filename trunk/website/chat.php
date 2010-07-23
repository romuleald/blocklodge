<?php

if($_SERVER['REMOTE_ADDR']== "192.168.1.100"){
	$link = mysql_connect("127.0.0.1", "root", "");
	$db = mysql_select_db('blacklodge', $link);
}
else
{


}
$link = mysql_connect("127.0.0.1", "root", "");
$db = mysql_select_db('blacklodge', $link);
if(isset($_POST['id']))
{
	?>!!!<?php
	$sQuery = "INSERT INTO  `chat` (  `user` ,  `id` ,  `post` ,  `date` ) VALUES ('". $_POST['user'] . "', '" . $_POST['id'] . "', '" . $_POST['post'] . "', CURRENT_TIMESTAMP)";
	mysql_query($sQuery);
}
else{
	$sQuery = mysql_query("SELECT * FROM  `chat` ORDER BY  `date` DESC LIMIT 0, 30");

	while($row = mysql_fetch_assoc($sQuery)){
	?>
	<div class="padding">
		<p>
			<?php echo $row["user"];?>
			<?php echo $row["id"];?>
		</p>
		<p><?php echo $row["post"];?></p>
		<p>à : <?php echo $row["date"];?></p>
		<hr/>
	</div>
	<?php
	}
}
mysql_close($link);

?>