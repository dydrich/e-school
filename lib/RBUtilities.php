<?php

require_once "data_source.php";
require_once "classes.php";
require_once 'AnnoScolastico.php';
require_once "Authenticator.php";
require_once 'Subject.php';

final class RBUtilities{
	
	private $datasource;
	private static $instance;
	
	private function __construct($conn){
		if ($conn instanceof MySQLDataLoader){
			$this->datasource = $conn;
		}
		else{
			$this->datasource = new MySQLDataLoader($conn);
		}
	}

	/**
	 * Load an instance of RBUtilities - Singleton
	 * @param MySQLConnection or MySQLDataLoader $conn - db access
	 * @return RBUtilities instance
	 */
	public static function getInstance($conn){
		if(empty(self::$instance)){
			self::$instance = new RBUtilities($conn);
		}
		return self::$instance;
	}

	/**
	 * Load an instance of Classe using a student ID
	 * @param integer $uid - the student id
	 * @return Classe $cls - instance of Classe
	 */
	public function loadClassFromUserID($uid){
		$sel_classe = "SELECT rb_classi.*, rb_sedi.nome FROM rb_classi, rb_sedi, rb_alunni WHERE sede = id_sede AND rb_classi.id_classe = rb_alunni.id_classe AND rb_alunni.id_alunno = {$uid}";
		$res_classe = $this->datasource->executeQuery($sel_classe);
		if (!$res_classe){
			return false;
		}
		$cls = new Classe($res_classe[0], $this->datasource->getSource());
		return $cls;
	}

	/**
	 * Load an instance of Classe using a class ID
	 * @param integer $cid - the class id
	 * @return Classe $cls - instance of Classe
	 */
	public function loadClassFromClassID($cid){
		$sel_classe = "SELECT rb_classi.*, rb_sedi.nome FROM rb_classi, rb_sedi WHERE sede = id_sede AND rb_classi.id_classe = {$cid}";
		$res_classe = $this->datasource->executeQuery($sel_classe);
		$cls = new Classe($res_classe[0], $this->datasource->getSource());
		return $cls;
	}
	
	public function loadUserConfig($uid){
		$sel_config = "SELECT rb_parametri_utente.*, rb_parametri_configurabili.codice FROM rb_parametri_utente, rb_parametri_configurabili WHERE rb_parametri_utente.id_parametro = rb_parametri_configurabili.id AND id_utente = {$uid}";
		$res_config = $this->datasource->executeQuery($sel_config);
		$config = array();
		if ($res_config){
			foreach ($res_config as $row){
				$data = explode(";", $row['valore']);
				$config[$row['codice']] = $data;
			}
		}
		return $config;
	}

	/**
	 * Load an instance of AnnoScolastico
	 * @param integer $id - the year's id
	 * @return AnnoScolastico
	 */
	public function loadYearFromID($id){
		$sel_anno = "SELECT * FROM rb_anni WHERE id_anno = {$id}";
		$res_anno = $this->datasource->executeQuery($sel_anno);
		$year = new AnnoScolastico($res_anno[0]);
		return $year;
	}

	/**
	 * Load an instance of Subject
	 * @param integer $id - the subject id
	 * @return Subject
	 */
	public function loadSubjectFromID($id){
		$sel_sub = "SELECT * FROM rb_materie WHERE id_materia = {$id}";
		$res_sub = $this->datasource->executeQuery($sel_sub);
		$mat = new \eschool\Subject($res_sub[0]['id_materia'], $res_sub[0]['materia']);
		return $mat;
	}
	
