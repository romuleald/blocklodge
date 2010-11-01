<?php
session_start();
if(!isset($_SESSION['auth']))
{
	echo '[{"statut":"error","msg":"need login"}]';
}
elseif(!isset($_COOKIE['auth']))
{
	echo '[{"statut":"error","msg":"need login"}]';
}
elseif(isset($_SESSION['auth']) != $_COOKIE["auth"])
{
	echo '[{"statut":"error","msg":"need login"},{' . $_SESSION['auth'] . ':' . $_COOKIE["auth"] . '}]';
}
elseif(empty($_SESSION["pseudo"]))
{
	echo '[{"statut":"error","msg":"need login, no pseudo"}';
}
else{


	include "../inc/tehbd.php";
	//	$link = mysql_connect('127.0.0.1', $sUsr, $sPsswrd);
	//	$db = mysql_select_db($sDbName, $link);

	//TODO: faire des fonctions pour que lorsque l'on fasse un post, soit renvoyé directement les nouveaux messages (éviter la double requête HTTP)

	error_reporting(0);
	set_time_limit(60);
	function chatGetJson($lastId)
	{

		$conx = conPDO();
		$sQuery = "SELECT `index` FROM `chat` WHERE `index` > '".$lastId."';";
		$result = $conx->query($sQuery);
		if(!$result){
			echo "[{'error':'Error SQL'}]";
		}
		elseif($result->rowCount() == 0){
			echo "[{'newMsg':false}]";
			//		sleep(1);
			//		return chatGetJson($lastId);
		}
		else
		{
			$sQuery = "SELECT * FROM `chat` WHERE `index` > '".$lastId."' ORDER BY `index`;";
			$result = $conx->query($sQuery);
			?>[<?php
				$index = 0;
			while($row = $result->fetchObject())
			{
				$index++;
				echo "{";
				$indexCol = 0;
				foreach($row as $key => $val)
				{
					$val = str_replace(array("\r", "\r\n", "\n"), ' ', $val);
					echo "'" . $key . "'";
					echo ':';
					echo "'" . $val . "'";
					if(end($row) != $val)
					{
						echo ',';
					}
					$indexCol++;
				}
				echo "}";
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

	function chatGetHTML()
	{
		//todo: change the HTML version to JSON of the all HTML (all WS must be JSON)
		$conx = conPDO();
		$sQuery = "SELECT * FROM (SELECT * FROM `chat` ORDER BY `index` DESC LIMIT 30) AS pouet ORDER BY `index` ASC;";
		$result = $conx->query($sQuery);
		if(!$result){
			$mes_erreurs = $conx->errorInfo();
			echo "Lecture impossible, code", $conx->errorCode(), $mes_erreurs[2];
		}
		else
		{
			// if blank, any space will provoc bold
			$bIsNamed = "untrucimpossiblearefairedansunpseudo654984-('";
			if(isset($_SESSION["pseudo"]))
			{
				$bIsNamed = $_SESSION["pseudo"];
			}

			while($row = $result->fetchObject()){
				?>  <div class="padding" id="chat<?php echo $row->index;?>">
					<p class="user">
						<a class="pseudo"><?php echo $row->user;?></a>
					<?php //echo $row["id"];?>
						<span class="date">à : <?php echo $row->date;?></span>
					</p>
					<p class="post<?php echo preg_match("/\b$bIsNamed\b/i", $row->post) ? ' bold' : ''; ?>"><?php echo $row->post = preg_replace("@[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]@","<a href=\"\\0\" onclick=\"window.open(this.href);return false;\">\\0</a>", stripslashes($row->post));?></p>

				</div><?php
			}
		}
		$result->closeCursor();
		$conx = null;
	}

	function chatPost()
	{
		$conx = conPDO();
		date_default_timezone_set('Europe/Paris');
		$currentDate = date('Y-m-d H:i:s',time());

		$post = htmlspecialchars(addslashes($_POST['post']));
		//	$post = str_replace(array("\r", "\r\n", "\n"), " ", $post);
		//	$post = str_replace("<br /><br /><br />", "<br />", $post);
		//	$post = preg_replace("/<br \/><br \/><br \/>/", "<br /><br />", $post);

		$sQuery = "INSERT INTO  `chat` (  `user` ,  `id` ,  `post` ,  `date` ) VALUES ('". $_POST['user'] . "', '" . $_POST['id'] . "', '" . $post . "', '" . $currentDate . "')";
		//	echo $sQuery;
		$result = $conx->query($sQuery);
		if(!$result){
			$mes_erreurs = $result->errorInfo();
			echo "Lecture impossible, code", $result->errorCode(), $mes_erreurs[2];
		}
		$result->closeCursor();
		$conx = null;

	}



	if(isset($_POST['id']))
	{
		chatPost();
	}
	else{

		if(isset($_GET['html']))
		{
			chatGetHTML();
		}
		elseif(isset($_GET['lastid']))
		{
			chatGetJson($_GET['lastid']);
		}
		else{
			echo "[{'newMsg':false}]";
		}
	}
}
?>
