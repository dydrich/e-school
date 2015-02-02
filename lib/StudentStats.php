<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 23/07/14
 * Time: 11.57
 */

namespace eschool;

require_once "RBUtilities.php";


class StudentStats {

	private $datasource;
	private $student;
	private $year;

	public function __construct(\DataLoader $dl, $id, \SchoolYear $y){
		$this->datasource = $dl;
		$this->student = $id;
		$this->year = $y;
	}

	/**
	 * @param mixed $year
	 */
	public function setYear(\SchoolYear $year){
		$this->year = $year;
	}

	/**
	 * @return mixed
	 */
	public function getYear(){
		return $this->year;
	}

	public function getGradesAvg($subject, $type = null, $session = 0){
		$school_year = $this->getYear();
		$y = $school_year->getYear()->get_ID();
		$type_param = $session_param = "";
		if ($type != null) {
			$type_param = "AND tipologia = {$type}";
		}
		if ($session != 0) {
			$session_end = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
			/*

			if(date("Y-m-d") > $session_end){
				$session_param = "AND (data_voto  > '{$session_end}' AND data_voto <= NOW()) ";
			}
			else{
				$session_param = "AND data_voto <= '{$session_end}'";
			}
			*/
			if ($session == 1) {
				$session_param = "AND data_voto <= '{$session_end}'";
			}
			else {
				$session_param = "AND (data_voto  > '{$session_end}' AND data_voto <= NOW()) ";
			}
		}
		else {
			$session_param = "AND data_voto <= NOW()";
		}
		$sel = "SELECT COUNT(*) as count, ROUND(AVG(voto), 2) as avg FROM rb_voti WHERE alunno = {$this->student} AND anno = {$y} AND materia = {$subject} {$type_param} {$session_param} ";
		$res = $this->datasource->executeQuery($sel);
		//print_r($res);
		/*
		 * religion grades
		 */
		if ($subject == 30 || $subject == 26) {
			$religionGrades = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");
			$avg = \RBUtilities::convertReligionGrade($res[0]['avg']);
			$res[0]['avg'] = $religionGrades[$avg];
		}
		return array("count" => $res[0]['count'], "avg" => $res[0]['avg']);
	}
} 
