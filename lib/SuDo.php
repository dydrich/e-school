<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 06/07/14
 * Time: 12.40
 */

namespace eschool;

require_once "Authenticator.php";


class SuDo {
	private $authenticator;

	private $datasource;

	public function __construct(\DataLoader $dl){
		$this->authenticator = new \Authenticator($dl);
		$this->datasource = $dl;
	}

	public function su($pwd){
		$user = $this->authenticator->login(3, 'admin', $pwd);
		if ($user != null){
			$temp_user = $_SESSION['__user__'];
			$_SESSION['__user__'] = $user;
			$_SESSION['__sudoer__'] = $temp_user;
			return true;
		}
		else {
			return false;
		}
	}

	public function sudo($area, $uid){
		$data = $this->getLoginData($area, $uid);
		$user = $this->authenticator->login($area, $data[0]['username'], $data[0]['password']);
		if ($user != null){
			$temp_user = $_SESSION['__user__'];
			$_SESSION['__user__'] = $user;
			$_SESSION['__sudoer__'] = $temp_user;
			return true;
		}
		else {
			return false;
		}
	}

	public function back(){
		$_SESSION['__user__'] = $_SESSION['__sudoer__'];
		unset($_SESSION['__sudoer__']);
		return true;
	}

	private function getLoginData($area, $uid){
		$table = "rb_utenti";
		$id = "uid";
		if ($area == 2){
			$table = "rb_alunni";
			$id = "id_alunno";
		}
		$data = $this->datasource->executeQuery("SELECT username, password FROM {$table} WHERE {$id} = {$uid}");
		return $data;
	}

} 
