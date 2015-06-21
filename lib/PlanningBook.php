<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 20/06/15
 * Time: 19.26
 */

require_once "PlanningMeeting.php";
require_once 'PlanningBookPDF.php';

class PlanningBook {

	private $datasource;

	private $id;

	private $creationDateTime;

	private $classes;

	private $year;

	private $file;

	private $meetings;

	private $pdf;

	private $directory;

	function __construct(MySQLDataLoader $datasource, $id, $classes, AnnoScolastico $year, $meetings, $file, $creationDateTime) {
		$this->datasource = $datasource;
		$this->id = $id;
		$this->classes = $classes;
		$this->year = $year;
		$this->meetings = $meetings;
		$this->file = $file;
		$this->creationDateTime = $creationDateTime;
	}

	public function init() {
		$this->loadData();
		$this->checkPath();
		$this->checkFile();
		$this->pdf = new PlanningBookPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$this->pdf->init($this->year, $this->classes, $this->meetings, $this->directory.$this->file);
		$this->pdf->createPDF();
		$this->saveData();
	}

	private function checkPath() {
		$cls = array();
		foreach ($this->classes as $idc => $class) {
			$cls[] = $class['desc_cls'];
		}
		$f = implode("-", $cls);
		$path = $_SESSION['__path_to_root__']."download/registri/".$this->year->get_descrizione()."/scuola_primaria/programmazione/modulo".$f."/";
		$this->directory = $path;
		@mkdir($path, 0755, true);
	}

	private function checkFile() {
		if ($this->file == null) {
			$cls = array();
			foreach ($this->classes as $idc => $class) {
				$cls[] = $class['desc_cls'];
			}
			$this->file = "registro_programmazione_".$this->year->get_descrizione()."_".implode("-", $cls).".pdf";
		}
	}


	private function loadData() {
		if ($this->classes == null) {
			$this->classes = $this->datasource->executeQuery("SELECT rb_classi_modulo.classe, CONCAT(rb_classi.anno_corso, rb_classi.sezione) AS desc_cls FROM rb_classi_modulo, rb_classi WHERE id_classe = classe AND id_modulo = ".$this->id);
		}
		if ($this->meetings == null) {
			$meets = $this->datasource->executeQuery("SELECT * FROM rb_riunioni_programmazione WHERE id_modulo = ".$this->id);
			foreach ($meets as $meet) {
				$data = $meet;
				$planMeet = new \eschool\PlanningMeeting($meet['id_riunione'], $data, $this->datasource);
				$this->meetings[] = $planMeet;
			}
		}
	}

	private function saveData() {
		$count = $this->datasource->executeCount("SELECT COUNT(*) FROM rb_registri_programmazione WHERE modulo = ".$this->id);
		if ($count > 0) {
			$this->datasource->executeUpdate("UPDATE rb_registri_programmazione SET data_creazione = NOW()");
		}
		else {
			$this->datasource->executeUpdate("INSERT INTO rb_registri_programmazione (modulo, data_creazione, file) VALUES ({$this->id}, NOW(), '{$this->file}')");
		}
		$this->creationDateTime = $this->datasource->executeCount("SELECT data_creazione FROM rb_registri_programmazione WHERE modulo = ".$this->id);
	}

	/**
	 * @return MySQLDataLoader
	 */
	public function getDatasource() {
		return $this->datasource;
	}

	/**
	 * @param mixed $datasource
	 */
	public function setDatasource($datasource) {
		$this->datasource = $datasource;
	}

	/**
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed integer
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getCreationDateTime() {
		return $this->creationDateTime;
	}

	/**
	 * @param string $creationDateTime
	 */
	public function setCreationDateTime($creationDateTime) {
		$this->creationDateTime = $creationDateTime;
	}

	/**
	 * @return array
	 */
	public function getClasses() {
		return $this->classes;
	}

	/**
	 * @param array $classes
	 */
	public function setClasses($classes) {
		$this->classes = $classes;
	}

	/**
	 * @return mixed
	 */
	public function getYear() {
		return $this->year;
	}

	/**
	 * @param mixed $year
	 */
	public function setYear($year) {
		$this->year = $year;
	}

	/**
	 * @return string
	 */
	public function getFile() {
		return $this->file;
	}

	/**
	 * @return array of PlanningMeeting
	 */
	public function getMeetings() {
		return $this->meetings;
	}

	/**
	 * @param array $meetings
	 */
	public function setMeetings($meetings) {
		$this->meetings = $meetings;
	}

	/**
	 * @return mixed
	 */
	public function getDirectory() {
		return $this->directory;
	}

	/**
	 * @param mixed $directory
	 */
	public function setDirectory($directory) {
		$this->directory = $directory;
	}

}
