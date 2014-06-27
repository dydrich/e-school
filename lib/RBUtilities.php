<?php

require_once "data_source.php";
require_once "classes.php";
require_once 'AnnoScolastico.php';

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
	 * @return RBUtilitiees instance
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
	 * Load an instance of some UserBean class
	 * @param integer $uid - the user's id
	 * @param string $area - type of user
	 */
	public function loadUserFromUid($uid, $area){
		switch ($area){
			case "student":
				$sel_user = "SELECT id_alunno, nome, cognome, username, nickname, accessi, stile, rb_alunni.id_classe, CONCAT(anno_corso, sezione) AS desc_cls FROM rb_alunni, rb_classi WHERE rb_alunni.id_classe = rb_classi.id_classe AND id_alunno = {$uid}";
				$ut = $this->datasource->executeQuery($sel_user);
				$utente = $ut[0];
				$gid = array(8);
				$perms = 256;
				$user = new StudentBean($uid, $utente['nome'], $utente['cognome'], $gid, $perms, $utente['username']);
				$user->setClass($utente['id_classe']);
				$user->setClassDescritption($utente['desc_cls']);
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
			case "school":
			default:
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
	
	public function loadStudentWSupport($uid, $stid){
		$sel_st = "SELECT cognome, nome, data_nascita, luogo_nascita, alunno FROM rb_alunni, rb_assegnazione_sostegno WHERE docente = {$uid} AND anno = {$_SESSION['__current_year__']->get_ID()} AND id_alunno = alunno AND alunno = {$stid} ";
		$res_st = $this->datasource->execute($sel_st);
		
		if (count($res_st) == 1){
			$row = $res_st[0];
			$student = $row;
		}
		$sel_indirizzo = "SELECT * FROM rb_indirizzi_alunni WHERE id_alunno = {$stid}";
		$res_indirizzo = $this->datasource->execute($sel_indirizzo);
		$student['indirizzo'] = array();
		if (count($res_indirizzo) > 0){
			$indirizzo = $res_indirizzo[0];
			$student['indirizzo'] = $indirizzo;
		}
		$sel_dati = "SELECT * FROM rb_dati_sostegno WHERE alunno = {$stid}";
		$res_dati = $this->datasource->execute($sel_dati);
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
	
}
