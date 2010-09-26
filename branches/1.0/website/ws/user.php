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
		$createUser = new User();
		if($createUser->loginTest($email, $mdp))
		{
			if($createUser->loginSetCookies($email, $mdp))
			{
				echo '[{"statut":"login","msg":true}]';
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

}
else{
	header("HTTP/1.0 403 Forbidden");
	echo '[{"statut":"error","msg":"pas de requête"}]';
}
?>