	/**
	 * Load an instance of some UserBean class
	 * @param integer $uid - the user's id
	 * @param string $area - type of user
	 */
	public function loadUserFromUid($uid, $area){

		switch ($area){
			case "student":
				/*
				$sel_user = "SELECT id_alunno, nome, cognome, username, nickname, accessi, stile, rb_alunni.id_classe, CONCAT(anno_corso, sezione) AS desc_cls FROM rb_alunni, rb_classi WHERE rb_alunni.id_classe = rb_classi.id_classe AND id_alunno = {$uid}";
				$ut = $this->datasource->executeQuery($sel_user);
				$utente = $ut[0];
				$gid = array(8);
				$perms = 256;
				$user = new StudentBean($uid, $utente['nome'], $utente['cognome'], $gid, $perms, $utente['username']);
				$user->setClass($utente['id_classe']);
				$user->setClassDescritption($utente['desc_cls']);
				*/
				$sel_user = "SELECT id_alunno, nome, cognome, username, nickname, accessi, stile, rb_alunni.id_classe, CONCAT(anno_corso, sezione) AS desc_cls, ordine_di_scuola FROM rb_alunni, rb_classi WHERE rb_alunni.id_classe = rb_classi.id_classe AND id_alunno = {$uid} AND attivo = '1'";
				$res_user = $this->datasource->executeQuery($sel_user);
				if($res_user == null){
					return false;
				}
				$utente = $res_user[0];

				$gid = array(8);
				$perms = 256;
				$user = new StudentBean($utente['id_alunno'], $utente['nome'], $utente['cognome'], $gid, $perms, $utente['username']);
				$user->setClass($utente['id_classe']);
				$user->setClassDescritption($utente['desc_cls']);
				$user->setSchoolOrder($utente['ordine_di_scuola']);
				break;
			case "parent":
				$sel_user = "SELECT nome, cognome, username, accessi FROM rb_utenti WHERE uid = {$uid}";
				$ut = $this->datasource->executeQuery($sel_user);
				$utente = $ut[0];
				$gid = array(4);
				$perms = 8;
				$user = new ParentBean($uid, $utente['nome'], $utente['cognome'], $gid, $perms, $utente['username']);
				$sel_figli = "SELECT id_alunno FROM rb_genitori_figli WHERE id_genitore = {$uid}";
				$figli = $this->datasource->executeQuery($sel_figli);
				$user->setChildren($figli);
				$sel_children_names = "SELECT CONCAT_WS(' ', cognome, nome) AS nome FROM rb_alunni WHERE id_alunno IN (".implode(", ", $figli).")";
				$children_names = $this->datasource->executeQuery($sel_children_names);
				$user->setChildrenNames($children_names);
				break;
			case "simple_school":
				$sel_user = "SELECT nome, cognome, username, accessi, permessi FROM rb_utenti WHERE rb_utenti.uid = {$uid} ";
				$ut = $this->datasource->executeQuery($sel_user);
				$utente = $ut[0];
				$sel_gr = "SELECT gid FROM rb_gruppi_utente WHERE uid = {$uid}";
				$groups = $this->datasource->executeQuery($sel_gr);
				$gid = array();
				foreach ($groups as $group) {

					$gid[] = $group;
				}
				$str_groups = join(",", $gid);
				$user = new SchoolUserBean($uid, $utente['nome'], $utente['cognome'], $gid, $utente['permessi'], $utente['username']);
				break;
			case "school":
			default:
				//$sel_user = "SELECT username, password FROM rb_utenti WHERE rb_utenti.uid = {$uid} ";
				//$ut = $this->datasource->executeQuery($sel_user);
				/*
				$auth = new Authenticator($this->datasource);
				$user = $auth->login(3, $ut[0]['username'], $ut[0]['password']);
				if ($user == null) {
					$user = $this->loadUserFromUid($uid, 'simple_school');
				}
				*/
				$sel_user = "SELECT rb_utenti.uid, nome, cognome, username, accessi, permessi FROM rb_utenti, rb_gruppi_utente WHERE rb_utenti.uid = rb_gruppi_utente.uid AND rb_utenti.uid = {$uid} AND gid NOT IN (8) ";
				$res_utente = $this->datasource->executeQuery($sel_user);
				if ($res_utente == null){
					return false;
				}
				$utente = $res_utente[0];

				$sel_gr = "SELECT gid FROM rb_gruppi_utente WHERE uid = {$utente['uid']}";
				$gid = $this->datasource->executeQuery($sel_gr);
				$str_groups = join(",", $gid);

				$user = new SchoolUserBean($utente['uid'], $utente['nome'], $utente['cognome'], $gid, $utente['permessi'], $utente['username']);
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

					/**
					 * populate the classes array
					 */
					$classes = array();
					$uid = $user->getUid();
					if (!$titolare) {
						$user->setSupplyTeacher(true);
						$uid = $this->datasource->executeCount("SELECT id_docente_assente FROM rb_supplenze WHERE id_supplente = {$user->getUid()} AND data_fine_supplenza >= NOW()");
						if ($uid != null && $uid !== false) {
							$user->setSubstitution($uid);
						}
						else {
							$uid = $this->datasource->executeCount("SELECT id_docente_assente FROM rb_supplenze WHERE id_supplente = {$user->getUid()} ORDER BY data_fine_supplenza DESC LIMIT 1");
						}
					}
					if ($materia['materia'] != 27 && $materia['materia'] != 41){
						$sel_cdc = "SELECT rb_classi.id_classe, CONCAT(rb_classi.anno_corso, rb_classi.sezione) AS classe, id_materia FROM rb_classi, rb_cdc WHERE anno_corso <> 0 AND rb_classi.id_classe = rb_cdc.id_classe AND id_docente = {$uid} AND id_anno = ".$_SESSION['__current_year__']->get_ID()." ORDER BY rb_classi.sezione, rb_classi.anno_corso";
					}
					else {
						$sel_cdc = "SELECT rb_classi.id_classe, CONCAT(rb_classi.anno_corso, rb_classi.sezione) AS classe, '{$materia['materia']}' AS materia FROM rb_classi, rb_assegnazione_sostegno WHERE anno_corso <> 0 AND rb_classi.id_classe = classe AND docente = {$uid} AND anno = ".$_SESSION['__current_year__']->get_ID()." ORDER BY rb_classi.sezione, rb_classi.anno_corso";
					}
					$res_cdc = $this->datasource->executeQuery($sel_cdc);

					if (!$titolare) {
						/*
						 * classi supplente
						 */
						$cls_supp = $this->datasource->executeQuery("SELECT classe FROM rb_classi_supplenza, rb_supplenze WHERE rb_classi_supplenza.id_supplenza = rb_supplenze.id_supplenza AND id_supplente = {$user->getUid()} ");
					}
					if ($res_cdc != null && count($res_cdc) > 0) {
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
					if ($user == null) {
						$user = $this->loadUserFromUid($uid, 'simple_school');
					}
				}
				break;
		}
		if (is_installed("com")) {
			$uniqID = $this->datasource->executeCount("SELECT id FROM rb_com_users WHERE uid = {$uid} AND type = '{$area}'");
			$user->setUniqID($uniqID);
		}
		return $user;
	}
	
