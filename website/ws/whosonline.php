<?php
	include "../inc/tehbd.php";
	$conx = conPDO();

if(isset($_POST['id']))
{
	$chatUserName = strip_tags(htmlentities(addslashes($_POST['user'])));
	$chatUserId = $_POST['id'];
	date_default_timezone_set('Europe/Paris');
	$currentDate = date('Y-m-d H:i:s',time());
	$microtime = time();

	$sQuery = "INSERT INTO  `whosonline` (  `user` ,  `id` ,  `date` ) VALUES ('". $chatUserName . "', '" . $chatUserId . "', '" . $microtime . "')  ON DUPLICATE KEY UPDATE date='" . $microtime . "'";

	$result = $conx->query($sQuery);
	if(!$result){
		$mes_erreurs = $conx->errorInfo();
		echo "Lecture impossible, code", $conx->errorCode(), $mes_erreurs[2];
	}

	$result->closeCursor();
	$conx = null;

}
else
{
	$sQuery = "SELECT * FROM `whosonline` WHERE `date` > '" . (time()-300) . "' ORDER BY `date` DESC LIMIT 30;";
	$result = $conx->query($sQuery);
	if(!$result){
		$mes_erreurs = $conx->errorInfo();
		echo "Lecture impossible, code", $conx->errorCode(), $mes_erreurs[2];
	}
	else
	{
		while($row = $result->fetchObject())
		{
			if($row->user != "pseudo")
			{
?>
	<li class="padding">
		<p class="user">
			<a class="pseudo"><?php echo stripslashes($row->user);?></a><br/>
			<span class="date">il y a : <?php echo (time() - $row->date);?> seconde<?php echo ((time() - $row->date) >= 1) ? "s" : ""?></span>
		</p>

	</li>
<?php
			}
		}
	}
	$result->closeCursor();
	$conx = null;

}
?>