<?php

require_once 'users_classes.php';

class Authenticator {
	
	private $datasource;
	
	/**
	 * formatted string with data for ajax response
	 * @var string
	 */
	private $stringAjax;
	
	public function __construct(DataLoader $dl){
		$this->datasource = $dl;
	}
	
	public function getStringAjax(){
		return $this->stringAjax;
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
		$sel_user = "SELECT rb_utenti.uid, nome, cognome, username, accessi, permessi FROM rb_utenti, rb_gruppi_utente WHERE rb_utenti.uid = rb_gruppi_utente.uid AND username = '{$nick}' AND password = '".trim($pass)."' AND gid NOT IN (8) ";
		$res_utente = $this->datasource->executeQuery($sel_user);
		if ($res_utente == null){
			return false;
		}
		$utente = $res_utente[0];
				
		$sel_gr = "SELECT gid FROM rb_gruppi_utente WHERE uid = {$utente['uid']}";
		$gid = $this->datasource->executeQuery($sel_gr);
		//$gid = array();
		$str_groups = join(",", $gid);
		
		$user = new SchoolUserBean($utente['uid'], $utente['nome'], $utente['cognome'], $gid, $utente['permessi'], $nick);
		
		if (in_array(4, $gid)){
			// genitore
			$sel_figli = "SELECT id_alunno FROM rb_genitori_figli WHERE id_genitore = ".$utente['uid'];
			$figli = $this->datasource->executeQuery($sel_figli);
			if(count($figli) > 0){
				$_SESSION['__figli__'] = join(",", $figli);
			}
			else
				$_SESSION['__figli__'] = "";
			$_SESSION['__parent__'] = 1;
		}
		
		/**
		 * profile
		 */
		$sel_profile = "SELECT * FROM rb_profili WHERE id = ".$user->getUid();
		$profile = $this->datasource->executeQuery($sel_profile);
		if($profile != null){
			$user->setProfile($profile);
		}
		
		/**
		 * subjects and classes : only for teachers
		 */
		if($user->isTeacher()){
			$sel_subject = "SELECT materia, tipologia_scuola, ruolo FROM rb_docenti WHERE id_docente = ".$user->getUid();
			$r_materia = $this->datasource->executeQuery($sel_subject);
			$materia = $r_materia[0];
			$user->setSubject($materia['materia']);
			$user->setSchoolOrder($materia['tipologia_scuola']);
			$titolare = ($materia['ruolo'] == "S") ? true : false;
			/*
			 * supplente: ancora in servizio
			 */
			if (!$titolare) {
				$max_date = $this->datasource->executeCount("SELECT MAX(data_fine_supplenza) FROM rb_supplenze WHERE id_supplente = {$user->getUid()}");
				if ($max_date < date("Y-m-d")) {
					return null;
				}
			}

			/**
			 * populate the classes array
			*/
			$classes = array();
			$uid = $user->getUid();
			//echo $uid;
			if (!$titolare) {
				$uid = $this->datasource->executeCount("SELECT id_docente_assente FROM rb_supplenze WHERE id_supplente = {$user->getUid()} AND data_fine_supplenza >= NOW()");
				$user->setSubstitution($uid);
			}
			if ($materia['materia'] != 27 && $materia['materia'] != 41){
				$sel_cdc = "SELECT rb_classi.id_classe, CONCAT(rb_classi.anno_corso, rb_classi.sezione) AS classe, id_materia FROM rb_classi, rb_cdc WHERE anno_corso <> 0 AND rb_classi.id_classe = rb_cdc.id_classe AND id_docente = {$uid} AND id_anno = ".$_SESSION['__current_year__']->get_ID()." ORDER BY rb_classi.sezione, rb_classi.anno_corso";
			}
			else {
				$sel_cdc = "SELECT rb_classi.id_classe, CONCAT(rb_classi.anno_corso, rb_classi.sezione) AS classe, '{$materia['materia']}' AS materia FROM rb_classi, rb_assegnazione_sostegno WHERE anno_corso <> 0 AND rb_classi.id_classe = classe AND docente = {$uid} AND anno = ".$_SESSION['__current_year__']->get_ID()." ORDER BY rb_classi.sezione, rb_classi.anno_corso";
			}
			//echo $sel_cdc;
			$res_cdc = $this->datasource->executeQuery($sel_cdc);

			if (!$titolare) {
				/*
				 * classi supplente
				 */
				$cls_supp = $this->datasource->executeQuery("SELECT classe FROM rb_classi_supplenza, rb_supplenze WHERE rb_classi_supplenza.id_supplenza = rb_supplenze.id_supplenza AND id_supplente = {$user->getUid()} ");
			}
			foreach ($res_cdc as $row){
				if ($titolare || in_array($row['id_classe'], $cls_supp)) {
					if(!isset($classes[$row['id_classe']])){
						//echo $row['id_classe'];
						$classes[$row['id_classe']] = array();
						$classes[$row['id_classe']]['teacher'] = 1;
						$classes[$row['id_classe']]['coordinatore'] = 0;
						$classes[$row['id_classe']]['segretario'] = 0;
						$classes[$row['id_classe']]['materie'] = array();
						$classes[$row['id_classe']]['classe'] = $row['classe'];
						$classes[$row['id_classe']]['id_classe'] = $row['id_classe'];
					}
					@array_push($classes[$row['id_classe']]['materie'], $row['id_materia']);
				}
			}
			/*
			 * estrazione classi in cui si e` coordinatori o segretari ma non docenti
			*/
			$sel_other_cls = "SELECT * FROM rb_classi WHERE anno_corso <> 0 AND (coordinatore = {$user->getUid()} OR segretario = {$user->getUid()})";
			$res_other_cls = $this->datasource->executeQuery($sel_other_cls);
			if($res_other_cls != null){
				foreach ($res_other_cls as $row){
					if ($classes[$row['id_classe']]){
						if($row['coordinatore'] == $user->getUid()){
							$classes[$row['id_classe']]['coordinatore'] = 1;
						}
						if($row['segretario'] == $user->getUid()){
							$classes[$row['id_classe']]['segretario'] = 1;
						};
						continue;
					}
					$classes[$row['id_classe']] = array();
					$classes[$row['id_classe']]['teacher'] = 0;
					$classes[$row['id_classe']]['coordinatore'] = 0;
					$classes[$row['id_classe']]['segretario'] = 0;
					$classes[$row['id_classe']]['materie'] = array();
					$classes[$row['id_classe']]['classe'] = $row['anno_corso'].$row['sezione'];
					$classes[$row['id_classe']]['id_classe'] = $row['id_classe'];
					if($row['coordinatore'] == $user->getUid()){
						$classes[$row['id_classe']]['coordinatore'] = 1;
					}
					if($row['segretario'] == $user->getUid()){
						$classes[$row['id_classe']]['segretario'] = 1;
					}
				}
			}
			$user->setClasses($classes);
		}

		$_SESSION['__accessi__'] = $utente['accessi'] + 1;
		
		/*
		 * for checking administration area access
		*/
		if($user->isAdministrator()){
			$now = time();
			$_SESSION['__admin_authentication_timeout__'] = $now;
		}
		

		
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
		
		$update = "UPDATE rb_utenti SET accessi = (accessi + 1), previous_access = last_access, last_access = NOW() WHERE uid = ".$utente['uid'];
		$upd = $this->datasource->executeUpdate($update);
		
		$this->stringAjax = "0;".$str_groups.";".$utente['nome'].";".$utente['cognome'].";".$_SESSION['__accessi__'].";".$utente['permessi'];

		return $user;
	}
	
