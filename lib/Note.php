<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 24/07/14
 * Time: 16.14
 */

namespace eschool;


abstract class Note {

	protected $id;
	protected $teacher;
	protected $class;
	protected $student;
	protected $type;
	protected $year;
	protected $date;

	protected $datasource;

	/**
	 * @param mixed $class
	 */
	public function setClass($class){
		$this->class = $class;
	}

	/**
	 * @return mixed
	 */
	public function getClass(){
		return $this->class;
	}

	/**
	 * @param mixed $datasource
	 */
	public function setDatasource($datasource){
		$this->datasource = $datasource;
	}

	/**
	 * @return mixed
	 */
	public function getDatasource(){
		return $this->datasource;
	}

	/**
	 * @param mixed $date
	 */
	public function setDate($date){
		$this->date = $date;
	}

	/**
	 * @return mixed
	 */
	public function getDate(){
		return $this->date;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id){
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * @param mixed $student
	 */
	public function setStudent($student){
		$this->student = $student;
	}

	/**
	 * @return mixed
	 */
	public function getStudent(){
		return $this->student;
	}

	/**
	 * @param mixed $teacher
	 */
	public function setTeacher($teacher){
		$this->teacher = $teacher;
	}

	/**
	 * @return mixed
	 */
	public function getTeacher(){
		return $this->teacher;
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
	public function getType(){
		return $this->type;
	}

	/**
	 * @param mixed $year
	 */
	public function setYear($year){
		$this->year = $year;
	}

	/**
	 * @return mixed
	 */
	public function getYear(){
		return $this->year;
	}

	abstract protected function loadFromDB();
	abstract public function save();
	abstract public function delete();
} 
