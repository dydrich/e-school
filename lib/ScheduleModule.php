<?php

require_once "ScheduleModuleDay.php";
require_once "data_source.php";

class ScheduleModule{
	
	private $ID;
	private $days = array();
	private $datasource;
	private $hasCanteen = false;
	private $duration;
	private $noLessonDays = array();
	/*
	 * if true subtract canteen time from duration
	 */
	private $substractCanteenTime;
	
	public function __construct($ds, $id, $scd = false){
		$this->ID = $id;
		if($ds instanceof MySQLDataLoader){
			$this->datasource = $ds;
		}
		else {
			$this->datasource = new MySQLDataLoader($ds);
		}
		$this->duration = new RBTime(0, 0, 0);
		$this->substractCanteenTime = $scd;
		$this->loadDays();
	}
	
	private function loadDays(){
		$q = "SELECT * FROM rb_giorni_modulo WHERE id_modulo = {$this->ID}";
		$days = $this->datasource->executeQuery($q);
		$time = 0;
		$days_in_week = array(0, 1, 2, 3, 4, 5, 6);
		if ($days && count($days) > 0){
			foreach ($days as $day){
				unset($days_in_week[$day['giorno']]);
				$this->days[$day['giorno']] = new ScheduleModuleDay($day);
				$time += $this->days[$day['giorno']]->getClassDuration()->getTime();
				if($day['inizio_pausa'] != ""){
					$this->hasCanteen = true;
				}
			}
		}
		$this->noLessonDays = $days_in_week;
		$this->duration->setTime($time);
	}
	
	public function canteenAreEquals(){
		$days = array();
		$keys = array();
		foreach ($this->days as $k => $d){
			if($d instanceof ScheduleModuleDay){
				if($d->getCanteenStart() != ""){
					$days[] = $d;
					$keys[] = $k;
				}
			}
		}
		if(count($days) > 0){
			$canteen_start = $days[0]->getCanteenStart();
			$canteen_duration = $days[0]->getCanteenDuration();
		}
		foreach ($days as $d){
			if ($d->getCanteenStart() != $canteen_start || $d->getCanteenDuration() != $canteen_duration){
				return false;
			}
		}
		return join(",", $keys);
	}
	
	public function getDays(){
		return $this->days;
	}
	
	public function getNumberOfDays(){
		return count($this->days);
	}
	
	public function getCanteenStart(){
		if($keys = $this->canteenAreEquals()){
			$k = substr($keys, 0, 1);
			return $this->getDay($k)->getCanteenStart();
		}
	}
	
	public function getCanteenDuration(){
		if($keys = $this->canteenAreEquals()){
			$k = substr($keys, 0, 1);
			return $this->getDay($k)->getCanteenDuration();
		}
	}
	
	public function getDay($d){
		if (isset($this->days[$d])){
			return $this->days[$d];
		}
		else {
			return null;
		}
	}
	
	public function getID(){
		return $this->ID;
	}
	
	public function hasCanteen(){
		return $this->hasCanteen;
	}
	
	public function getClassDuration(){
		return $this->duration;
	}
	
	public function getNoLessonDays(){
		return $this->noLessonDays;
	}
	
	public function getLessonStartTimes(){
		$starts = array();
		foreach ($this->days as $k => $d){
			if($d instanceof ScheduleModuleDay){
				$a = $d->getEnterTime();
				$number_of_hours = $d->getNumberOfHours();
				$hour_duration = $d->getHourDuration()->getTime();
				$day = $d->getDay();
				$starts[$day][1] = $a->toString(RBTime::$RBTIME_SHORT);
				while($a->getTime() <= $d->getExitTime()->getTime()){
					$a->add($hour_duration);
					$starts[$day][] = $a->toString(RBTime::$RBTIME_SHORT);
				}
			}
		}
		return $starts;
	}
	
	public function getNumberOfHours(){
		$hours = 0;
		foreach ($this->days as $k => $d){
			if($d instanceof ScheduleModuleDay){
				$hours += $d->getNumberOfHours();
			}
		}
		return $hours;
	}
	
}
