<?php

abstract class UserBean {
	/**
	 * from table: utenti
	 */
	protected $uid;
	protected $firstName;
	protected $lastName;
	protected $username;
	protected $groups = array();
	protected $perms;
	protected $cf;
	protected $sex;
	protected $pwd;
	protected $accesses;
	/**
	 * for communication module
	 * uniq ID from table rb_com_utenti
	 */
	protected $uniqID;
	/**
	 * from table: profili
	 */
	protected $profile;
    /**
     * token for authentication from mobile devices
     * @var $token string
     */
    protected $token;

	public function __construct($u, $fn, $ln, $gr, $pr, $un){
		$this->uid = $u;
		$this->firstName = $fn;
		$this->lastName = $ln;
		$this->groups = $gr;
		$this->perms = $pr;
		$this->username = $un;
		$this->profile = array();
	}
	
	public function getGroups(){
		return $this->groups;
	}

	public function setFirstName($fn){
		$this->firstName = $fn;
	}

	public function getFirstName(){
		return $this->firstName;
	}

	public function setLastName($ln){
		$this->lastName = $ln;
	}

	public function getLastName(){
		return $this->lastName;
	}

	/**
	 * @param mixed $accesses
	 */
	public function setAccesses($accesses) {
		$this->accesses = $accesses;
	}

	/**
	 * @return mixed
	 */
	public function getAccesses() {
		return $this->accesses;
	}
	
	/**
	 * 
	 * @param number $order: the order of printing
	 * @param number $full: unused (@see ParentBean::getFullName)
	 * @return string
	 */
	public function getFullName($order = 1, $full = 0){
		($order == 1) ? ($ret = $this->firstName." ".$this->lastName) : ($ret = $this->lastName." ".$this->firstName);
		return $ret;
	}

	/**
	 * print name's initials
	 * @param number $order: the order of printing
	 * @param number $dot: print a dot after every initial (@see ParentBean::getFullName)
	 * @return string
	 */
	public function getInitials($order = 1, $dot = 0) {
		$fn_init = $ln_init = "";
		$fname = explode(" ", $this->firstName);
		foreach ($fname as $item) {
			$fn_init .= substr($item, 0, 1);
			if ($dot) {
				$fn_init .= ".";
			}
		}
		$lname = explode(" ", $this->lastName);
		foreach ($lname as $item) {
			$ln_init .= substr($item, 0, 1);
			if ($dot) {
				$ln_init .= ".";
			}
		}
		if ($dot) {
			($order == 1) ? ($ret = $fn_init." ".$ln_init) : ($ret = $ln_init." ".$fn_init);
		}
		else {
			($order == 1) ? ($ret = $fn_init.$ln_init) : ($ret = $ln_init.$fn_init);
		}
		return $ret;
	}

	public function getPerms(){
		return $this->perms;
	}

	public function getUid($lecturer = false){
		return $this->uid;
	}

	public function getUsername(){
		return $this->username;
	}

	public function isInGroup($groupId){
		return in_array($groupId, $this->groups);
	}

	public function setBirthday($day){
		$this->profile['birthday'] = $day;
	}

	public function getBirthday(){
		if (isset($this->profile['birthday'])) {
			return $this->profile['birthday'];
		}
		else {
			return null;
		}
	}

	public function setAddress($add){
		$this->profile['address'] = $add;
	}

	public function getAddress(){
		if (isset($this->profile['address'])) {
			return $this->profile['address'];
		}
		else {
			return null;
		}
	}

	public function setPhone($n){
		$this->profile['phone'] = $n;
	}

	public function getPhone(){
		if (isset($this->profile['phone'])) {
			return $this->profile['phone'];
		}
		else {
			return null;
		}
	}

	public function setMobile($n){
		$this->profile['mobile'] = $n;
	}

	public function getMobile(){
		if (isset($this->profile['mobile'])) {
			return $this->profile['mobile'];
		}
		else {
			return null;
		}
	}

	public function setEmail($email){
		$this->profile['mail'] = $email;
	}

	public function getEmail(){
		if (isset($this->profile['email'])) {
			return $this->profile['email'];
		}
		else {
			return null;
		}
	}

	public function setWeb($web){
		$this->profile['web'] = $web;
	}

