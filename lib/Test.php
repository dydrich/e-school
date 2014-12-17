<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 27/07/14
 * Time: 14.38
 */

namespace eschool;

require_once 'data_source.php';
require_once 'RBUtilities.php';
require_once 'users_classes.php';
require_once 'classes.php';
require_once 'Grade.php';


class Test {

	private $id;
	private $teacher;
	private $class;
	private $year;
	private $testDate;
	private $insertDate;
	private $subject;
	private $evaluated;
	private $type;
	private $description;
	private $topic;
	private $annotation;
	private $activityId;
	private $students;
	private $learningObjectives;
	private $datasource;

	public function __construct($id, \MySQLDataLoader $dl, $data, $loadFromDB){
		$this->id = $id;
		$this->datasource = $dl;
		$rb = \RBUtilities::getInstance($dl);
		if ($data != null) {
			$this->teacher = $rb->loadUserFromUid($data['id_docente'], "simple_school");
			$this->class = $rb->loadClassFromClassID($data['id_classe']);
			$this->year = $rb->loadYearFromID($data['id_anno']);
			$this->testDate = $data['data_verifica'];
			$this->insertDate = $data['data_assegnazione'];
			$this->subject = $rb->loadSubjectFromID($data['id_materia']);
			$this->evaluated = ($data['valutata'] == 1) ? true : false;
			$this->type = $data['tipologia'];
			$this->description = $data['prova'];
			$this->topic = $data['argomento'];
			$this->annotation = $data['note'];
			$this->activityId = $data['id_attivita'];
		}
		else if ($loadFromDB) {
			$this->loadFromDB();
		}
		else {
			return;
		}
		$this->loadStudents();
		if ($this->id != 0) {
			$this->setGrades();
			$this->loadLearningObjectives();
		}
		else {
			$this->learningObjectives = array();
		}
	}

	/**
	 * @param mixed $activityId
	 */
	public function setActivityId($activityId){
		$this->activityId = $activityId;
	}

	/**
	 * @param mixed $annotation
	 */
	public function setAnnotation($annotation){
		$this->annotation = $annotation;
	}

	/**
	 * @param mixed $class
	 */
	public function setClass(\Classe $class){
		$this->class = $class;
	}

	/**
	 * @param mixed $description
	 */
	public function setDescription($description){
		$this->description = $description;
	}

	/**
	 * @param mixed $insertDate
	 */
	public function setInsertDate($insertDate){
		$this->insertDate = $insertDate;
	}

	/**
	 * @param mixed $isEvaluated
	 */
	public function setIsEvaluated($isEvaluated){
		$this->isEvaluated = $isEvaluated;
	}

	/**
	 * @param mixed $subject
	 */
	public function setSubject(Subject $subject){
		$this->subject = $subject;
	}

	/**
	 * @param mixed $teacher
	 */
	public function setTeacher(\SchoolUserBean $teacher){
		$this->teacher = $teacher;
	}

	/**
	 * @param mixed $testDate
	 */
	public function setTestDate($testDate){
		$this->testDate = $testDate;
	}

	/**
	 * @param mixed $topic
	 */
	public function setTopic($topic){
		$this->topic = $topic;
	}

	/**
	 * @param mixed $type
	 */
	public function setType($type){
		$this->type = $type;
	}

	/**
	 * @return mixed
	 */
	public function getActivityId() {
		return $this->activityId;
	}

	/**
	 * @return mixed
	 */
	public function getAnnotation() {
		return $this->annotation;
	}

	/**
	 * @return mixed
	 */
	public function getClass() {
		return $this->class;
	}

	/**
	 * @return \MySQLDataLoader
	 */
	public function getDatasource() {
		return $this->datasource;
	}

	/**
	 * @return mixed
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getInsertDate() {
		return $this->insertDate;
	}

	/**
	 * @return mixed
	 */
	public function getIsEvaluated() {
		return $this->evaluated;
	}

	/**
	 * @return mixed
	 */
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * @return mixed
	 */
	public function getTeacher() {
		return $this->teacher;
	}

	/**
	 * @return mixed
	 */
	public function getTestDate() {
		return $this->testDate;
	}

	/**
	 * @return mixed
	 */
	public function getTopic() {
		return $this->topic;
	}

	/**
	 * @return mixed
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return mixed
	 */
	public function getYear() {
		return $this->year;
	}

	/**
	 * @return null
	 */
	public function getLearningObjectives() {
		return $this->learningObjectives;
	}

	/**
	 * @param mixed $grades
	 */
	private function setGrades() {
		$grades = $this->datasource->executeQuery("SELECT id_voto, alunno, voto FROM rb_voti WHERE id_verifica = ".$this->id);
		if ($grades) {
			foreach ($grades as $grade) {
				$this->students[$grade['alunno']]['grade']['gid'] = $grade['id_voto'];
				$this->students[$grade['alunno']]['grade']['grade'] = $grade['voto'];
			}
		}
	}

