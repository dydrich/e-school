<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 27/07/14
 * Time: 15.48
 */

namespace eschool;


class Subject {

	private $id;
	private $description;

	public function __construct($id, $des) {
		$this->id = $id;
		$this->description = $des;
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
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

} 
