<?php
/**
 * Created by IntelliJ IDEA.
 * User: romu
 * Date: 14 sept. 2010
 * Time: 23:32:49
 * To change this template use File | Settings | File Templates.
 * @class User
 */

require_once "../inc/tehbd.php";
class User {
	/**
	 * @param email string
	 * @param pseudo string
	 * @param mdp string
	 * @param desc string
	 * @param birth time
	 * @return boolean
	 * */
	public function create($email, $pseudo, $mdp, $desc, $birth, $parent){
		if(empty($email))
		{
			//error, mandatory
			header("HTTP/1.0 403 Forbidden");
			echo '[{"statut":"error","msg":"email manquant"}]';
			return false;
			exit;
		}
		if(empty($pseudo))
		{
			//error, mandatory
			header("HTTP/1.0 403 Forbidden");
			return false;
			echo '[{"statut":"error","msg":"pseudo manquant"}]';
			exit;
		}
		if(empty($mdp))
		{
			//error, mandatory
			header("HTTP/1.0 403 Forbidden");
			echo '[{"statut":"error","msg":"mot de passe oublié"}]';
			return false;
			exit;
		}
		if(empty($desc))
		{
			//ok
		}
		if(empty($birth))
		{
			//error, must be 18+
//			header("HTTP/1.0 403 Forbidden");
//			echo '[{"statut":"error","msg":"âge manquant"}]';
//			return false;
//			exit;
		}
		elseif($birth > time() - 18 * 365*24*60*60)
		{
			//error, must be 18+
//			header("HTTP/1.0 403 Forbidden");
//			echo '[{"statut":"error","msg":"trop jeune"}]';
//			return false;
		}
		if(empty($parent))
		{
			//error, mandatory
			//TODO lien de parente
//			return false;
//			exit;
		}
		
		$conx = conPDO();

		$sQuery = "INSERT INTO  `user` ( `user`, `email`, `mdp`, `avatar`, `birth`, `parent`) VALUES ('". $pseudo . "', '" . $email . "', '" . $mdp . "', '" . $desc . "', '" . $birth . "', '" . $parent . "')";

		$result = $conx->query($sQuery);
		if(!$result){
			header("HTTP/1.0 403 Forbidden");
			echo '[{"statut":"error","msg":"email déjà existant"}]';
			return false;
		}
		else{
			return true;
		}



	}

	/**
	 * @param  $email
	 * @param  $mdp
	 * @return boolean
	 */
	function loginTest($email, $mdp){
		// TODO: test un utilisateur en base
		if(empty($email))
		{
			//error, mandatory
			header("HTTP/1.0 403 Forbidden");
			echo '[{"statut":"error","msg":"email manquant"}]';
			return false;
			exit;
		}
		if(empty($mdp))
		{
			//error, mandatory
			header("HTTP/1.0 403 Forbidden");
			echo '[{"statut":"error","msg":"mot de passe oublié"}]';
			return false;
			exit;
		}

		$conx = conPDO();

		$sQuery = "SELECT `email`, `mdp` FROM `user` WHERE `email` = '".$email."' AND `mdp` = '".$mdp."';";
//		echo $sQuery;
//		exit;
		$result = $conx->query($sQuery);
		if(!$result){
//			$mes_erreurs = $conx->errorInfo();
			header("HTTP/1.0 403 Forbidden");
			return false;
		}
		else{
			return true;
		}

	}
	/**
	 * @param  $email
	 * @return bool
	 */
	function loginSetCookies($email){
		// TODO: ecrit le cookie
		// si le cookies est écrit, return true, sinon false
		// pour securiser l'auth du cookies, on récupère l'email + time et on le hash
		// ensuite on le stocke dans une table d'authentification qui aura la colone "hash" et la colone "expire"

		//on insere la personne en SQL
		$conx = conPDO();

		$hash = hash('sha256', $email + "bl");
		$expire = time()+60*60*24*30;

//		echo $hash, $expire;

		$sQuery = "INSERT INTO  `auth` (  `hash` ,  `expire` ) VALUES ('". $hash . "', '" . $expire . "')  ON DUPLICATE KEY UPDATE expire='" . $expire . "'";
		$result = $conx->query($sQuery);
		if(!$result){
			header("HTTP/1.0 403 Forbidden");
			return false;
		}
		else{
			//on set le cookie si c'est bon
			setcookie("auth", $hash, $expire, "/");
			return true;
		}


	}

	function loginGetCookies($hash){
		// TODO: on fait mather la table auth avec le cookies
		// si le cookies est écrit, return true, sinon false
		// pour securiser l'auth du cookies, on récupère l'email + time et on le hash
		// ensuite on le stocke dans une table d'authentification qui aura la colone "hash" et la colone "expire"

		exit;
		
		//on insere la personne en SQL
		$conx = conPDO();

		$sQuery = "INSERT INTO  `auth` (  `hash` ,  `expire` ) VALUES ('". $email . "', '" . $chatUserId . "', '" . $microtime . "')  ON DUPLICATE KEY UPDATE date='" . $microtime . "'";
		echo $sQuery;
		$result = $conx->query($sQuery);
		if(!$result){
			header("HTTP/1.0 403 Forbidden");
			return false;
		}
		else{
			//on set le cookie si c'est bon
			return setcookie("auth", '', time()+60*60*24*30, "/");
		}


	}


	/**
	 * @param  $id
	 * @return void
	 */
	function logout($id){
		// TODO: Supprime la connexion d'un utilisateur

	}
	/**
	 * @param  $id
	 * @param  $email
	 * @param  $pseudo
	 * @param  $mdp
	 * @param  $avatar
	 * @param  $desc
 	 * @return void
	 */
	function modify($id, $email, $pseudo, $mdp, $avatar, $desc){
		// TODO: modifier un utilisateur

	}
	/**
	 * @param  $email
	 * @param  $mdp
	 * @return void
	 *
	 */
	function modifyPsw($id, $email, $mdp, $newsMdp){
		// TODO: modifier un utilisateur

	}
	/**
	 * @param id int
	 * @param $time int time
	 * @return ban user
	 */
	function ban($id, $time){
		// TODO: bannir un utilisateur

	}
	/**
	 * @param id int
	 * @return unban user
	 */
	function unban($id){
		// TODO: debannir un utilisateur

	}

}