	/**
	 * @param mixed $students
	 */
	private function loadStudents() {
		$students = array();
		$res = $this->datasource->executeQuery("SELECT id_alunno, cognome, nome FROM rb_alunni WHERE id_classe = ".$this->class->get_ID()." ORDER BY cognome, nome");
		foreach ($res as $st) {
			$students[$st['id_alunno']] = array ("stid" => $st['id_alunno'], "name" => $st['cognome']." ".$st['nome'], "grade" => array("gid" => 0, "grade" => 0));
		}
		$this->students = $students;
	}

	/**
	 * @return mixed
	 */
	public function getStudents() {
		return $this->students;
	}

	/**
	 * @param boolean $evaluated
	 */
	public function setEvaluated($evaluated) {
		$this->evaluated = $evaluated;
	}

	/**
	 * @return boolean
	 */
	public function isEvaluated() {
		return $this->evaluated;
	}

	public function testDateToString() {
		setlocale(LC_TIME, "it_IT.utf8");
		return strftime("%A %d %B %H:%M", strtotime($this->testDate));
	}

	public function getAverage() {
		$sum = $count = 0;
		reset($this->students);
		foreach ($this->students as $k => $st) {
			if ($st['grade']['gid'] != 0) {
				$sum += $st['grade']['grade'];
				$count++;
			}
		}
		if ($count == 0) {
			return "";
		}
		$avg = round(($sum/$count), 2);
		if ($this->subject->getId() == 26 || $this->subject->getId() == 30) {
			$religion_grades = \RBUtilities::getReligionGrades();
			return $religion_grades[\RBUtilities::convertReligionGrade($avg)];
		}
		return $avg;
	}

	public function getEvaluatedStudents() {
		$tot = 0;
		reset($this->students);
		foreach ($this->students as $k => $st) {
			if ($st['grade']['gid'] != 0 ) {
				//echo $st['grade']['gid']." != 0 => COUNT $count<br>";
				$tot += 1;
			}
		}
		return $tot;
	}

	private function loadFromDB() {
		$ts = $this->datasource->executeQuery("SELECT * FROM rb_verifiche WHERE id_verifica = ".$this->id);
		$rb = \RBUtilities::getInstance($this->datasource);
		if ($ts[0] != null) {
			$data = $ts[0];
			$this->teacher = $rb->loadUserFromUid($data['id_docente'], "simple_school");
			$this->class = $rb->loadClassFromClassID($data['id_classe']);
			$this->year = $rb->loadYearFromID($data['id_anno']);
			$this->testDate = $data['data_verifica'];
			$this->insertDate = $data['data_assegnazione'];
			$this->subject = $rb->loadSubjectFromID($data['id_materia']);
			$this->evaluated = ($data['valutata'] == 1) ? true : false;
			$this->type = $data['tipologia'];
			$this->description = $data['prova'];
			$this->topic = $data['argomento'];
			$this->annotation = $data['note'];
			$this->activityId = $data['id_attivita'];
		}
	}

	public function save() {
		if ($this->id == 0) {
			// insert
			/*
			$query_activity = "INSERT INTO rb_impegni (data_assegnazione, data_inizio, data_fine, docente, classe, anno, materia, descrizione, note, tipo) VALUES (NOW(), '{$this->testDate}', '{{$this->testDate}}' + INTERVAL 1 HOUR, {$this->teacher->getUid()}, {$this->class->get_ID()}, {$this->year->get_ID()}, {$this->subject->GetId()}, '{$this->description}', '{$this->annotation}', '{$this->type}')";
			if($this->testDate > date("Y-m-d")) {
				$act_id = $this->datasource->executeUpdate($query_activity);
			}
			else {
				$act_id = "NULL";
			}
			*/
			$query_test = "INSERT INTO rb_verifiche (id_docente, id_classe, id_anno, data_verifica, data_assegnazione, id_materia, id_attivita, prova, argomento, note, tipologia) ";
			$query_test .= "VALUES ({$this->teacher->getUid()}, {$this->class->get_ID()}, {$this->year->get_ID()}, '{$this->testDate}', NOW(), {$this->subject->getId()}, NULL, '{$this->description}', '{$this->topic}', '{$this->annotation}', {$this->type})";
			$test_id = $this->datasource->executeUpdate($query_test);
		}
		else {
			// update
			$test_id = $this->datasource->executeUpdate("UPDATE rb_verifiche SET data_verifica = '{$this->testDate}', prova = '{$this->description}', argomento = '{$this->topic}', note = '{$this->annotation}', tipologia = {$this->type} WHERE id_verifica = ".$this->id);
			$this->datasource->executeUpdate("UPDATE rb_voti SET descrizione = '{$this->description}', argomento = '{$this->topic}', data_voto = '{$this->testDate}', tipologia = {$this->type} WHERE id_verifica = ".$_REQUEST['id_verifica']);
		}
		return $test_id;
	}

