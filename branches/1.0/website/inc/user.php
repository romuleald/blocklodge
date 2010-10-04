<?php
/**
 * Created by IntelliJ IDEA.
 * User: romu
 * Date: 14 sept. 2010
 * Time: 23:32:49
 * To change this template use File | Settings | File Templates.
 * @class User
 */

session_start();

require_once "../inc/tehbd.php";
class User {
	var $uid;
	var $pseudo;
	var $email;
	var $avatar;
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

		$result = $conx->prepare("SELECT `email`, `mdp`, `id`, `user`, `avatar` FROM `user` WHERE `email` = '".$email."' AND `mdp` = '".$mdp."';");
		$result->execute();

//		$sQuery = "SELECT `email`, `mdp`, `id`, `user`, `avatar` FROM `user` WHERE `email` = '".$email."' AND `mdp` = '".$mdp."';";
//		echo $sQuery;
//		$result = $conx->query($sQuery);
		if($result->rowCount() == 0){
			//			$mes_erreurs = $conx->errorInfo();
			header("HTTP/1.0 403 Forbidden");
			return false;
		}
		else{
			$result = $result->fetch(PDO::FETCH_OBJ);
			$this->setUser(
				$result->id,
				$result->email,
				$result->user,
				$result->avatar
			);
			return true;
		}

	}
	/**
	 * @return bool
	 */
	function loginSetCookies(){
		// si le cookies est écrit, return true, sinon false
		// pour securiser l'auth du cookies, on récupère l'uid + l'ip + 'bl' et on le hash
		// ensuite on le stocke dans une table d'authentification qui aura la colone "hash" et la colone "expire"

		//on insere la personne en SQL
		$conx = conPDO();

		$uid = $this->uid;
		$ip = $_SERVER["REMOTE_ADDR"];
		$hash = hash('sha256', $uid . $ip . "bl");
		$expire = time()+60*60*24*30;

//		echo $hash, $expire;

		$sQuery = "INSERT INTO  `auth` (  `hash`, `expire`, `id`, `ip` ) VALUES ('". $hash . "', '" . $expire . "', '" . $uid . "', '" . $ip . "')  ON DUPLICATE KEY UPDATE expire='" . $expire . "'";
		$result = $conx->query($sQuery);
		if(!$result){
			header("HTTP/1.0 403 Forbidden");
			return false;
		}
		else{
			//on set le cookie si c'est bon
			setcookie("auth", $hash, $expire, "/");
			$this->setAuth($hash);
			return true;
		}


	}
	function loginGetCookies(){
		// si le cookies est écrit, return true, sinon false
		// pour securiser l'auth du cookies, on récupère l'email + time et on le hash
		// ensuite on le stocke dans une table d'authentification qui aura la colone "hash" et la colone "expire"

		if(isset($_COOKIE["auth"])){
			$hash = $_COOKIE["auth"];

			//on insere la personne en SQL
			$conx = conPDO();
			$sQuery = "SELECT `hash` FROM `auth` WHERE `hash` = '".$hash."';";

			$result = $conx->query($sQuery);
			if($result->rowCount() == 1){
				$this->setAuth($hash);
				return true;
			}
			else{
				return false;
			}

		}
		else{
			return false;
		}

	}
	/**
	 * @param  $hash
	 * @return void
	 */
	function setAuth($hash){
		$_SESSION['auth'] = $hash;
	}
	/**
	 * @param  $uid
	 * @param  $email
	 * @param  $pseudo
	 * @param  $avatar
	 * @return void
	 */
	function setUser($uid, $email, $pseudo, $avatar){

		$_SESSION['uid'] = $uid;
		$_SESSION['email'] = $email;
		$_SESSION['pseudo'] = $pseudo;
		$_SESSION['avatar'] = $avatar;

	}

	/**
	 * @param  $id
	 * @return void
	 */
	function logout($id){
		// TODO: Supprime la connexion d'un utilisateur
		session_unset();
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