	public function calcola_minuti_assenza($ingresso, $uscita, $inizio, $fine){
		// mktime(ore, minuti, secondi, mese, giorno, anno)
		if($ingresso <= $inizio)
			$start = $inizio;
		else
			$start = $ingresso;
	
		if($fine >= $uscita)
			$end = $uscita;
		else
			$end = $fine;
	
		//print ("Inizia $start e finisce $end\n");
		$s = explode(":", $start);
		$e = explode(":", $end);
		$ore = intval($s[0], $base = 10);
		$minuti = intval($s[1], $base = 10);
		$ore2 = intval($e[0], $base = 10);
		$minuti2 = intval($e[1], $base = 10);
		//print ("Ore $ore vs. Ore $ore2\nMinuti $minuti vs $minuti2\n\n");
	
		$from = mktime($hour = $ore, $minute = $minuti, $second = 0, $month = 12, $day = 1, $year = 1970, $is_dst = -1);
		$to = mktime($hour = $ore2, $minute = $minuti2, $second = 0, $month = 12, $day = 1, $year = 1970, $is_dst = -1);
		//print("From $from to $to\n");
		return 60 - (($to - $from) / 60);
	}
	
	public static function loadStudentWSupport($uid, $stid, MySQLDataLoader $conn){
		$sel_st = "SELECT cognome, nome, data_nascita, luogo_nascita, alunno FROM rb_alunni, rb_assegnazione_sostegno WHERE docente = {$uid} AND anno = {$_SESSION['__current_year__']->get_ID()} AND id_alunno = alunno AND alunno = {$stid} ";
		$res_st = $conn->execute($sel_st);
		
		if (count($res_st) == 1){
			$row = $res_st[0];
			$student = $row;
		}
		$sel_indirizzo = "SELECT * FROM rb_indirizzi_alunni WHERE id_alunno = {$stid}";
		$res_indirizzo = $$conn->execute($sel_indirizzo);
		$student['indirizzo'] = array();
		if (count($res_indirizzo) > 0){
			$indirizzo = $res_indirizzo[0];
			$student['indirizzo'] = $indirizzo;
		}
		$sel_dati = "SELECT * FROM rb_dati_sostegno WHERE alunno = {$stid}";
		$res_dati = $$conn->execute($sel_dati);
		$student['dati'] = array();
		if (count($res_dati) > 0){
			$dati = $res_dati[0];
			$student['dati'] = $dati;
		}
		return $student;
	}

