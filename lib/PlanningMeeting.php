<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 09/09/14
 * Time: 17.03
 */

namespace eschool;


class PlanningMeeting {
	private $rid;
	private $module;
	private $meetingDate;
	private $startTime;
	private $endTime;
	private $absents;
	private $subjects;
	private $datasource;
	private $other;

	public function __construct($rid, $data, \MySQLDataLoader $dl) {
		$this->rid = $rid;
		$this->datasource = $dl;
		if ($data != null) {
			$this->module = $data['id_modulo'];
			$this->meetingDate = $data['data'];
			$this->startTime = $data['ora_inizio'];
			$this->endTime = $data['ora_termine'];
			$this->absents = $data['assenti'];
			$this->other = $data['altro'];
			$this->subjects = array();
			$this->subjects["italiano"] = $data['italiano'];
			$this->subjects["matematica"] = $data['matematica'];
			$this->subjects["religione"] = $data['religione'];
			$this->subjects["immagine"] = $data['immagine'];
			$this->subjects["inglese"] = $data['inglese'];
			$this->subjects["storia"] = $data['storia'];
			$this->subjects["geografia"] = $data['geografia'];
			$this->subjects["motoria"] = $data['motoria'];
			$this->subjects["scienze"] = $data['scienze'];
			$this->subjects["musica"] = $data['musica'];
			$this->subjects["tecnologia"] = $data['tecnologia'];
		}
	}

	/**
	 * @param mixed $absents
	 */
	public function setAbsents($absents) {
		$this->absents = $absents;
	}

	/**
	 * @return mixed
	 */
	public function getAbsents() {
		return $this->absents;
	}

	/**
	 * @param mixed $endTime
	 */
	public function setEndTime($endTime) {
		$this->endTime = $endTime;
	}

	/**
	 * @return mixed
	 */
	public function getEndTime() {
		return $this->endTime;
	}

	/**
	 * @param mixed $meetingDate
	 */
	public function setMeetingDate($meetingDate) {
		$this->meetingDate = $meetingDate;
	}

	/**
	 * @return mixed
	 */
	public function getMeetingDate() {
		return $this->meetingDate;
	}

	/**
	 * @param mixed $module
	 */
	public function setModule($module) {
		$this->module = $module;
	}

	/**
	 * @return mixed
	 */
	public function getModule() {
		return $this->module;
	}

	/**
	 * @param mixed $rid
	 */
	public function setRid($rid) {
		$this->rid = $rid;
	}

	/**
	 * @return mixed
	 */
	public function getRid() {
		return $this->rid;
	}

	/**
	 * @param mixed $startTime
	 */
	public function setStartTime($startTime) {
		$this->startTime = $startTime;
	}

	/**
	 * @return mixed
	 */
	public function getStartTime() {
		return $this->startTime;
	}

	/**
	 * @param mixed $subjects
	 */
	public function setSubjects($subjects) {
		$this->subjects = $subjects;
	}

	/**
	 * @return mixed
	 */
	public function getSubjects() {
		return $this->subjects;
	}

	/**
	 * @param mixed $other
	 */
	public function setOther($other) {
		$this->other = $other;
	}

	/**
	 * @return mixed
	 */
	public function getOther() {
		return $this->other;
	}

	public function getSubject($sub) {
		return $this->subjects[$sub];
	}

	public function insert() {
		$this->rid = $this->datasource->executeUpdate("
		INSERT INTO rb_riunioni_programmazione (`id_modulo`, `data`, `ora_inizio`, `ora_termine`, `assenti`, `italiano`, `matematica`, `religione`, `immagine`, `inglese`, `storia`, `geografia`, `motoria`, `scienze`, `musica`, `tecnologia`, `altro`)
		VALUES ({$this->module}, '{$this->meetingDate}', '{$this->startTime}', '{$this->endTime}', ".field_null($this->absents, true).", ".field_null($this->subjects['italiano'], true).", ".field_null($this->subjects['matematica'], true).", ".field_null($this->subjects['religione'], true).", ".field_null($this->subjects['immagine'], true).",".field_null($this->subjects['inglese'], true).",".field_null($this->subjects['storia'], true).",".field_null($this->subjects['geografia'], true).",".field_null($this->subjects['motoria'], true).",".field_null($this->subjects['scienze'], true).",".field_null($this->subjects['tecnologia'], true).",".field_null($this->subjects['musica'], true).",".field_null($this->other, true).")"
		);
		return $this->rid;
	}

	public function update() {
		$this->datasource->executeUpdate("UPDATE rb_riunioni_programmazione SET
		data = '{$this->meetingDate}',
		ora_inizio = '{$this->startTime}',
		ora_termine = '{$this->endTime}',
		assenti = ".field_null($this->absents, true).",
		italiano = ".field_null($this->subjects['italiano'], true).",
		matematica = ".field_null($this->subjects['matematica'], true).",
		religione = ".field_null($this->subjects['religione'], true).",
		immagine = ".field_null($this->subjects['immagine'], true).",
		inglese = ".field_null($this->subjects['inglese'], true).",
		storia = ".field_null($this->subjects['storia'], true).",
		geografia = ".field_null($this->subjects['geografia'], true).",
		motoria = ".field_null($this->subjects['motoria'], true).",
		scienze = ".field_null($this->subjects['scienze'], true).",
		musica = ".field_null($this->subjects['musica'], true).",
		tecnologia = ".field_null($this->subjects['tecnologia'], true).",
		altro = ".field_null($this->other, true)."
		WHERE id_riunione = {$this->rid}
		");
	}

	public function delete() {
		$this->datasource->executeUpdate("DELETE FROM rb_riunioni_programmazione WHERE id_riunione = ".$this->rid);
	}

} 