	public function parentLogin($nick, $pass){
		$sel_user = "SELECT uid, nome, cognome, username, accessi FROM rb_utenti WHERE username = '{$nick}' AND password = '".trim($pass)."'";
		$res_user = $this->datasource->executeQuery($sel_user);
		if ($res_user == null){
			return false;
		}
		$utente = $res_user[0];
	
		$gid = array(4);
		$perms = 8;
		$user = new ParentBean($utente['uid'], $utente['nome'], $utente['cognome'], $gid, $perms, $nick);

		$sel_figli = "SELECT id_alunno FROM rb_genitori_figli WHERE id_genitore = ".$utente['uid'];
		$figli = $this->datasource->executeQuery($sel_figli);
		$user->setChildren($figli);
		if(count($figli) > 0){
			$_SESSION['__figli__'] = join(",", $figli);
		}
		else{
			$_SESSION['__figli__'] = "";
		}
		$_SESSION['__parent__'] = 1;
		$_SESSION['__accessi__'] = $utente['accessi'] + 1;
		$first_access = 0;
		if($utente['accessi'] == 0)
			$first_access = 1;
		$_SESSION['__parent__'] = 1;
		$sel_children_names = "SELECT CONCAT_WS(' ', cognome, nome) AS nome FROM rb_alunni WHERE id_alunno IN ({$_SESSION['__figli__']})";
		$children_names = $this->datasource->executeQuery($sel_children_names);
		$user->setChildrenNames($children_names);
	
		$update = "UPDATE rb_utenti SET accessi = (accessi + 1) WHERE uid = ".$utente['uid'];
		$upd = $this->datasource->executeUpdate($update);

		$this->stringAjax = "G;".$utente['username'].";".$utente['uid'].";".$utente['nome'].";".$utente['cognome'].";".$_SESSION['__figli__'].";".$_SESSION['__accessi__'].";8;".$first_access;
		
		return $user;
	}
	
	public function studentLogin($nick, $pass){
		$sel_user = "SELECT id_alunno, nome, cognome, username, nickname, accessi, stile, rb_alunni.id_classe, CONCAT(anno_corso, sezione) AS desc_cls, ordine_di_scuola FROM rb_alunni, rb_classi WHERE rb_alunni.id_classe = rb_classi.id_classe AND username = '{$nick}' AND password = '".trim($pass)."' AND attivo = '1'";
		$res_user = $this->datasource->executeQuery($sel_user);
		if($res_user == null){
			return false;
		}
		$utente = $res_user[0];
		
		$gid = array(8);
		$perms = 256;
		$user = new StudentBean($utente['id_alunno'], $utente['nome'], $utente['cognome'], $gid, $perms, $nick);
		$user->setClass($utente['id_classe']);
		$user->setClassDescritption($utente['desc_cls']);
		$user->setSchoolOrder($utente['ordine_di_scuola']);
				
		$_SESSION['__nick__'] = $utente['nick'];
		$_SESSION['__accessi__'] = $utente['accessi'] + 1;
		$_SESSION['__perms__'] = 256;
		if($utente['accessi'] == 0){
			$first_access = 1;
		}
		$_SESSION['__student__'] = 1;
		
		$update = "UPDATE rb_alunni SET accessi = (accessi + 1) WHERE id_alunno = ".$utente['id_alunno'];
		$upd = $this->datasource->executeUpdate($update);
		
		$this->stringAjax = "S;".$utente['username'].";".$utente['id_alunno'].";".$utente['nome'].";".$utente['cognome'].";".$utente['nick'].";".$_SESSION['__accessi__'].";".$first_access;
		
		return $user;
	}
}
