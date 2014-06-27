<?php

require_once "TeacherRecordBookPDF.php";

abstract class TeacherRecordBook{
	
	protected $teacher;
	protected $RecordBooks;
	protected $datasource;
	protected $pdf;
	protected $path;
	protected $year;
	protected $cls;
	protected $pubbID;
	protected $schoolYear;

	public function __construct(SchoolUserBean $teacher, MySQLDataLoader $ds, $pt, AnnoScolastico $y, $c, SchoolYear $sy){
		$this->teacher = $teacher;
		$this->datasource = $ds;
		$this->path = $pt;
		$this->year = $y;
		$this->cls = $c;
		$this->schoolYear = $sy;
	}
	
	abstract public function createWholeRecordBook();
	abstract public function createRecordBook($cls, $subject);
	abstract public function getRecordBook($cls, $subject);
}