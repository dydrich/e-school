<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 29/07/14
 * Time: 13.23
 */

namespace eschool;

require_once "Note.php";


class DisciplinaryNote extends Note {

	private $penalty;
	private $description;

	public function __construct($id, $data, \DataLoader $dl){
		$this->id = $id;
		if ($data != null) {
			$this->id = $data['id'];
			$this->teacher = $data['docente'];
			$this->class = $data['classe'];
			$this->student = $data['alunno'];
			$this->type = $data['tipo'];
			$this->year = $data['anno'];
			$this->date = $data['data'];
			$this->description = $data['descrizione'];
			$this->penalty = $data['sanzione'];
		}
		$this->datasource = $dl;
	}

	public function save() {
		if ($this->id == 0) {
			$statement = "INSERT INTO rb_note_disciplinari (docente, classe, alunno, anno, tipo, descrizione, data, sanzione) ";
			$statement .= "VALUES ({$this->teacher}, {$this->class}, ".field_null($this->student, false).", {$this->year}, {$this->type}, '{$this->description}', '{$this->date}', ".field_null($this->penalty, true).")";
		}
		else {
			$statement = "UPDATE rb_note_disciplinari SET tipo = {$this->type}, descrizione = '{$this->description}', data = '{$this->date}' WHERE id_nota = {$this->id}";
		}
		return $this->datasource->executeUpdate($statement);
	}

	public function delete() {
		return $this->datasource->executeUpdate("DELETE FROM rb_note_disciplinari WHERE id_nota = {$this->getId()}");
	}

	protected function loadFromDB() {
		$this->datasource->executeQuery("SELECT * FROM rb_note_disciplinari WHERE id_nota = ".$this->id);
	}

	/**
	 * @param mixed $penalty
	 */
	public function setPenalty($penalty) {
		$this->penalty = $penalty;
	}

	/**
	 * @return mixed
	 */
	public function getPenalty() {
		return $this->penalty;
	}
}
