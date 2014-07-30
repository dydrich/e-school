<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 26/07/14
 * Time: 19.03
 */

namespace eschool;

require_once "RBUtilities.php";
require_once "data_source.php";


class Substitution {

	private $id;
	private $school_order;
	private $year;
	private $startDate;
	private $endDate;
	private $lecturer;
	private $substitute;
	private $classes;
	private $datasource;

	public function __construct($id, $school_order, $year, $dl, $data){
		$this->id = $id;
		$this->school_order = $school_order;
		$this->year = $year;
		$this->datasource = new \MySQLDataLoader($dl);
		if ($data != null) {
			$this->startDate = $data['data_inizio_supplenza'];
			$this->endDate = $data['data_fine_supplenza'];
			$rb = \RBUtilities::getInstance($this->datasource);
			$this->lecturer = $rb->loadUserFromUid($data['id_docente_assente'], "school");
			$this->substitute = $rb->loadUserFromUid($data['id_supplente'], "simple_school");
			$this->classes = $data['classi'];
		}
	}

	/**
	 * @return mixed
	 */
	public function getClasses(){
		return $this->classes;
	}

	/**
	 * @return mixed
	 */
	public function getEndDate(){
		return $this->endDate;
	}

	/**
	 * @return mixed
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getLecturer(){
		return $this->lecturer;
	}

	/**
	 * @return mixed
	 */
	public function getSchoolOrder(){
		return $this->school_order;
	}

	/**
	 * @return mixed
	 */
	public function getStartDate(){
		return $this->startDate;
	}

	/**
	 * @return mixed
	 */
	public function getSubstitute(){
		return $this->substitute;
	}

	/**
	 * @return mixed
	 */
	public function getYear(){
		return $this->year;
	}

	/**
	 * @param mixed $classes
	 */
	public function setClasses($classes){
		$this->classes = $classes;
	}

	/**
	 * @param mixed $endDate
	 */
	public function setEndDate($endDate){
		$this->endDate = $endDate;
	}

	/**
	 * @param mixed $lecturer
	 */
	public function setLecturer($lecturer){
		$this->lecturer = $lecturer;
	}

	/**
	 * @param mixed $startDate
	 */
	public function setStartDate($startDate){
		$this->startDate = $startDate;
	}

	/**
	 * @param mixed $substitute
	 */
	public function setSubstitute($substitute){
		$this->substitute = $substitute;
	}

	public static function getInstance($id, \DataLoader $dl) {
		$sel_supplenza = "SELECT rb_supplenze.*, classe, anno_corso, sezione FROM rb_supplenze, rb_classi_supplenza, rb_classi WHERE rb_supplenze.id_supplenza = rb_classi_supplenza.id_supplenza AND rb_supplenze.id_supplenza = {$id} AND id_classe = classe ";
		$res_supplenza = $dl->execute($sel_supplenza);
		$supplenza = array();
		$ids = 0;
		foreach ($res_supplenza as $row) {
			if ($ids != $row['id_supplenza']) {
				$supplenza['id'] = $id;
				$supplenza['id_docente_assente'] = $row['id_docente_assente'];
				$supplenza['id_supplente'] = $row['id_supplente'];
				$supplenza['anno'] = $row['anno'];
				$supplenza['ordine_di_scuola'] = $row['ordine_di_scuola'];
				$supplenza['data_inizio_supplenza'] = $row['data_inizio_supplenza'];
				$supplenza['data_fine_supplenza'] = $row['data_fine_supplenza'];
				$supplenza['classi'] = array();
			}
			$ids = $row['id_supplenza'];
			$supplenza['classi'][$row['classe']] = $row['anno_corso'].$row['sezione'];
		}
		return new Substitution($id, $supplenza['ordine_di_scuola'], $supplenza['anno'], $dl->getSource(), $supplenza);
	}

	public function isOpen(){
		return $this->getEndDate() >= date("Y-m-d");
	}

	public function save() {
		if ($this->id == 0) {
			// insert
			$id = $this->datasource->executeUpdate("INSERT INTO rb_supplenze (id_supplente, id_docente_assente, ordine_di_scuola, data_inizio_supplenza, data_fine_supplenza, anno) VALUES ({$this->substitute->getUid()}, {$this->lecturer->getUid()}, {$this->school_order}, '{$this->startDate}', '{$this->endDate}', {$this->year})");
			$ins = "INSERT INTO rb_classi_supplenza (id_supplenza, classe) VALUES ";
			foreach ($this->classes as $k => $cl) {
				$ins .= "($id, $k),";
			}
			$ins = substr($ins, 0, strlen($ins) - 1);
			$this->datasource->executeUpdate($ins);
		}
		else {
			// update
			$id = $this->datasource->executeUpdate("UPDATE rb_supplenze SET id_supplente = {$this->substitute->getUid()}, id_docente_assente = {$this->lecturer->getUid()}, data_inizio_supplenza = '{$this->startDate}', data_fine_supplenza = '{$this->endDate}' WHERE id_supplenza = {$this->id}");
			$this->datasource->executeUpdate("DELETE FROM rb_classi_supplenza WHERE id_supplenza = {$this->id}");
			$ins = "INSERT INTO rb_classi_supplenza (id_supplenza, classe) VALUES ";
			foreach ($this->classes as $k => $cl) {
				$ins .= "({$this->id}, $k),";
			}
			$ins = substr($ins, 0, strlen($ins) - 1);
			$this->datasource->executeUpdate($ins);
		}
		return $id;
	}

	public function delete(){
		$this->datasource->executeUpdate("DELETE FROM rb_classi_supplenza WHERE id_supplenza = {$this->id}");
		$this->datasource->executeUpdate("DELETE FROM rb_supplenze WHERE id_supplenza = {$this->id}");
	}

} 
