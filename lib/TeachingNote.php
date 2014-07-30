<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 24/07/14
 * Time: 16.20
 */

namespace eschool;

require_once "Note.php";

class TeachingNote extends Note {

	private $subject;
	private $annotation;

	public function __construct($id, $data, \DataLoader $dl){
		$this->id = $id;
		if ($data != null) {
			$this->id = $data['id'];
			$this->teacher = $data['docente'];
			$this->class = $data['classe'];
			$this->student = $data['alunno'];
			$this->type = $data['tipo'];
			$this->subject = $data['materia'];
			$this->year = $data['anno'];
			$this->date = $data['data'];
			$this->annotation = $data['note'];
		}
		$this->datasource = $dl;
	}

	/**
	 * @param mixed $annotation
	 */
	public function setAnnotation($annotation){
		$this->annotation = $annotation;
	}

	/**
	 * @return mixed
	 */
	public function getAnnotation(){
		return $this->annotation;
	}

	/**
	 * @param mixed $subject
	 */
	public function setSubject($subject){
		$this->subject = $subject;
	}

	/**
	 * @return mixed
	 */
	public function getSubject(){
		return $this->subject;
	}

	protected function loadFromDB() {
		// TODO: Implement loadFromDB() method.
	}

	public function save(){
		if ($this->id == 0) {
			$statement = "INSERT INTO rb_note_didattiche (docente, classe, alunno, materia, anno, tipo, note, data) ";
			$statement .= "VALUES ({$this->getTeacher()}, {$this->getClass()}, {$this->getStudent()}, {$this->getSubject()}, {$this->getYear()}, {$this->getType()}, ".field_null($this->getAnnotation(), true).", '{$this->getDate()}')";
		}
		else {
			$statement = "UPDATE rb_note_didattiche SET tipo = {$this->getType()}, note = ".field_null($this->getAnnotation(), true).", data = '{$this->getDate()}' WHERE id_nota = {$this->getId()}";
		}
		return $this->datasource->executeUpdate($statement);
	}

	public function delete(){
		return $this->datasource->executeUpdate("DELETE FROM rb_note_didattiche WHERE id_nota = {$this->getId()}");
	}

} 
