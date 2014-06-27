<?php

require_once "classes.php";
require_once "users_classes.php";
require_once "data_source.php";

class Student extends StudentBean{

	private $oldClass;
	
	public function setUid($uid){
		$this->uid = $uid;
	}
	
	public function setOldClass($oc){
		$this->oldClass = $oc;
	}
	
	public function getOldClass(){
		return $this->oldClass;
	}
}

?>