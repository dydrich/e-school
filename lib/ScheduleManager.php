<?php

require_once "classes.php";
require_once "data_source.php";
require_once "RBUtilities.php";
require_once "RBTime.php";

class ScheduleManager{
	
	private $datasource 		= null;
	private $classes 			= array();
	private $yearID				= null;	
	
	public function __construct($ds, $yID){
		$this->yearID = $yID;
		if($ds instanceof MySQLDataLoader){
			$this->datasource = $ds;
		}
		else {
			$this->datasource = new MySQLDataLoader($ds);
		}
		$this->loadClasses();
	}
	
	public function deleteSchedule(){
		$this->datasource->executeUpdate("DELETE FROM rb_orario WHERE anno = {$this->yearID}");
	}
	
	public function deleteClassSchedule(Classe $class){
		$classID = $class->get_ID();
		$this->datasource->executeUpdate("DELETE FROM rb_orario WHERE anno = {$this->yearID} AND classe = {$classID}");
	}
	
	public function insertSchedule(){
		foreach ($this->classes as $cl){
			$this->insertClassSchedule($cl);
		}
	}
	
	public function insertClassSchedule(Classe $class){
		$module = $class->get_modulo_orario();
		$classID = $class->get_ID();
		for($i = 1; $i < 7; $i++){
			$day = $module->getDay($i);
			if($day){
				$starts = $day->getNumberOfHours();
				for ($x = 0; $x < $starts; $x++){
					$ora = $x + 1;
					$q = "INSERT INTO rb_orario (giorno, ora, classe, anno) VALUES ({$i}, {$ora}, {$classID}, {$this->yearID})";
					$this->datasource->executeUpdate($q);
				}
			}
		}
	}
	
	public function reinsertSchedule(){
		$this->deleteSchedule();
		$this->insertSchedule();
	}
	
	public function reinsertClassSchedule(Classe $class){
		$classID = $class->get_ID();
		$this->deleteClassSchedule($class);
		$this->insertClassSchedule($class);
	}
	
	public function loadClasses(){
		$query = "SELECT * FROM rb_classi, rb_sedi WHERE rb_classi.sede = id_sede";
		$_classes = $this->datasource->executeQuery($query);
		foreach($_classes as $_class){
			$this->classes[] = new Classe($_class, $this->datasource->getSource());
		}
	}
	
	public function setClasses($classes){
		$this->classes = $classes;
	}
	
	public function getClasses(){
		return $this->classes;
	}
}
