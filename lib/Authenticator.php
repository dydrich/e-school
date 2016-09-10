<?php

require_once 'users_classes.php';
require_once 'RBUtilities.php';

class Authenticator {
	
	private $datasource;
	
	/**
	 * formatted string with data for ajax response
	 * @var string
	 */
	private $stringAjax;

	private $response;

    public static $PARENT_AREA = 1;
    public static $STUDENT_AREA = 2;
    public static $SCHOOL_AREA = 3;
	
	public function __construct(DataLoader $dl){
		$this->datasource = $dl;
		$this->response = array();
	}
	
	public function getStringAjax(){
		return $this->stringAjax;
	}

	/**
	 * @return array
	 */
	public function getResponse() {
		return $this->response;
	}
	
	public function login($area, $username, $password){
		switch ($area){
			case 3:
				return $this->schoolLogin($username, $password);
				break;
			case 1:
				return $this->parentLogin($username, $password);
				break;
			case 2:
				return $this->studentLogin($username, $password);
				break;
			default:
				throw new Exception("Valore param {$area} non valido");
				break;
		}
	}

	public function schoolLogin($nick, $pass){
		$sel_user = "SELECT rb_utenti.uid FROM rb_utenti, rb_gruppi_utente WHERE rb_utenti.uid = rb_gruppi_utente.uid AND username = '{$nick}' AND password = '".trim($pass)."' AND gid NOT IN (8) ";
		$res_utente = $this->datasource->executeCount($sel_user);
		if ($res_utente == null){
			return false;
		}

		$rb = RBUtilities::getInstance($this->datasource->getSource());
		$user = $rb->loadUserFromUid($res_utente, 'school');

		/*
		 * supplente: ancora in servizio
		 */
		if ($user->isSupplyTeacher()) {
			$max_date = $this->datasource->executeCount("SELECT MAX(data_fine_supplenza) FROM rb_supplenze WHERE id_supplente = {$user->getUid()}");
			if ($max_date < date("Y-m-d")) {
				return null;
			}
		}

		$_SESSION['__accessi__'] = $user->getAccesses() + 1;
		
		/*
		 * for checking administration area access
		*/
		if($user->isAdministrator()){
			$now = time();
			$_SESSION['__admin_authentication_timeout__'] = $now;
		}
		
		$str_groups = join (", ", $user->getGroups());
		
		$perms = ($user->getPerms()) ? $user->getPerms() : $_SESSION['__perms__'];
		if($user->isInGroup(DS_GROUP)){
			$_SESSION['__administration_group__'] = "menu_ds";
			$_SESSION['__role__'] = "Dirigente scolastico";
		}
		else if($user->isInGroup(SEG_GROUP)){
			$_SESSION['__administration_group__'] = "menu_ata";
			$_SESSION['__role__'] = "Segreteria";
		}
		else if($user->isInGroup(DSGA_GROUP)){
			$_SESSION['__administration_group__'] = "menu_dsga";
			$_SESSION['__role__'] = "DSGA";
		}
		
		$update = "UPDATE rb_utenti SET accessi = (accessi + 1), previous_access = last_access, last_access = NOW() WHERE uid = ".$res_utente;
		$upd = $this->datasource->executeUpdate($update);
		
		$this->response['group'] = "0";
		$this->response['gids'] = $user->getGroups();
		$this->response['name'] = $user->getFullName();
		$this->response['accesses'] = $_SESSION['__accessi__'];
		$this->response['perms'] = $user->getPerms();

		return $user;
	}
	
	public function parentLogin($nick, $pass){
		$sel_user = "SELECT uid FROM rb_utenti WHERE username = '{$nick}' AND password = '".trim($pass)."'";
		$res_user = $this->datasource->executeCount($sel_user);
		if ($res_user == null){
			return false;
		}

		$rb = RBUtilities::getInstance($this->datasource->getSource());
		$user = $rb->loadUserFromUid($res_user, 'parent');

		$figli = $user->getChildren();
		if(count($figli) > 0){
			$_SESSION['__figli__'] = join(",", $figli);
		}
		else{
			$_SESSION['__figli__'] = "";
		}
		$_SESSION['__parent__'] = 1;
		$_SESSION['__accessi__'] = $user->getAccesses() + 1;
		$first_access = 0;
		if($user->getAccesses() == 0) {
			$first_access = 1;
		}
		$_SESSION['__parent__'] = 1;

		$update = "UPDATE rb_utenti SET accessi = (accessi + 1), previous_access = last_access, last_access = NOW() WHERE uid = ".$res_user;
		$upd = $this->datasource->executeUpdate($update);

		$this->response['group'] = "G";
		$this->response['gids'] = $user->getGroups();
		$this->response['name'] = $user->getFullName();
		$this->response['accesses'] = $_SESSION['__accessi__'];
		$this->response['perms'] = $user->getPerms();

		return $user;
	}
	
	public function studentLogin($nick, $pass){
		$sel_user = "SELECT id_alunno FROM rb_alunni WHERE username = '{$nick}' AND password = '".trim($pass)."' AND attivo = '1'";
		$res_user = $this->datasource->executeCount($sel_user);
		if($res_user == null){
			return false;
		}

		$rb = RBUtilities::getInstance($this->datasource->getSource());
		$user = $rb->loadUserFromUid($res_user, 'student');

		$_SESSION['__nick__'] = $nick;
		$_SESSION['__accessi__'] = $user->getAccesses() + 1;
		$_SESSION['__perms__'] = 256;
		if($user->getAccesses() == 0){
			$first_access = 1;
		}
		$_SESSION['__student__'] = 1;
		
		$update = "UPDATE rb_alunni SET accessi = (accessi + 1) WHERE id_alunno = ".$res_user;
		$upd = $this->datasource->executeUpdate($update);

		$this->stringAjax = "S;".$user->getUsername().";".$res_user.";".$user->getFirstName().";".$user->getLastName().";".$nick.";".$_SESSION['__accessi__'].";".$first_access;

		return $user;
	}

	public function loginWithToken($token, $area) {
	    $table = 'rb_utenti';
        $field = 'uid';
        if ($area == self::$STUDENT_AREA) {
            $table = 'rb_alunni';
            $field = 'id_alunno';
            $rbArea = 'student';
        }
        $sel_user = "SELECT $field FROM $table WHERE token = '{$token}'";
        $uid = $this->datasource->executeCount($sel_user);
        if ($uid == null || $uid == false) {
            return false;
        }
        $rb = RBUtilities::getInstance($this->datasource->getSource());
        $user = $rb->loadUserFromUid($uid, $rbArea);

        if ($area == self::$STUDENT_AREA) {
            $smt = $this->datasource->prepare("UPDATE rb_alunni SET accessi = (accessi + 1) WHERE id_alunno = ?");
        }
        else {
            // TODO: update last_mobile_access field (must be added on table)
            $smt = $this->datasource->prepare("UPDATE rb_utenti SET accessi = (accessi + 1), previous_access = last_access, last_access = NOW() WHERE uid = ?");
        }
        $smt->bind_param("i", $uid);
        $smt->execute();

        return $user;
    }
}