	public function getWeb(){
		if (isset($this->profile['web'])) {
			return $this->profile['web'];
		}
		else {
			return null;
		}
	}

	public function setMessenger($m){
		$this->profile['messenger'] = $m;
	}

	public function getMessenger(){
		if (isset($this->profile['messenger'])) {
			return $this->profile['messenger'];
		}
		else {
			return null;
		}
	}

	public function setBlog($b){
		$this->profile['blog'] = $b;
	}

	public function getBlog(){
		if (isset($this->profile['blog'])) {
			return $this->profile['blog'];
		}
		else {
			return null;
		}
	}
	
	public function setCf($cf){
		$this->cf = $cf;
	}
	
	public function getCf(){
		return $this->cf;
	}
	
	public function getSex(){
		return $this->sex;
	}
	
	public function setSex($s){
		$this->sex = $s;
	}
	
	public function setPwd($p){
		$this->pwd = $p;
	}
	
	public function getPwd(){
		return $this->pwd;
	}

	/**
	 *
	 * updates all profile's fields using a database record
	 */
	public function setProfile($row){
		$this->profile = $row;
	}

	public function getGroupsString(){
		return join(",", $this->groups);
	}

	public function check_perms($permissions){
		return $this->perms&$permissions;
	}

	public function isSchoolUser(){
		return ($this->check_perms(2|4|32|64|128));
	}

	public function isParent(){
		return $this->check_perms(8);
	}

	public function isTeacher(){
		return $this->check_perms(2);
	}

	public function isAdministrator(){
		return $this->check_perms(1);
	}
	
	public function isPrimarySchoolAdministrator(){
		return $this->check_perms(1|512);
	}
	
	public function isMiddleSchoolAdministrator(){
		return $this->check_perms(1|1024);
	}
	
	public function isFirstSchoolAdministrator(){
		return $this->check_perms(1|2048);
	}

	/**
	 * @param int $uniqID
	 */
	public function setUniqID($uniqID) {
		$this->uniqID = $uniqID;
	}

	/**
	 * @return int
	 */
	public function getUniqID() {
		return $this->uniqID;
	}

    /**
     * @return string
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token) {
        $this->token = $token;
    }

    /**
     * return an array of data in json string format
     * @return string
     */
    public function toJSON() {
        $json_array = [];
        $json_array['uid'] = $this->uid;
        $json_array['uniqID'] = $this->uniqID;
        $json_array['fname'] = $this->firstName;
        $json_array['lname'] = $this->lastName;
        $json_array['username'] = $this->username;
        $json_array['token'] = $this->token;
        return $json_array;
    }
}

class SchoolUserBean extends UserBean{

	/**
	 * from table: docenti
	 */
	private $subject;
	private $schoolOrder;
	/*
	 * is he/she a supply teacher?
	 */
	private $supplyTeacher;
	private $lecturer;
	/**
	 * from table: cdc
	 * multidimensional array
	 * first dimension: id_class=>array
	 * second dimension: class=>class name; coordinator=>boolean; subjects=>array of ids
	 */
	private $classes = array();

	/*
	 * primary school
	 */
	private $modules;

	/*
	 * connected accounts
	 */
	private $connectedAccounts;

	public function setSubject($subject){
		$this->subject = $subject;
	}

	public function getSubject(){
		return $this->subject;
	}

	public function getGroups(){
		return $this->groups;
	}

	public function setClasses($classes){
		$this->classes = $classes;
	}

	public function getClasses(){
		return $this->classes;
	}

	public function isCoordinator($id_classe){
		return $this->classes[$id_classe]['coordinatore'] || $this->classes[$id_classe]['segretario'];
	}
	
	public function getSchoolOrder(){
		return $this->schoolOrder;
	}
	
	public function setSchoolOrder($s){
		$this->schoolOrder = $s;	
	}
	
	public function isTeacherInClass($cls){
		$myclass = $this->classes[$cls];
		if (($myclass['coordinatore'] == 1 || $myclass['segretario'] == 1) && (count($myclass['materie']) < 1)) {
			return false;
		}
		return true;
	}

	public function setSubstitution($lecturer) {
		$this->supplyTeacher = true;
		$this->lecturer = $lecturer;
	}

	public function isSupplyTeacher(){
		return $this->supplyTeacher;
	}

