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

		$uid = $_SESSION["uid"];
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
			$result = $conx->prepare("SELECT `hash`, `expire`, `id` FROM `auth` WHERE `hash` = '".$hash."';");
			$result->execute();
			$result = $result->fetch(PDO::FETCH_OBJ);

//			echo "/* ".count($result)." */";

			if(is_object($result) && count($result) == 1){

				// auth ok
				if($result->expire<time()){
					// auth expire
					return false;
				}

				/* dirty trick */
				if(!isset($_SESSION["pseudo"])){
					$this->setUser('','','','');

				}
				if($_SESSION["pseudo"]== ""){
//					echo "/* si pas de session */ ";
					$uid = $result->id;
					$result2 = $conx->prepare("SELECT `email`, `mdp`, `id`, `user`, `avatar` FROM `user` WHERE `id` = '".$uid."';");
					$result2->execute();
					$result2 = $result2->fetch(PDO::FETCH_OBJ);
					
					$this->setUser(
						$result2->id,
						$result2->email,
						$result2->user,
						$result2->avatar
					);

				}
				$this->setAuth($hash);
//				echo "/* 2 */";
				return true;
			}
			else{
//				echo "/* 3 */";
				// no auth
				return false;
			}

		}
		else{
//			echo "/* 4 */";
			// no cookies
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
//		echo "/* ".session_id()." */";
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
	 * delete all user's sessions from anothers computers 
	 * @param  $uid
	 * @return void
	 */
	function killMyOtherSession($uid){
		$conx = conPDO();

		$uid = $this->uid;

//		echo $hash, $expire;

		$sQuery = "DELETE FROM  `auth` WHERE `id` = ".$uid." && `hash` != ".$_COOKIE['auth'];
		$result = $conx->query($sQuery);
		if(!$result){
			return false;
		}
		else{
			//on set le cookie si c'est bon
			return true;
		}

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
	function modify($email, $pseudo, $birth, $avatar, $desc){
		// TODO: modifier un utilisateur

		$conx = conPDO();
		$result = array();
		$uid = $_SESSION["uid"]; 
		$i = 0;

		//TODO: i think theres's a better way to optimize these requests
		//but for the moment it's enough, see over bindparam/value

		if(!empty($email))
		{
			$result[$i++] = $conx->prepare("UPDATE `user` SET `email` = '". $email ."' WHERE `id` = ".$uid);
		}
		if(!empty($pseudo))
		{
			$result[$i++] = $conx->prepare("UPDATE `user` SET `user` = '". $pseudo ."' WHERE `id` = ".$uid);
		}
		if(!empty($birth))
		{

			if($birth > time() - 18 * 365*24*60*60)
			{
				//error, must be 18+
				//			header("HTTP/1.0 403 Forbidden");
				//			echo '[{"statut":"error","msg":"trop jeune"}]';
				//			return false;
				$result[$i++] = $conx->prepare("UPDATE `user` SET `birth` = '". $birth ."' WHERE `id` = ".$uid);

			}
		}
		if(!empty($desc))
		{
			$result[$i++] = $conx->prepare("UPDATE `user` SET `desc` = '". $desc ."' WHERE `id` = ".$uid);
		}
/*
		if(!empty($avatar))
		{
			$result[$i++] = $conx->prepare("REPLACE INTO `user` (`avatar`) VALUES ('". $email ."') ");
		}
 */

		foreach($result as $request){
			$result = $request->execute();
			if($result){
				echo '[{"statut":"error","msg":":)"}]';
			}
			else
			{
				echo '[{"statut":"error","msg":":("}]';
			}

		}

/*		$result = $result->fetch(PDO::FETCH_OBJ);


		if(!$result){
			header("HTTP/1.0 403 Forbidden");
			echo '[{"statut":"error","msg":"email déjà existant"}]';
			return false;
		}
		else{
			return true;
		}
*/
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
