<?php

require_once 'AnnoScolastico.php';

class SchoolYear{
	private $ID;
	
	private $year;
	private $schoolOrder;
	
	private $sessions = 0;
	private $firstSessionEndDate  = null;
	private $secondSessionEndDate = null;
	
	private $classesStartDate     = null;
	private $classesEndDate       = null;
	
	private $holydays = array();
	
	public function __construct(AnnoScolastico $y){
		$this->year = $y; 
	}
	
	public function setID($id){
		$this->ID = $id;
	}
	
	public function getID(){
		return $this->ID;
	}
	
	public function setYear($y){
		$this->year = $y;
	}
	
	public function getYear(){
		return $this->year;
	}
	
	public function setSchoolOrder($s){
		$this->schoolOrder = $s;
	}
	
	public function getSchoolOrder(){
		return $this->schoolOrder;
	}
	
	public function setSessions($s){
		if(!is_numeric($s)){
			return false;
		}
		$this->sessions = $s;
	}
	
	public function getSessions(){
		return $this->sessions;
	}
	
	public function setFirstSessionEndDate($d){
		$this->firstSessionEndDate = $d;
	}
	
	public function getFirstSessionEndDate(){
		return format_date($this->firstSessionEndDate, SQL_DATE_STYLE, IT_DATE_STYLE, "/");
	}
	
	public function setSecondSessionEndDate($d){
		$this->secondSessionEndDate = $d;
	}
	
	public function getSecondSessionEndDate(){
		if($this->secondSessionEndDate != null){
			return format_date($this->secondSessionEndDate, SQL_DATE_STYLE, IT_DATE_STYLE, "/");
		}
		return false;
	}
	
	public function setClassesStartDate($d){
		$this->classesStartDate = $d;
	}
	
	public function getClassesStartDate(){
		return $this->classesStartDate;
	}
	
	public function setClassesEndDate($d){
		$this->classesEndDate = $d;
	}
	
	public function getClassesEndDate(){
		return $this->classesEndDate;
	}
	
	public function setHolydays($days){
		if(is_array($days))
			$this->holydays = $days;
		else
			$this->holydays = explode(",", $days);
	}
	
	public function getHolydays (){
		return $this->holydays;
	}
	
	public function getHolydaysToString(){
		return join(",", $this->holydays);
	}
	
	public function getCurrentSession(){
		$current = 0;
		$today = date("Y-m-d");
		if($today <= $this->firstSessionEndDate){
			$current = 1;
		}
		else if($this->sessions == 3){
			if($today <= $this->secondSessionEndDate){
				$current = 2;
			}
			else {
				$current = 3;
			}
		}
		else if($this->sessions == 2){
			$current = 2;
		}
		return $current;
	}
	
	public function currentSessionToString(){
		$label = "quadrimestre";
		$current = $this->getCurrentSession();
		if($this->sessions == 3){
			$label = "trimestre";
		}
		return $current . " " .$label;
	}
	
	
	
}

?>