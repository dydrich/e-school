<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 05/04/15
 * Time: 20.34
 */

namespace eschool;


class EndOfYearEvaluationManager {

	private $datasource;

	private $year;

	private $session;

	private $class;

	private $schoolLevel;

	private $student;

	private $subject;

	private $students;

	private $subjects;

	private $reportCardsPubblicationID;

	private $actionScope;

	public static $GLOBAL_SCOPE    = 1;
	public static $CLASS_SCOPE     = 2;
	public static $STUDENT_SCOPE   = 3;

	public function __construct(\MySQLDataLoader $datasource, $year, $session, $sl) {
		$this->datasource = $datasource;
		$this->year = $year;
		$this->session = $session;
		$this->schoolLevel = $sl;
		$this->subject = null;
		$this->class = null;
		$this->student = null;
	}

	/**
	 * @return mixed
	 */
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * @param mixed $subject
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}

	/**
	 * @return mixed
	 */
	public function getDatasource() {
		return $this->datasource;
	}

	/**
	 * @param mixed $datasource
	 */
	public function setDatasource(\MySQLDataLoader $datasource) {
		$this->datasource = $datasource;
	}

	/**
	 * @return mixed
	 */
	public function getYear() {
		return $this->year;
	}

	/**
	 * @param mixed $year
	 */
	public function setYear($year) {
		$this->year = $year;
	}

	/**
	 * @return mixed
	 */
	public function getSession() {
		return $this->session;
	}

	/**
	 * @param mixed $session
	 */
	public function setSession($session) {
		$this->session = $session;
	}

	/**
	 * @return mixed
	 */
	public function getClass() {
		return $this->class;
	}

	/**
	 * @param mixed $class
	 */
	public function setClass($class) {
		$this->class = $class;
	}

	/**
	 * @return mixed
	 */
	public function getSchoolLevel() {
		return $this->schoolLevel;
	}

	/**
	 * @param mixed $schoolLevel
	 */
	public function setSchoolLevel($schoolLevel) {
		$this->schoolLevel = $schoolLevel;
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
	public function getActionScope() {
		return $this->actionScope;
	}

	/**
	 * @param mixed $actionScope
	 * questo metodo va chiamato per ultimo prima dell'operazione, in quanto
	 * carica i dati necessari alla classe con il metodo init()
	 */
	public function setActionScope($actionScope) {
		$this->actionScope = $actionScope;
		$this->init();
	}

	public function insert () {
		$this->insertReportCards();
		reset ($this->students);
		foreach ($this->students as $alunno) {
			$id_alunno = $alunno['id_alunno'];
			$classe = $alunno['id_classe'];
			$desc_classe = $alunno['desc_cls'];
			foreach($this->subjects as $materia){
				if (($alunno['musicale'] != 1) && ($materia == 13)) {
					continue;
				}

				$ins = "INSERT INTO rb_scrutini (alunno, classe, anno, quadrimestre, materia) VALUES ($id_alunno, $classe, {$this->year}, {$this->session}, {$materia})";
				$this->datasource->executeUpdate($ins);
			}
		}
	}

	public function delete () {
		$classes_table = "rb_vclassi_s{$this->schoolLevel}";
		$delete_statement = "DELETE FROM rb_scrutini WHERE anno = {$this->year} AND quadrimestre = {$this->session} AND classe IN (SELECT id_classe FROM {$classes_table}) ";
		if ($this->actionScope == EndOfYearEvaluationManager::$STUDENT_SCOPE) {
			$delete_statement .= " AND alunno = ".$this->student;
		}
		else if ($this->actionScope == EndOfYearEvaluationManager::$CLASS_SCOPE) {
			$delete_statement .= " AND classe = ".$this->class;
		}
		if ($this->subject != null) {
			$delete_statement .= " AND materia = ".$this->subject;
		}

		$this->datasource->executeUpdate($delete_statement);
	}

	public function reinsert () {
		$this->delete();
		$this->insert();
	}

	public function insertSubject () {

	}

	public function deleteSubject () {

	}

	private function init() {
		$this->checkReportCard();
		$this->loadStudents();
		$this->loadSubjects();
	}

	private function checkReportCard() {
		$pub = $this->datasource->executeCount("SELECT id_pagella FROM rb_pubblicazione_pagelle WHERE anno = {$this->year} AND quadrimestre = {$this->session}");
		if ($pub == null){
			// inserisco pubblicazione
			$this->reportCardsPubblicationID = $this->datasource->executeUpdate("INSERT INTO rb_pubblicazione_pagelle (anno, quadrimestre) VALUES ({$this->year}, {$this->session})");
		}
		else {
			$this->reportCardsPubblicationID = $pub;
		}
	}

	private function loadStudents() {
		$classes_table = "rb_vclassi_s{$this->schoolLevel}";
		$students_param = "";
		if ($this->actionScope == EndOfYearEvaluationManager::$STUDENT_SCOPE) {
			$students_param = " AND id_alunno = ".$this->student;
		}
		else if ($this->actionScope == EndOfYearEvaluationManager::$CLASS_SCOPE) {
			$students_param = "AND rb_alunni.id_classe = ".$this->class;
		}

		$sel_alunni = "SELECT id_alunno, rb_alunni.id_classe, musicale, CONCAT(anno_corso, sezione) AS desc_cls, ordine_di_scuola
						FROM rb_alunni, {$classes_table} WHERE attivo = '1' $students_param AND rb_alunni.id_classe = {$classes_table}.id_classe";
		$this->students = $this->datasource->executeQuery($sel_alunni);
	}

	private function loadSubjects() {
		if ($this->subject != null) {
			$sel_materie = "SELECT id_materia FROM rb_materie WHERE id_materia = ".$this->subject;
		}
		else {
			$sel_materie = "SELECT id_materia FROM rb_materie WHERE pagella = 1 AND tipologia_scuola = {$this->schoolLevel} ORDER BY id_materia";
		}
		$this->subjects = $this->datasource->executeQuery($sel_materie);
	}

	private function insertReportCards() {
		$pub = $this->reportCardsPubblicationID;
		foreach ($this->students as $alunno) {
			$id_alunno = $alunno['id_alunno'];
			$classe = $alunno['id_classe'];
			$desc_classe = $alunno['desc_cls'];
			$this->datasource->executeUpdate("INSERT INTO rb_pagelle (id_pubblicazione, id_alunno, id_classe, desc_classe) VALUES ({$pub}, {$id_alunno}, {$classe}, '{$desc_classe}')");
		}
	}

}
