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
	if( $createUser->create($email, $pseudo, hash('sha256',$mdp), $desc, $birth, $parent))
	{
		$createUser = new User();
		if($createUser->loginCookies($email, $mdp))
		{
			echo "[{'statut':'login','msg':true}]";
		}
		else
		{
			echo "[{'statut':'login','msg':false}]";
		}

	}
	else{
		header("HTTP/1.0 403 Forbidden");
		echo "[{'statut':'error','msg':'Impossible de créer l'utilisateur'}]";
	}
}

if(isset($_POST['ctn']))
{
//	echo "ok";

	$email = isset($_POST['email'])    ? $_POST['email']   : null;
	$pseudo = isset($_POST['pseudo'])  ? $_POST['pseudo']  : null;
	$mdp = isset($_POST['mdp'])        ? $_POST['mdp']     : null;
	$desc = isset($_POST['desc'])      ? $_POST['desc']    : null;
	$birth = isset($_POST['birth'])    ? $_POST['birth']   : null;
	$parent = isset($_POST['parent'])  ? $_POST['parent']  : null;

	if($_POST['ctn'] == 'crt')
	{
		create_user($email, $pseudo, $mdp, $desc, $birth, $parent);
	}
	if($_POST['ctn'] == 'lgn')
	{
		$createUser = new User();
		if($createUser->loginTest($email, $mdp))
		{
			if($createUser->loginCookies($email, $mdp))
			{

			}
		}
		else
		{
			header("HTTP/1.0 403 Forbidden");
			echo "[{'statut':'error','msg':'pas de requête'}]";
		}
	}

}
else{
	header("HTTP/1.0 403 Forbidden");
	echo "[{'statut':'error','msg':'pas de requête'}]";
}
?>