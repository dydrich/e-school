<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 22/12/13
 * Time: 18.30
 */

namespace eschool;


abstract class EventLog {

	protected $data;

	public function __construct($dt){
		$this->data = $dt;
	}

	abstract function logFailedLogin();

	abstract function logDeletedDocument();

	abstract function logUpdatedEndOfYearGrade();

	abstract function logUpdatedGrade();
} 
