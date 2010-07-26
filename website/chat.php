<?php

	$link = mysql_connect("127.0.0.1", "root", "");
	$db = mysql_select_db('blacklodge', $link);
if(isset($_POST['id']))
{
	?>!!!<?php
	$sQuery = "INSERT INTO  `chat` (  `user` ,  `id` ,  `post` ,  `date` ) VALUES ('". $_POST['user'] . "', '" . $_POST['id'] . "', '" . strip_tags(addslashes($_POST['post'])) . "', CURRENT_TIMESTAMP)";
	mysql_query($sQuery);
}
else{
	$sQuery = mysql_query("SELECT * FROM  `chat` ORDER BY  `index`");

	while($row = mysql_fetch_assoc($sQuery)){
	?>
	<?php if(isset($_GET['html'])) { ?>
	<div class="padding" id="chat<?php echo $row["index"];?>">
		<p>
			<?php echo $row["user"];?>
			<?php echo $row["id"];?>
		</p>
		<p><?php echo $row["post"];?></p>
		<p>à : <?php echo $row["date"];?></p>
		<hr/>
	</div>
	<?php } else { header('Content-type: text/javascript') ?>
	[{
		index:<?php echo $row["index"];?>,
		user:<?php echo $row["user"];?>,
		id:<?php echo $row["id"];?>,
		post:<?php echo $row["post"];?>,
		date:<?php echo $row["date"];?>
	}]
	<?php } ?>
	<?php
	}
}
mysql_close($link);

?>