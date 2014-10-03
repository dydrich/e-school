<?php

require_once "RBTime.php";

class ScheduleModuleDay{
	
	private $day;
	private $enterTime;
	private $exitTime;
	private $canteenStart = null;
	private $canteenDuration = null;
	private $hourDuration;
	private $subtractCanteenTime;
	private $canteenHour;
	
	public function __construct($record, $scd = false){
		$this->subtractCanteenTime = $scd;
		$this->day = $record['giorno'];
		list($h, $m, $s) = explode(":", $record['ingresso']);
		$this->enterTime = new RBTime(intval($h), intval($m), intval($s));
		list($h, $m, $s) = explode(":", $record['uscita']);
		$this->exitTime = new RBTime(intval($h), intval($m), intval($s));
		$this->hourDuration = new RBTime(0, 0, 0);
		$this->hourDuration->setTime($record['durata_ora'] * 60);
		if($record['inizio_pausa'] != ""){
			list($h, $m, $s) = explode(":", $record['inizio_pausa']);
			$this->canteenStart = new RBTime(intval($h), intval($m), intval($s));
			$this->canteenDuration = new RBTime(0, 0, 0); 
			$this->canteenDuration->setTime($record['durata_pausa'] * 60);
			
		}
	}
	
	public function getDay(){
		return $this->day;
	}
	
	public function setEnterTime(RBTime $t){
		$this->enterTime = $t;
	}
	
	public function getEnterTime(){
		return $this->enterTime;
	}
	
	public function setExitTime(RBTime $t){
		$this->exitTime = $t;
	}
	
	public function getExitTime(){
		return $this->exitTime;
	}
	
	public function hasCanteen(){
		return ($this->canteenStart != null && $this->canteenStart->getTime() != 0);
	}
	
	public function setCanteenStart(RBTime $cs){
		$this->canteenStart = $cs;
	}
	
	public function getCanteenStart(){
		return $this->canteenStart;
	}
	
	public function setCanteenDuration(RBTime $cd){
		$this->canteenDuration = $cd;
	}
	
	public function getCanteenDuration(){
		return $this->canteenDuration;
	}
	
	public function getClassDuration(){
		$ret = $this->exitTime->getTime() - $this->enterTime->getTime();
		if(($this->canteenDuration != null) && $this->subtractCanteenTime) $ret -= $this->getCanteenDuration()->getTime();
		$rb = new RBTime(0, 0, 0);
		$rb->setTime($ret);
		return $rb;
	}
	
	public function getNumberOfHours(){
		$a = $this->getClassDuration()->getTime();
		$duration = $this->hourDuration->getTime();
		return $a / $duration;
	}
	
	public function getHourDuration(){
		return $this->hourDuration;
	}
	
	public function getLessonsStartTime(){
		$h = $this->getNumberOfHours();
		//echo $h;
		$starts = array();
		for ($i = 0; $i < $h; $i++){
			$starts[$i+1] = new RBTime(0, 0, 0);
			$starts[$i+1]->setTime($this->enterTime->getTime());
		}
		$hour = $this->enterTime->getTime() + 3600;
		$x = 2;
		if($this->hasCanteen() && $this->subtractCanteenTime){
			while ($hour < $this->canteenStart->getTime()){
				//echo "Ciclo $x<br />";
				$starts[$x]->setTime($starts[$x - 1]->getTime());
				$starts[$x]->add($this->hourDuration->getTime());
				$hour += $this->hourDuration->getTime();
				$x++;
			}
			$starts[$x]->setTime($this->canteenStart->getTime());
			$starts[$x]->add($this->canteenDuration->getTime());
			$x++;
			while($x <= $h){
				$starts[$x]->setTime($starts[$x - 1]->getTime());
				$starts[$x]->add($this->hourDuration->getTime());
				$x++;
			}
		}
		else{
			$x = 2;
			while($x <= $h){
				$starts[$x]->setTime($starts[$x - 1]->getTime());
				$starts[$x]->add($this->hourDuration->getTime());
				$x++;
			}
		}
		
		return $starts;
	}

	public function getCanteenHour() {
		$starts = $this->getLessonsStartTime();
		$start = $this->canteenStart;
		$x = 1;
		foreach ($starts as $tm) {
			if ($start->equal($tm)) {
				return $x;
			}
			$x++;
		}
	}
	
}
