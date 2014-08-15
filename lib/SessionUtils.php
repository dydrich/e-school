<?php

require_once "RBUtilities.php";

abstract class DataUtils {
	
	protected $connection;
}

class SessionUtils extends DataUtils {
	
	private static $instance;
	private $utility;
	
	private function __construct($conn){
		$this->connection = $conn;
		$this->utility = RBUtilities::getInstance($conn);
	}
	
	public static function getInstance($conn){
		if(empty(self::$instance)){
			self::$instance = new SessionUtils($conn);
		}
		return self::$instance;
	}
	
	public function registerCurrentClassFromUser($uid, $identifier){
		$_cls = $this->utility->loadClassFromUserID($uid);
		$_SESSION[$identifier] = $_cls;
	}
	
	public function registerCurrentClassFromClassID($cid, $identifier){
		$_cls = $this->utility->loadClassFromClassID($cid);
		$_SESSION[$identifier] = $_cls;
	}
	
	public function registerUserConfig($uid, $identifier){
		$config = $this->utility->loadUserConfig($uid);
		$_SESSION[$identifier] = $config;
	}
	
	public function registerStudentWSupport($uid, $stid, $identifier){
		$config = RBUtilities::loadStudentWSupport($uid, $stid, $this->connection);
		$_SESSION[$identifier] = $config;
	}
}
