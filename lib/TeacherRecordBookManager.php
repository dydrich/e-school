<?php

ini_set("display_errors", "1");

require_once "FirstGradeTeacherRecordBook.php";
require_once "FirstGradeSupportTeacherRecordBook.php";
require_once "PrimarySchoolTeacherRecordBook.php";
require_once "PrimarySchoolSupportTeacherRecordBook.php";

class TeacherRecordBookManager{
	
	private $recordBook;
	private $teacher;
	private $datasource;
	private $path;
	private $year;
	private $pubblicationID;
	private $schoolYear;
	/*
	 * standard or support
	 */
	private $type;
	
	public function __construct(SchoolUserBean $teacher, $ds, $pt, $y, $school_year, $type = "standard"){
		$this->teacher = $teacher;
		$this->datasource = new MySQLDataLoader($ds);
		$this->path = $pt;
		$this->year = $y;
		$this->schoolYear = $school_year;
		$this->setPubblicationID();
		$this->type = $type;
		switch ($this->teacher->getSchoolOrder()){
			case 1:
				if ($this->type == "standard"){
					$this->recordBook = new FirstGradeTeacherRecordBook($this->teacher, $this->datasource, $this->path, $this->year, $this->pubblicationID, $this->schoolYear);
				}
				else {
					$this->recordBook = new FirstGradeSupportTeacherRecordBook($this->teacher, $this->datasource, $this->path, $this->year, $this->pubblicationID, $this->schoolYear);
				}
				break;
			case 2:
				if ($this->type == "standard"){
					$this->recordBook = new PrimarySchoolTeacherRecordBook($this->teacher, $this->datasource, $this->path, $this->year, $this->pubblicationID, $this->schoolYear);
				}
				else {
					$this->recordBook = new PrimarySchoolSupportTeacherRecordBook($this->teacher, $this->datasource, $this->path, $this->year, $this->pubblicationID, $this->schoolYear);
				}
				break;
		}
	}
	
	public function setPubblicationID(){
		$sel_idp = "SELECT id_pagella FROM rb_pubblicazione_pagelle WHERE anno = {$this->year->get_ID()} AND quadrimestre = 2";
		$this->pubblicationID = $this->datasource->executeCount($sel_idp);
	}
	
	public function createWholeRecordBook(){
		$classi = $this->recordBook->getRecordBooks();
		foreach ($classi as $cls => $book) {
			foreach ($book['subjects'] as $subId => $subject) {
				switch ($this->teacher->getSchoolOrder()){
					case 1:
						if ($this->type == "standard"){
							$this->recordBook = new FirstGradeTeacherRecordBook($this->teacher, $this->datasource, $this->path, $this->year, $this->pubblicationID, $this->schoolYear);
						}
						else {
							$this->recordBook = new FirstGradeSupportTeacherRecordBook($this->teacher, $this->datasource, $this->path, $this->year, $this->pubblicationID, $this->schoolYear);
						}
						break;
					case 2:
						if ($this->type == "standard"){
							$this->recordBook = new PrimarySchoolTeacherRecordBook($this->teacher, $this->datasource, $this->path, $this->year, $this->pubblicationID, $this->schoolYear);
						}
						else {
							$this->recordBook = new PrimarySchoolSupportTeacherRecordBook($this->teacher, $this->datasource, $this->path, $this->year, $this->pubblicationID, $this->schoolYear);
						}
						break;
				}
				$this->createRecordBook($cls, $subId);
			}
		}
	}

	/*
	 * on standard record book, value = subject ID
	 * on support teachers record book, value = student ID
	 */
	public function createRecordBook($cls, $value){
		$this->recordBook->setCls($cls);
		return $this->recordBook->createRecordBook($cls, $value);
	}
	
	public function getRecordBooks(){
		return $this->recordBook->getRecordBooks();
	}
}
