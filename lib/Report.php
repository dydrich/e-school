<?php

require_once 'Document.php';

class Report extends Document{
	
	private $session;
	private $id_pubb;
	private $student;
	private $parent;
	private $registerReading;
	
	public function __construct($file, $y, $fp, $sess, $id_pubb, $st){
		$this->id = 0;
		$this->file = $file;
		$this->year = $y;
		$this->owner = 0;
		$this->filePath = $fp;
		$this->id_pubb = $id_pubb;
		$this->student = $st;
		$this->deleteOnDownload = false;
		$this->filePath = "download/pagelle/";
		$this->area = "intranet";
		$this->session = $sess;
		if ($this->session == 1){
			$this->deleteOnDownload = true;
			$this->filePath = "tmp/";
		}
		$this->setDocumentType(8);
	}
	
	public function getRegisterReading(){
		return $this->registerReading;
	}
	
	public function setRegisterReading($update){
		$this->registerReading = $update;
	}
	
}