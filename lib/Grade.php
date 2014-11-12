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

	private $learningObjectives;

	/**
	 * @param mixed $religionGrade
	 */
	public function setReligionGrade() {
		if ($this->subject == 26 || $this->subject == 30) {
			$this->religionGrade = true;
		}
		else {
			$this->religionGrade = false;
		}
	}

	/**
	 * @return mixed
	 */
	public function isReligionGrade() {
		return $this->religionGrade;
	}

	public function __construct($id, $data, MySQLDataLoader $dl){
		$this->id = $id;
		$this->classwork = null;
		$this->religionGrades = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");
		$this->classwork = null;
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
			if ($data['id_verifica'] != null && $data['id_verifica'] != "" && $data['id_verifica'] != 0) {
				$this->classwork = $data['id_verifica'];
			}
		}
		$this->datasource = $dl;
		$this->setReligionGrade();
		if ($this->id != 0) {
			$this->loadLearningObjectives();
		}
		else {
			$this->learningObjectives = array();
		}
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
	public function getGrade($rel = false) {
		if ($rel && $this->isReligionGrade()) {
			return $this->religionGrades[$this->grade];
		}
		else {
			return $this->grade;
		}
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

	/**
	 * @param array $learningObjectives
	 */
	public function setLearningObjectives($learningObjectives) {
		$this->learningObjectives = $learningObjectives;
	}

	/**
	 * @return array
	 */
	public function getLearningObjectives() {
		return $this->learningObjectives;
	}

	private function loadLearningObjectives() {
		$los = $this->datasource->executeQuery("SELECT voto, obiettivo FROM rb_voti_obiettivo WHERE id_voto = ".$this->id);
		if ($los) {
			foreach ($los as $lo) {
				$this->learningObjectives[$lo['obiettivo']] = $lo['voto'];
			}
		}
		else {
			$this->learningObjectives = array();
		}
	}

	public function save(){
		$privato = 0;
		if ($this->isPrivateGrade()) {
			$privato = 1;
		}
		$this->verifyClassworkGrade();

		if ($this->id == 0) {
			// insert
			$stm = "INSERT INTO rb_voti (alunno, docente, materia, anno, voto, privato, descrizione, tipologia, note, data_voto, argomento, id_verifica, from_file, inserimento) ";
			$stm .= "VALUES ({$this->getStudent()}, {$this->getTeacher()}, {$this->getSubject()}, {$this->getYear()}, {$this->getGrade()}, {$privato}, '{$this->getDescription()}', {$this->getType()}, ".field_null($this->getNote(), true).", '{$this->getGradeDate()}', '{$this->getTopic()}', ".field_null($this->getClasswork(), false).", 'Grade', NOW())";
			return $this->datasource->executeUpdate($stm);
		}
		else {
			// update
			$stm = "UPDATE rb_voti SET voto = {$this->getGrade()}, privato = {$privato}, descrizione = '{$this->getDescription()}', tipologia = {$this->getType()}, note = ".field_null($this->getNote(), true).", data_voto = '{$this->getGradeDate()}', argomento = '{$this->getTopic()}' WHERE id_voto = {$this->getId()}";
			return $this->datasource->executeUpdate($stm);
		}
	}

	public function delete(){
		$this->datasource->executeUpdate("DELETE FROM rb_voti WHERE id_voto = {$this->getId()}");
		$this->datasource->executeUpdate("DELETE FROM rb_voti_obiettivo WHERE id_voto = {$this->getId()}");
	}

	public function hasLearningObjectives() {
		return count($this->learningObjectives) > 0;
	}

	public function updateLearningObjectiveGrade($newGrade, $lo) {
		$exists_goal_grade = $this->datasource->executeCount("SELECT id FROM rb_voti_obiettivo WHERE id_voto = {$this->id} AND obiettivo = {$lo}");
		if ($newGrade == 0) {
			if ($exists_goal_grade) {
				$this->datasource->executeUpdate("DELETE FROM rb_voti_obiettivo WHERE id = {$exists_goal_grade}");
			}
		}
		else {
			if ($exists_goal_grade) {
				//echo "UPDATE rb_voti_obiettivo SET voto = {$grade} WHERE id = {$exists_goal_grade}";
				$this->datasource->executeUpdate("UPDATE rb_voti_obiettivo SET voto = {$newGrade} WHERE id = {$exists_goal_grade}");
			}
			else {
				$this->datasource->executeUpdate("INSERT INTO rb_voti_obiettivo (id_voto, obiettivo, voto) VALUES ({$this->id}, {$lo}, {$newGrade})");
			}
		}
		$this->loadLearningObjectives();
	}

	public function updateLearningObjectivesGrades($newGrade) {
		if ($this->hasLearningObjectives()) {
			foreach ($this->learningObjectives as $k => $lo) {
				$this->updateLearningObjectiveGrade($newGrade, $k);
			}
		}
	}

	/**
	 * updates only some fields
	 * @param: fields: array of fields
	 * @param: values: array of values
	 * @param: chars: if the field is char or not
	 */
	public function updateFields($fields, $values, $chars) {
		$upd = "UPDATE rb_voti SET";
		for ($i = 0; $i < count($fields); $i++) {
			$upd .= " {$fields[$i]} = ".field_null($values[$i], $chars[$i]).",";
		}
		$upd = substr($upd, 0, strlen($upd) -1);
		$upd .= " WHERE id_voto = ".$this->id;
		$this->datasource->executeUpdate($upd);
	}

	protected function verifyClassworkGrade() {
		if ($this->classwork == null || $this->classwork == "") {
			return true;
		}
		$existsGrade = $this->datasource->executeCount("SELECT id_voto FROM rb_voti WHERE id_verifica = ".$this->classwork." AND alunno = ".$this->student);
		if ($existsGrade) {
			$this->id = $existsGrade;
			return false;
		}
	}

} 
