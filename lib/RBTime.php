<?php

class RBTime{
	
	private $hours;
	private $minutes;
	private $seconds = 0;
	private $time;
	public static $RBTIME_LONG = 1;
	public static $RBTIME_SHORT = 2;
	
	public function __construct($h, $m, $s){
		$this->hours = $h;
		$this->minutes = $m;
		$this->seconds = $s;
		$this->time = $s + (60 * $m) + (3600 * $h);
	}
	
	public function setTime($t){
		$this->time = $t;
		$this->calculateData();
	}
	
	public function getTime(){
		return $this->time;
	}
	
	public function getHours(){
		return $this->hours;
	}
	
	public function getMinutes(){
		return $this->minutes;
	}
	
	public function getSeconds(){
		return $this->seconds;
	}
	
	public function calculateData(){
		$t = $this->time;
		$this->seconds = $t % 60;
		$t -= $this->seconds;
		$m = $t / 60;
		$this->minutes = $m % 60;
		$m -= $this->minutes;
		$this->hours = $m / 60;
	}
	
	public function calculateDistance(RBTime $rb){
		$a = null;
		$b = null;
		$x = $this->compare($rb);
		if($x == 0){
			return new RBTime(0, 0, 0);
		}
		else if($x == 1){
			$a = $this;
			$b = $rb;
		}
		else{
			$a = $rb;
			$b = $this;
		}
		$c = new RBTime(0, 0, 0);
		$t = $a->getTime() - $b->getTime();
		$c->setTime($t);
		return $c;
	}
	
	public function compare(RBTime $rb){
		if($this->time > $rb->getTime()){
			return 1;
		}
		else if($rb->getTime() > $this->time){
			return -1;
		}
		else{
			return 0;
		}
	}
	
	public function equal(RBTime $rb){
		return $this->compare($rb) == 0;	
	}
	
	public function toString($style = 2){
		$this->calculateData();
		$h = $this->hours;
		$m = $this->minutes;
		$s = $this->seconds;
		if ($h < 10){
			$h = "0".$h;
		}
		if($m < 10){
			$m = "0".$m;
		}
		if($s < 10){
			$s = "0".$s;
		}
		if ($style == RBTime::$RBTIME_LONG){
			return "{$h}:{$m}:{$s}";
		}
		else {
			return "{$h}:{$m}";
		}
			
	}
	
	public function add($time){
		$this->time += $time;
	}
	
	public function subtract($time){
		$this->time -= $time;
	}
	
}