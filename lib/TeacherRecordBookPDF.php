<?php

require_once "SchoolPDF.php";

abstract class TeacherRecordBookPDF extends SchoolPDF{

	protected $path = "download/registri";
	protected $teacher;
	protected $cls;
	protected $pubblicationId;
	protected $students;
	protected $_page;
	
	public function setPath($p){
		$this->path = $p;
	}
	
	public abstract function init($t, $c, $idp, $s, $a);

}