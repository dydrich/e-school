<?php

require_once "classes.php";
require_once "data_source.php";
require_once "SchoolYear.php";

class SchoolYearManager {
	
	private $datasource;
	private $year;
	
	public function __construct($ds){
		$this->datasource = new MySQLDataLoader($ds);
	}
	
	public function setYear(SchoolYear $schoolYear){
		$this->year = $schoolYear;
	}
	
	public function getYear(){
		return $this->year;
	}
	
	public function createNewYear($start, $end){
		$start_format = get_date_format($start);
		if($start_format == -1){
			return false;
		}
		else if($start_format == 1){
			$start = format_date($start, IT_DATE_STYLE, SQL_DATE_STYLE, "-");
		}
		
		$end_format = get_date_format($end);
		if($end_format == -1){
			return false;
		}
		else if($end_format == 1){
			$end = format_date($end, IT_DATE_STYLE, SQL_DATE_STYLE, "-");
		}
		
		$year_desc = "";
		list($y, $m, $d) = explode("-", $start);
		$year_desc = $y ."-". ($y + 1);
		$fyear = $y + 1;
		
		$q = "INSERT INTO rb_anni (data_inizio, data_fine, descrizione) VALUES ('{$start}', '{$end}', '{$year_desc}')";
		$new_year_id = $this->datasource->executeUpdate($q);
		$this->datasource->executeUpdate("UPDATE rb_config SET valore = '0' WHERE variabile = 'stato_avanzamento_nuove_classi'");
		// rb_dati_lezione table
		$this->datasource->executeUpdate("INSERT INTO rb_dati_lezione (id_anno, id_ordine_scuola) SELECT {$new_year_id}, id_tipo FROM rb_tipologia_scuola WHERE has_admin = 1");
		$this->datasource->executeUpdate("INSERT INTO rb_pubblicazione_pagelle (anno, quadrimestre, disponibili_docenti, disponibili_docenti_sp) VALUES ({$new_year_id}, 1, '{$fyear}-01-01', '{$fyear}-01-01')");
		$this->datasource->executeUpdate("INSERT INTO rb_pubblicazione_pagelle (anno, quadrimestre, disponibili_docenti, disponibili_docenti_sp) VALUES ({$new_year_id}, 2, '{$fyear}-06-01', '{$fyear}-06-01')");
		return $new_year_id;
	}
	
	public function updateBasicData($start, $end){
		$year_desc = "";
		list($y, $m, $d) = explode("-", $start);
		$year_desc = $y ."-". ($y + 1);
		$id = $this->year->getYear()->get_ID();
		$q = "UPDATE rb_anni SET data_inizio = '{$start}', data_fine = '{$end}', descrizione = '{$year_desc}' WHERE id_anno = {$id}";
		$this->datasource->executeUpdate($q);
		$this->year->getYear()->set_data_apertura($start);
		$this->year->getYear()->set_data_chiusura($end);
		$this->year->getYear()->set_descrizione($year_desc);
	}
	
	public function saveLessonsData($row){
		$s_date = $row['classes_start'];
		$e_date = $row['classes_end'];
		$sessions = $row['sessions'];
		$e_sess1 = $row['session1'];
		$e_sess2 = ($row['session2'] != "") ? $row['session2'] : format_date($this->getYear()->getYear()->get_data_chiusura(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
		$sql = "UPDATE rb_dati_lezione SET data_inizio_lezioni = '{$s_date}', data_termine_lezioni = '{$e_date}', sessioni = {$sessions}, ";
		$sql .= "data_fine_1_sessione = '{$e_sess1}', data_fine_2_sessione = '{$e_sess2}', vacanze = '{$row['holydays']}' WHERE id = {$this->getYear()->getID()}";
		$this->datasource->executeUpdate($sql);
		$this->year->setClassesStartDate($s_date);
		$this->year->setClassesEndDate($e_date);
		$this->year->setSessions($sessions);
		$this->year->setFirstSessionEndDate($e_sess1);
		$this->year->setSecondSessionEndDate($e_sess2);
		$days = explode(",", $row['holydays']);
		$this->year->setHolydays($days);
	}
	
	public function startTransaction(){
		return $this->datasource->startTransaction();
	}
	
	public function doRollback(){
		return $this->datasource->rollbackTransaction();
	}
	
	public function doCommit(){
		return $this->datasource->commitTransaction();
	}
	
	
	
}

?>
