<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 21/07/14
 * Time: 15.32
 *
 * rappresenta un voto
 *
 */

class Grade {

	private $id;

	private $grade;

	private $subject;

	private $teacher;

	private $student;

	private $gradeDate;

	private $classwork;

	private $privateGrade;

	private $description;

	private $type;

	private $note;

	private $year;

	private $topic;

	private $datasource;

	private $response;

	private $religionGrades;

	private $religionGrade;

	/**
	 * @param mixed $religionGrade
	 */
	public function setReligionGrade() {
		return $this->subject == 26 || $this->subject = 30;
	}

	/**
	 * @return mixed
	 */
	public function isReligionGrade() {
		return $this->religionGrade;
	}

	public function __construct($id, $data, DataLoader $dl){
		$this->id = $id;
		$this->classwork = null;
		$this->religionGrades = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");
		$this->setReligionGrade();
		if ($data != null) {
			$this->id = $data['id_voto'];
			$this->grade = $data['voto'];
			$this->subject = $data['materia'];
			$this->teacher = $data['docente'];
			$this->student = $data['alunno'];
			$this->gradeDate = $data['data_voto'];
			$this->year = $data['anno'];
			$this->description = $data['descrizione'];
			$this->type = $data['tipologia'];
			$this->topic = $data['argomento'];
			$this->note = $data['note'];
			$this->privateGrade = ($data['privato'] == 1) ? true : false;
			if ($data['id_verifica'] != null && $data['id_verifica'] != "") {
				$this->classwork = $data['verifica'];
			}
		}
		$this->datasource = $dl;
	}

	/**
	 * @param mixed $classwork
	 */
	public function setClasswork($classwork) {
		$this->classwork = $classwork;
	}

	/**
	 * @return mixed
	 */
	public function getClasswork() {
		return $this->classwork;
	}

	/**
	 * @param mixed $grade
	 */
	public function setGrade($grade) {
		$this->grade = $grade;
	}

	/**
	 * @return mixed
	 */
	public function getGrade() {
		return $this->grade;
	}

	/**
	 * @param mixed $gradeDate
	 */
	public function setGradeDate($gradeDate) {
		$this->gradeDate = $gradeDate;
	}

	/**
	 * @return mixed
	 */
	public function getGradeDate() {
		return $this->gradeDate;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
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
	public function getStudent() {
		return $this->student;
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
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * @param mixed $teacher
	 */
	public function setTeacher($teacher) {
		$this->teacher = $teacher;
	}

	/**
	 * @return mixed
	 */
	public function getTeacher() {
		return $this->teacher;
	}

	/**
	 * @param mixed $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return mixed
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param mixed $note
	 */
	public function setNote($note) {
		$this->note = $note;
	}

	/**
	 * @return mixed
	 */
	public function getNote() {
		return $this->note;
	}

	/**
	 * @param mixed $privateGrade
	 */
	public function setPrivateGrade($privateGrade) {
		$this->privateGrade = $privateGrade;
	}

	/**
	 * @return mixed
	 */
	public function isPrivateGrade() {
		return $this->privateGrade;
	}

	/**
	 * @param mixed $topic
	 */
	public function setTopic($topic) {
		$this->topic = $topic;
	}

	/**
	 * @return mixed
	 */
	public function getTopic() {
		return $this->topic;
	}

	/**
	 * @param mixed $type
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @return mixed
	 */
	public function getType() {
		return $this->type;
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
	public function getYear() {
		return $this->year;
	}

	public function getResponse(){
		return $this->response;
	}

	public function save(){
		if ($this->id == 0) {
			// insert
		}
		else {
			// update
		}
	}

	public function delete(){

	}

} 