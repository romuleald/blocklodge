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
	if(empty($email))
	{
		//error, mandatory
		header("HTTP/1.0 403 Forbidden");
		echo "[{'statut':'error','msg':'email manquant'}]";
	}
	if(empty($pseudo))
	{
		//error, mandatory
		header("HTTP/1.0 403 Forbidden");
		echo "[{'statut':'error','msg':'pseudo manquant'}]";
	}
	if(empty($mdp))
	{
		//error, mandatory
		header("HTTP/1.0 403 Forbidden");
		echo "[{'statut':'error','msg':'mot de passe oublié'}]";

	}
	if(empty($desc))
	{
		//ok
	}
	if(empty($birth))
	{
		//error, must be 18+
		header("HTTP/1.0 403 Forbidden");
		echo "[{'statut':'error','msg':'âge manquant'}]";
	}
	elseif($birth > time() - 18 * 365*24*60*60)
	{
		//error, must be 18+
		header("HTTP/1.0 403 Forbidden");
		echo "[{'statut':'error','msg':'trop jeune'}]";
	}
	if(empty($parent))
	{
		//error, mandatory
	}
	$createUser = new User();
	if( $createUser->create($email, $pseudo, hash('sha256',$mdp), $desc, $birth, $parent))
	{
		$createUser = new User();
		return $createUser->loginSend($email, $mdp);
	}
	else{
		//nothing

	}
}

if(isset($_POST['ctn']))
{
//	echo "ok";

	$email = isset($_POST['email'])    ? $_POST['email']   : "";
	$pseudo = isset($_POST['pseudo'])  ? $_POST['pseudo']  : "";
	$mdp = isset($_POST['mdp'])        ? $_POST['mdp']     : "";
	$desc = isset($_POST['desc'])      ? $_POST['desc']    : "";
	$birth = isset($_POST['birth'])    ? $_POST['birth']   : "";
	$parent = isset($_POST['parent'])  ? $_POST['parent']  : "";

	if($_POST['ctn'] == 'crt')
	{
		create_user($email, $pseudo, $mdp, $desc, $birth, $parent);
	}

}
else{
	header("HTTP/1.0 403 Forbidden");
	echo "[{'statut':'error','msg':'pas de requête'}]";
}
?>