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
	 * @param avatar string
	 * @param desc string
	 * @return user created
	 * */
	function create($email, $pseudo, $mdp, $avatar, $desc){
		// TODO: créer un utilisateur
	}

	/**
	 * @param  $email
	 * @param  $mdp
	 * @return void
	 */
	function login($email, $mdp){
		// TODO: loggué un utilisateur

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
	function modifyPsw($email, $mdp){
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