	public function delete($deleteGrades) {
		if ($deleteGrades) {
			 $this->datasource->executeUpdate("DELETE FROM rb_voti WHERE id_verifica = ".$this->id);
		}
		else {
			$this->datasource->executeUpdate("UPDATE rb_voti SET id_verifica = NULL WHERE id_verifica = ".$this->id);
		}
		$this->datasource->executeUpdate("DELETE FROM rb_obiettivi_verifica WHERE id_verifica = ".$this->id);
		$this->datasource->executeUpdate("DELETE FROM rb_verifiche WHERE id_verifica = ".$this->id);
	}

	public function saveLearningObjectives() {
		if ($this->id != 0) {
			$this->datasource->executeUpdate("DELETE FROM rb_obiettivi_verifica WHERE id_verifica = ".$this->id);
			$this->datasource->executeUpdate("DELETE FROM rb_voti_obiettivo USING rb_voti_obiettivo JOIN rb_voti WHERE rb_voti_obiettivo.id_voto = rb_voti.id_voto AND id_verifica = ".$this->id);
		}
		if ($this->learningObjectives != null) {
			$ins = "INSERT INTO rb_obiettivi_verifica (id_verifica, id_obiettivo) VALUES ";
			foreach ($this->learningObjectives as $lo) {
				$ins .= "({$this->id}, {$lo}),";
				$this->datasource->executeUpdate("INSERT INTO rb_voti_obiettivo (id_voto, obiettivo, voto) SELECT id_voto, {$lo}, voto FROM rb_voti WHERE id_verifica = ".$this->id);
			}
			$ins = substr($ins, 0, strlen($ins) - 1);
			$this->datasource->executeUpdate($ins);
		}
	}

	private function loadLearningObjectives() {
		$los = $this->datasource->executeQuery("SELECT id_obiettivo FROM rb_obiettivi_verifica WHERE id_verifica = ".$this->id);
		if ($los) {
			$this->learningObjectives = $los;
		}
		else {
			$this->learningObjectives = array();
		}
	}

	public function setGrade($grade, $gradeID, $stid) {
		if($gradeID != 0){
			// esiste gia' il voto: vado in update o delete
			try{
				$_grade = new \Grade($gradeID, null, $this->datasource);
				if($grade == 0){
					// alunno assente: delete
					$_grade->delete();
					$idv = 0;
				}
				else{
					// update
					$fields = array("voto", "id_verifica");
					$values = array($grade, $this->id);
					$chars = array(false, false);
					$_grade->updateFields($fields, $values, $chars);
				}
			} catch (MySQLException $ex){
				$response['status'] = "kosql";
				$response['message'] = $ex->getMessage();
				$response['query'] = $ex->getQuery();
				echo json_encode($response);
				exit;
			}
		}
		else{
			// nuovo voto
			if($grade == 0){
				// alunno assente: do_nothing()
			}
			else{
				$data = array();
				$data['voto'] = $grade;
				$data['id_voto'] = 0;
				$data['materia'] = $this->subject->getId();
				$data['docente'] = $this->teacher->getUid();
				$data['alunno'] = $stid;
				$data['data_voto'] = substr($this->testDate, 0, 10);
				$data['anno'] = $this->year->get_ID();
				$data['descrizione'] = $this->datasource->getSource()->real_escape_string($this->description);
				$data['tipologia'] = $this->type;
				$data['argomento'] = $this->datasource->getSource()->real_escape_string($this->topic);
				$data['note'] = $this->datasource->getSource()->real_escape_string($this->annotation);
				$data['privato'] = 0;
				$data['id_verifica'] = $this->id;
				try{
					$_grade = new \Grade(0, $data, $this->datasource);
					$gradeID = $_grade->save();
					if ($this->hasLearningObjectives()) {
						$los = array();
						foreach ($this->learningObjectives as $lo) {
							$los[$lo] = 0;
						}
						$_grade->setLearningObjectives($los);
					}
					$_grade->updateLearningObjectivesGrades($grade);
				} catch (MySQLException $ex){
					$response['status'] = "kosql";
					$response['message'] = $ex->getMessage();
					$response['query'] = $ex->getQuery();
					echo json_encode($response);
					exit;
				}
			}
		}
		//$this->loadStudents();
		$this->students[$stid]['grade']['gid'] = $gradeID;
		$this->students[$stid]['grade']['grade'] = $grade;

		return $gradeID;
	}

	/**
	 * @param array $learningObjectives
	 */
	public function setLearningObjectives($learningObjectives) {
		$this->learningObjectives = $learningObjectives;
	}

	public function hasLearningObjectives() {
		return $this->learningObjectives != null && count($this->learningObjectives) > 0;
	}

}
