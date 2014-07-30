<?php

abstract class DataLoader{

	abstract public function connect($source);
}

abstract class DBDataLoader extends DataLoader{

	protected $source;

	public function connect($source){
		$this->source = $source;
	}
	
	public function getSource(){
		return $this->source;
	}

	abstract public function execute($query);
	abstract public function executeUpdate($query);
	abstract public function executeCount($query);
	abstract public function executeQuery($query);
}

class MySQLDataLoader extends DBDataLoader{
	
	function __construct(MySQLConnection $s){
		$this->connect($s);
	}
	
	public function execute($query){
		return $this->executeQuery($query);
	}
	
	public function executeUpdate($query){
		return $this->source->executeUpdate($query);
	}
	
	public function executeCount($query){
		return $this->source->executeCount($query);
	}
	
	public function executeQuery($query){
		$rows = $this->source->executeQuery($query);
		if (!$rows || $rows->num_rows == 0){
			return false;
		}
		$records = array();
		$f_count = $rows->field_count;
		while($r = $rows->fetch_array()){
			if ($f_count == 1) {
				$records[] = $r[0];
			}
			else {
				$records[] = $r;
			}
		}
		return $records;
	}
	
	public function startTransaction(){
		$this->source->executeUpdate("BEGIN");
	}
	
	public function rollbackTransaction(){
		$this->source->executeUpdate("ROLLBACK");
	}
	
	public function commitTransaction(){
		$this->source->executeUpdate("COMMIT");
	}

	public function prepare($query){
		return $this->source->prepare($query);
	}
}
