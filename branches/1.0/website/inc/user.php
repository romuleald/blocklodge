<?php
/**
 * Created by IntelliJ IDEA.
 * User: romu
 * Date: 14 sept. 2010
 * Time: 23:32:49
 * To change this template use File | Settings | File Templates.
 * @class User
 */

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
		// TODO: creer un utilisateur
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
		
		include "../inc/tehbd.php";
		$conx = conPDO();

		date_default_timezone_set('Europe/Paris');
//		$currentDate = date('Y-m-d H:i:s',time());
		$microtime = time();

		$sQuery = "INSERT INTO  `user` ( `user`, `email`, `mdp`, `avatar`, `birth`, `parent`) VALUES ('". $pseudo . "', '" . $email . "', '" . $mdp . "', '" . $desc . "', '" . $birth . "', '" . $parent . "')";

		$result = $conx->query($sQuery);
		if(!$result){
//			$mes_erreurs = $conx->errorInfo();
			header("HTTP/1.0 403 Forbidden");
			echo "[{'statut':'error','msg':'email déjà existant'}]";
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
		
		return true;//false

	}

	function loginCookies($email, $mdp){
		// TODO: ecrit le cookie
		// si le cookies est écrit, return true, sinon false


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