	public static function convertReligionGrade($grade){
		if ($grade < 5.5){
			return 4;
		}
		if ($grade < 7.5){
			return 6;
		}
		else {
			return round($grade, 0);
		}
	}

	/*
	 * download all reports
	 */
	public static function createAllReportsArchive($year_desc){
		$old_dir = getcwd();
		chdir($_SESSION['__config__']['html_root']."/download/pagelle/");
		$zip = new ZipArchive();
		$file_zip = "pagelle_".$year_desc.".zip";
		if (file_exists($file_zip)){
			unlink($file_zip);
		}
		if ($zip->open($file_zip, ZipArchive::CREATE)!==TRUE) {
			exit("cannot open <$file_zip>\n");
		}
		$root_path = "./{$year_desc}";
		$files = new RecursiveIteratorIterator (new RecursiveDirectoryIterator($root_path), RecursiveIteratorIterator::LEAVES_ONLY);
		foreach ($files as $name => $file) {
			$filePath = $file->getRealPath();
			$file_dirs = explode("/", $filePath);
			$act_dirs = array_slice($file_dirs, (count($file_dirs) - 4));
			$path = implode("/", $act_dirs);
			$basename = $file->getBasename();
			$ext = $file->getExtension();
			if ($basename != '.' && $basename != '..' && $ext != "zip"){
				$zip->addFile($filePath, $path);
			}
		}
		$zip->close();
		chdir($old_dir);
		return $file_zip;
	}

	/*
	 * download all teacher books
	 */
	public static function createAllTeachersBooksArchive($year_desc){
		$old_dir = getcwd();
		chdir($_SESSION['__config__']['html_root']."/download/registri/");
		$zip = new ZipArchive();
		$file_zip = "registri_".$year_desc.".zip";
		if (file_exists($file_zip)){
			unlink($file_zip);
		}
		if ($zip->open($file_zip, ZipArchive::CREATE)!==TRUE) {
			exit("cannot open <$file_zip>\n");
		}
		$root_path = "./{$year_desc}";
		$files = new RecursiveIteratorIterator (new RecursiveDirectoryIterator($root_path), RecursiveIteratorIterator::LEAVES_ONLY);
		foreach ($files as $name => $file) {
			$filePath = $file->getRealPath();
			$file_dirs = explode("/", $filePath);
			$act_dirs = array_slice($file_dirs, (count($file_dirs) - 5));
			$path = implode("/", $act_dirs);
			$basename = $file->getBasename();
			$ext = $file->getExtension();
			if ($basename != '.' && $basename != '..' && $ext != "zip"){
				$zip->addFile($filePath, $path);
			}
		}
		$zip->close();
		chdir($old_dir);
		return $file_zip;
	}

	public static function getReligionGrades() {
		return array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");
	}

	/**
	 * module: communication
	 * Load an instance of some UserBean class using the users's uniqID from table rb_com_utenti
	 * @param integer $uniqID - the user's uniqID
	 * @return UserBean $user - user
	 */
	public function loadUserFromUniqID($uniqID) {
		$data = $this->datasource->executeQuery("SELECT uid, type FROM rb_com_users WHERE id = ".$uniqID);
		$udata = $data[0];
		return $this->loadUserFromUid($udata['uid'], $udata['type']);
	}
	
}
