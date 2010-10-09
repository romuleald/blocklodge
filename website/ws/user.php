<?php
//header('Content-type: text/html; charset=utf-8');
include "../inc/user.php";

/**
 * @param  $email
 * @param  $pseudo
 * @param  $mdp
 * @param  $desc
 * @param  $birth
 * @return
 */
function create_user($email, $pseudo, $mdp, $desc, $birth, $parent)
{
//	print_r(func_get_args());

	$createUser = new User();
	if( $createUser->create($email, $pseudo, $mdp, $desc, $birth, $parent))
	{
		$createUser = new User();
		if($createUser->loginSetCookies($email, $mdp))
		{
			echo '[{"statut":"login","msg":true}]';
		}
		else
		{
			echo '[{"statut":"login","msg":false}]';
		}

	}
}
//print_r($_POST);
if(isset($_POST['ctn']))
{

	$email =  (isset($_POST['email'])  && !empty($_POST['email']))    ? $_POST['email']   : null;
	$pseudo = (isset($_POST['pseudo']) && !empty($_POST['pseudo']))   ? $_POST['pseudo']  : null;
	$mdp =    (isset($_POST['mdp'])    && !empty($_POST['mdp']))      ? hash('sha256',$_POST['mdp']) : null;
	$desc =   (isset($_POST['desc'])   && !empty($_POST['desc']))     ? $_POST['desc']    : null;
	$birth =  (isset($_POST['birth'])  && !empty($_POST['birth']))    ? $_POST['birth']   : null;
	$parent = (isset($_POST['parent']) && !empty($_POST['parent']))   ? $_POST['parent']  : null;

	if($_POST['ctn'] == 'crt')
	{
		create_user($email, $pseudo, $mdp, $desc, $birth, $parent);
	}
	if($_POST['ctn'] == 'lgn')
	{
		$login = new User();
		if($login->loginTest($email, $mdp))
		{
			if($login->loginSetCookies($email, $mdp))
			{
//				session_start();
				echo '[{"statut":"login","msg":true,"user":{"pseudo":"'.$_SESSION["pseudo"].'","email":"'.$_SESSION["email"].'","avatar":"'.$_SESSION["avatar"].'","uid":"'.$_SESSION["uid"].'"}}]';
			}
			else
			{
				echo '[{"statut":"login","msg":false}]';
			}
		}
		else
		{
			header("HTTP/1.0 403 Forbidden");
			echo '[{"statut":"error","msg":"email ou mot de passe non reconnu"}]';
		}
	}

	if($_POST['ctn'] == 'cks')
	{
		$getCookies = new User();
		if($getCookies->loginGetCookies())
		{
			echo '[{"statut":"cookies","msg":true,"user":{"pseudo":"'.$_SESSION["pseudo"].'","email":"'.$_SESSION["email"].'","avatar":"'.$_SESSION["avatar"].'","uid":"'.$_SESSION["uid"].'"}}]';
		}
		else{
			echo '[{"statut":"cookies","msg":false}]';
		}
	}
}
else{
	header("HTTP/1.0 403 Forbidden");
	echo '[{"statut":"error","msg":"pas de requête"}]';
}
?>