<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 09/05/15
 * Time: 19.41
 */

class EventType {

	private $id;

	private $name;

	private $description;

	private $numericField1, $numericField2;

	private $textField1, $textField2;

	private $floatField1;

	private $datasource;

	public function __construct($id, MySQLDataLoader $datasource, $data) {
		$this->id = $id;
		$this->datasource = $datasource;
		if ($data != null) {
			$this->name = $data['tipo'];
			$this->description = $data['descrizione'];
			$this->numericField1 = $_REQUEST['numeric1'];
			$this->numericField2 = $_REQUEST['numeric2'];
			$this->textField1 = $_REQUEST['text1'];
			$this->textField2 = $_REQUEST['text2'];
			$this->floatField1 = $_REQUEST['float1'];
		}
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
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
	public function getName() {
		return $this->name;
	}

	/**
	 * @param mixed $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getDescription() {
		return $this->description;
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
	public function getNumericField1() {
		return $this->numericField1;
	}

	/**
	 * @param mixed $numericField1
	 */
	public function setNumericField1($numericField1) {
		$this->numericField1 = $numericField1;
	}

	/**
	 * @return mixed
	 */
	public function getNumericField2() {
		return $this->numericField2;
	}

	/**
	 * @param mixed $numericField2
	 */
	public function setNumericField2($numericField2) {
		$this->numericField2 = $numericField2;
	}

	/**
	 * @return mixed
	 */
	public function getTextField1() {
		return $this->textField1;
	}

	/**
	 * @param mixed $textField1
	 */
	public function setTextField1($textField1) {
		$this->textField1 = $textField1;
	}

	/**
	 * @return mixed
	 */
	public function getTextField2() {
		return $this->textField2;
	}

	/**
	 * @param mixed $textField2
	 */
	public function setTextField2($textField2) {
		$this->textField2 = $textField2;
	}

	/**
	 * @return mixed
	 */
	public function getFloatField1() {
		return $this->floatField1;
	}

	/**
	 * @param mixed $floatField1
	 */
	public function setFloatField1($floatField1) {
		$this->floatField1 = $floatField1;
	}

	/**
	 * @return MySQLDataLoader
	 */
	public function getDatasource() {
		return $this->datasource;
	}

	/**
	 * @param MySQLDataLoader $datasource
	 */
	public function setDatasource($datasource) {
		$this->datasource = $datasource;
	}

	public function delete() {
		$this->datasource->executeUpdate("DELETE FROM rb_tipievento_log WHERE id = ".$this->id);
	}

	public function insert() {
		$this->id = $this->datasource->executeUpdate("
			INSERT INTO rb_tipievento_log
			(tipo, descrizione, numeric1, numeric2, text1, text2, float1)
			VALUES (
			'{$this->name}',
			'{$this->description}',
			'{$this->numericField1}',
			'{$this->numericField2}',
			'{$this->textField1}',
			'{$this->textField2}',
			'{$this->floatField1}')");
	}

	public function update() {
		$this->datasource->executeUpdate("
			UPDATE rb_tipievento_log SET
			tipo 		= '{$this->name}',
			descrizione = '{$this->description}',
			numeric1 	= '{$this->numericField1}',
			numeric2 	= '{$this->numericField2}',
			text1 		= '{$this->textField1}',
			text2 		= '{$this->textField2}',
			float1 		= '{$this->floatField1}'
			WHERE id = {$this->id}
		");
	}

}
