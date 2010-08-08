<?php
	include "../inc/tehbd.php";
//	$link = mysql_connect('127.0.0.1', $sUsr, $sPsswrd);
	$conx = conPDO();
//	$db = mysql_select_db($sDbName, $link);
if(isset($_POST['id']))
{
	date_default_timezone_set('Europe/Paris');
	$currentDate = date('Y-m-d H:i:s',time());
	$sQuery = "INSERT INTO  `chat` (  `user` ,  `id` ,  `post` ,  `date` ) VALUES ('". $_POST['user'] . "', '" . $_POST['id'] . "', '" . strip_tags(addslashes($_POST['post'])) . "', '" . $currentDate . "')";
//	echo $sQuery;
	$result = $conx->query($sQuery);
	if(!$result){
		$mes_erreurs = $idcom->errorInfo();
		echo "Lecture impossible, code", $idcom->errorCode(), $mes_erreurs[2];
	}

//	$result->closeCursor();
//	$conx = null;

}
else{
	?>
<?php if(isset($_GET['html']))
	{

		$sQuery = "SELECT * FROM (SELECT * FROM `chat` ORDER BY `index` DESC LIMIT 30) AS pouet ORDER BY `index` ASC;";
		$result = $conx->query($sQuery);
		if(!$result){
			$mes_erreurs = $conx->errorInfo();
			echo "Lecture impossible, code", $conx->errorCode(), $mes_erreurs[2];
		}
		else
		{
			while($row = $result->fetchObject()){
	?>
		<div class="padding" id="chat<?php echo $row->index;?>">
			<p class="user">
				<a class="pseudo"><?php echo $row->user;?></a>
				<?php //echo $row["id"];?>
			<span class="date">à : <?php echo $row->date;?></span>
			</p>
			<p class="post"><?php echo $row->post = preg_replace("@[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]@","<a href=\"\\0\">\\0</a>", stripslashes($row->post));?></p>

		</div>
		<?php
			}
		}
		$result->closeCursor();
		$conx = null;
	}
	elseif(isset($_GET['lastid']))
	{
//		echo $_GET['lastid'];
		$sQuery = "SELECT * FROM `chat` WHERE `index` > '".$_GET['lastid']."' ORDER BY `index`;";
		$result = $conx->query($sQuery);
		if(!$result){
			$mes_erreurs = $conx->errorInfo();
			echo "Lecture impossible, code", $conx->errorCode(), $mes_erreurs[2];
		}
		else
		{
			?>[<?php
				$index = 0;
				while($row = $result->fetchAll())
				{
					$index++;
					echo "{
";
					$indexCol = 0;
					foreach($row[0] as $key => $val)
					{
						echo "'" . $key . "'";
						echo ':';
						echo "'" . $val . "'";
						if(end($row) != $val)
						{
							echo ',
';
						}
						$indexCol++;
					}
					echo "}
";
					if($result->rowCount() > $index)
					{
						echo ',';
					}
				}
				?>]<?php

		}
		$result->closeCursor();
		$conx = null;

	}
	else{
echo '....';
	}?>
<?php } ?>
