<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 28/09/15
 * Time: 10.35
 */

namespace eschool;


class StudentActivityReport
{

	private $student;
	private $days;
	private $activities;
	private $datasource;

	/**
	 * StudentActivityReport constructor.
	 * @param $student
	 * @param $days
	 */
	public function __construct(\StudentBean $student, $days = 15, \MySQLDataLoader $sql) {
		$this->student = $student;
		if (is_int($days)) {
			$this->days = $days;
		}
		$this->datasource = $sql;
		$this->loadActivities();
	}

	/**
	 * @return mixed
	 */
	public function getStudent() {
		return $this->student;
	}

	/**
	 * @param mixed $student
	 */
	public function setStudent($student) {
		$this->student = $student;
	}

	/**
	 * @return mixed
	 */
	public function getDays() {
		return $this->days;
	}

	/**
	 * @param mixed $days
	 */
	public function setDays($days) {
		if (is_int($days)) {
			$this->days = $days;
		}
	}

	private function loadActivities() {
		$year = $_SESSION['__current_year__']->get_ID();
		$cls = $this->student->getClass();
		$studID = $this->student->getUid();
		$activities = [];
		/*
		 * ritardi e assenze
		 */
		$sel_delays = "SELECT data, rb_reg_alunni.ingresso AS ingresso, rb_reg_classi.ingresso AS enter FROM rb_reg_classi, rb_reg_alunni
					  WHERE id_anno = {$year}
					  AND id_reg = id_registro
					  AND rb_reg_classi.id_classe = {$cls}
					  AND id_alunno = {$studID}
					  AND data BETWEEN DATE_SUB(NOW(), INTERVAL 15 DAY) AND NOW()
					  ORDER BY data DESC, rb_reg_alunni.ingresso";
		$res_delays = $this->datasource->executeQuery($sel_delays);
		foreach ($res_delays as $as) {
			if ($as['ingresso'] == "") {
				if (!isset($activities[$as['data']])) {
					$activities[$as['data']] = [];
				}
				$activities[$as['data']][] = ["tipo" => "Assenza", "descrizione" => "Assente"];
			}
			else if ($as['ingresso'] > $as['enter']) {
				if (!isset($activities[$as['data']])) {
					$activities[$as['data']] = [];
				}
				$activities[$as['data']][] = ["tipo" => "Ritardo", "descrizione" => " - ingresso ore " . substr($as['ingresso'], 0, 5)];
			}
		}
		/*
		 * voti
		 */
		$sel_grades = "SELECT rb_voti.*, rb_materie.materia AS desc_mat
					  FROM rb_voti, rb_materie
					  WHERE alunno = {$studID}
					  AND rb_voti.materia = id_materia
					  AND data_voto BETWEEN DATE_SUB(NOW(), INTERVAL 15 DAY) AND NOW()
					  AND privato = 0
					  ORDER BY data_voto DESC";
		$res_grades = $this->datasource->executeQuery($sel_grades);
		if($res_grades) {
			foreach ($res_grades as $grade) {
				if (!isset($activities[$grade['data_voto']])) {
					$activities[$grade['data_voto']] = [];
				}
				$activities[$grade['data_voto']][] = ["tipo" => "Voto", "descrizione" => " di ".$grade['desc_mat'].": ".$grade['voto'], "voto" => $grade['voto']];
			}
		}

		/*
		 * note disciplinari
		 */
		$sel_notes = "SELECT rb_note_disciplinari.*,
					rb_utenti.cognome,
					rb_utenti.nome,
					rb_tipi_note_disciplinari.descrizione AS tipo_nota,
					rb_tipi_note_disciplinari.id_tiponota
					FROM rb_note_disciplinari, rb_tipi_note_disciplinari, rb_utenti
					WHERE id_tiponota = tipo
					AND tipo <> 2
					AND alunno = $studID
					AND anno = {$year}
					AND docente = uid
					AND data BETWEEN DATE_SUB(NOW(), INTERVAL 15 DAY) AND NOW()
					ORDER BY data DESC";
		$res_notes = $this->datasource->executeQuery($sel_notes);
		if ($res_notes) {
			foreach ($res_notes as $note) {
				if (!isset($activities[$note['data']])) {
					$activities[$note['data']] = [];
				}
				$activities[$note['data']][] = ["tipo" => "Nota disciplinare", "descrizione" => $note['tipo_nota'] . " (" . $note['cognome'] . " " . $note['nome'] . ")"];
			}
		}
		/*
		 * note didattiche
		 */
		$sel_notes = "SELECT rb_note_didattiche.*,
					rb_materie.materia AS desc_mat,
					rb_tipi_note_didattiche.descrizione AS tipo_nota,
					rb_tipi_note_didattiche.id_tiponota
					FROM rb_note_didattiche, rb_tipi_note_didattiche, rb_materie
					WHERE id_tiponota = tipo
					AND tipo <> 2
					AND alunno = $studID
					AND anno = {$year}
					AND rb_note_didattiche.materia = id_materia
					AND data BETWEEN DATE_SUB(NOW(), INTERVAL 15 DAY) AND NOW()
					ORDER BY data DESC";
		$res_notes = $this->datasource->executeQuery($sel_notes);
		if ($res_notes) {
			foreach ($res_notes as $note) {
				if (!isset($activities[$note['data']])) {
					$activities[$note['data']] = [];
				}
				$activities[$note['data']][] = ["tipo" => "Nota didattica", "descrizione" => $note['tipo_nota'] . " (" . $note['desc_mat'] . ")", "idnota" => $note['tipo']];
			}
		}
		krsort($activities);
		$this->activities = $activities;
	}

	/**
	 * @return mixed
	 */
	public function getActivities() {
		return $this->activities;
	}

	/**
	 * @return mixed
	 */
	public function checkMonthlyReport() {
		$activeReport = $this->datasource->executeQuery("SELECT * FROM rb_pagellini WHERE data_chiusura >= DATE_SUB(NOW(), INTERVAL 15 DAY)");
		if ($activeReport && count($activeReport) > 0) {
			$ins = $this->datasource->executeCount("SELECT * FROM rb_segnalazioni_pagellino WHERE id_pagellino = ".$activeReport[0]['id_pagellino']." AND alunno = ".$this->student->getUid());
			if ($ins) {
				return $activeReport[0]['mese'];
			}
		}
		return false;
	}

}