	public function setSupplyTeacher($st){
		$this->supplyTeacher = $st;
	}

	public function getUid($lecturer = false){
		if ($this->isSupplyTeacher() && $lecturer) {
			return $this->lecturer;
		}
		else {
			return $this->uid;
		}
	}

	/**
	 * @return mixed
	 */
	public function getLecturer(){
		return $this->lecturer;
	}

	/**
	 * @param mixed $modules
	 */
	public function setModules($modules) {
		$this->modules = $modules;
	}

	/**
	 * @return mixed
	 */
	public function getModules() {
		return $this->modules;
	}

	public function getConnectedAccounts() {
		return $this->connectedAccounts;
	}

	public function setConnectedAccounts($ca) {
		$this->connectedAccounts = $ca;
	}

	public function hasConnectedAccounts() {
		return count($this->connectedAccounts) > 0;
	}

}

class StudentBean extends UserBean {
	
	private $class;
	private $classDescription;
	private $schoolOrder;
	private $birthPlace;
	private $active;
	
	public function setClass($cl){
		$this->class = $cl;
	}
	
	public function getClass(){
		return $this->class;
	}
	
	public function setClassDescription($cl){
		$this->classDescription = $cl;
	}
	
	public function getClassDescritption(){
		return $this->classDescription;
	}
	
	public function getSchoolOrder(){
		return $this->schoolOrder;
	}
	
	public function setSchoolOrder($s){
		$this->schoolOrder = $s;
	}
	
	public function getBirthPlace(){
		return $this->birthPlace;
	}
	
	public function setBirthPlace($bp){
		$this->birthPlace = $bp;
	}

	public function setActive ($active) {
		$this->active = $active;
	}

	public function isActive() {
		return $this->active;
	}

	/**
	 * if $full print class description
	 * @see UserBean::getFullName()
	 */
	public function getFullName($order = 1, $full = 0){
		if (!$full){
			return parent::getFullName($order);
		}
		$n = parent::getFullName($order);
		$n .= " (" . $this->classDescription .")";
		return $n;
	}
	
	public function isTeacher(){
		return false;
	}

	public function isAdministrator(){
		return false;
	}

    public function toJSON() {
        $ar = parent::toJSON();
        $ar['school'] = $this->schoolOrder;
        $ar['classID'] = $this->class;
        $ar['classDesc'] = $this->classDescription;
        $ar['area'] = 'student';
        return json_encode($ar);
    }


}

class ParentBean extends UserBean {
	private $children;
	private $childrenNames;
	private $classesRepresented;
	private $schoolOrder;
	private $classes = [];

	public function __construct($u, $fn, $ln, $gr, $pr, $un) {
		parent::__construct($u, $fn, $ln, $gr, $pr, $un);
		$this->classesRepresented = [];
	}

	public function setChildren($c){
		$this->children = $c;
	}
	
	public function getChildren(){
		return $this->children;
	}
	
	public function setChildrenNames($cn){
		$this->childrenNames = $cn;
	}
	
	public function getChildrenNames(){
		return $this->childrenNames;
	}
	
	public function isTeacher(){
		return false;
	}
	
	public function isAdministrator(){
		return false;
	}
	
	/**
	 * if $full print also children names
	 * @see UserBean::getFullName()
	 */
	public function getFullName($order = 1, $full = 0){
		if (!$full){
			return parent::getFullName($order);
		}
		$n = parent::getFullName($order);
		$n .= " (" . implode(", ", $this->childrenNames) .")";
		return $n;
	}

	public function getSchoolOrder() {
		return $this->schoolOrder;
	}

	/**
	 * @param mixed $schoolOrder
	 */
	public function setSchoolOrder($schoolOrder) {
		$this->schoolOrder = $schoolOrder;
	}

	/**
	 * @return array
	 */
	public function getClasses() {
		return $this->classes;
	}

	/**
	 * @param array $classes
	 */
	public function setClasses($classes) {
		$this->classes = $classes;
	}

	public function addRepresentedClass($cls) {
		$this->classesRepresented[] = $cls;
	}

	public function getRepresentedClasses() {
		return $this->classesRepresented;
	}

	public function isClassRepresentative($cls) {
		return in_array($cls, $this->classesRepresented);
	}
}
