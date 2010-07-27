<?php

	include "inc/tehbd.php";
	$link = mysql_connect('127.0.0.1', $sUsr, $sPsswrd);
	$db = mysql_select_db($sDbName, $link);
//	$link = mysql_connect("127.0.0.1", "root", "");
//	$db = mysql_select_db('blacklodge', $link);
if(isset($_POST['id']))
{
	$sQuery = "INSERT INTO  `chat` (  `user` ,  `id` ,  `post` ,  `date` ) VALUES ('". $_POST['user'] . "', '" . $_POST['id'] . "', '" . strip_tags(addslashes($_POST['post'])) . "', CURRENT_TIMESTAMP)";
	mysql_query($sQuery);
}
else{
	$sQuery = mysql_query("SELECT * FROM `chat` WHERE `index` > ".$_GET['lastid']." ORDER BY `index`");

	?>
<?php if(isset($_GET['html']))
	{
		while($row = mysql_fetch_assoc($sQuery)){
?>
	<div class="padding" id="chat<?php echo $row["index"];?>">
		<p class="user">
			<?php echo $row["user"];?>
			<?php //echo $row["id"];?>
		</p>
		<p class="post"><?php echo $row["post"];?></p>
		<p class="date">à : <?php echo $row["date"];?></p>
	</div>
	<?php
		}
	}
	else
	{
//		header('Content-type: text/javascript');
		?>[<?php
//			echo mysql_fetch_assoc($sQuery);
			$index = 0;
			while($row = mysql_fetch_assoc($sQuery))
			{
				$index++;
				echo json_encode($row);
				if(mysql_num_rows($sQuery) > $index)
				{
					echo ',';
				}
			}?>]<?php

		 } ?>
<?php } ?>
<?php mysql_close($link); ?>