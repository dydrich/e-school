<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 10/30/16
 * Time: 10:03 AM
 */

namespace eschool;

require_once "start.php";


class ParentsMeetingsManager{
	private $schoolOrder;
	private $datasource;
	private $schoolMeetings;
	private $teachersMeetings;

	/**
	 * ParentsMeetings constructor.
	 * @param $schoolOrder
	 * @param $datasource
	 */
	public function __construct($schoolOrder, \MySQLDataLoader $datasource) {
		$this->schoolOrder = $schoolOrder;
		$this->datasource = $datasource;
		$this->loadSchoolMeetings();
	}

	/**
	 * @return mixed
	 */
	public function getSchoolOrder() {
		return $this->schoolOrder;
	}

	/**
	 * @param mixed $schoolOrder
	 * @return ParentsMeetingsManager
	 */
	public function setSchoolOrder($schoolOrder) {
		$this->schoolOrder = $schoolOrder;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDatasource() {
		return $this->datasource;
	}

	/**
	 * @param mixed $datasource
	 * @return ParentsMeetingsManager
	 */
	public function setDatasource($datasource) {
		$this->datasource = $datasource;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getSchoolMeetings() {
		return $this->schoolMeetings;
	}

	/**
	 * @param mixed $schoolMeetings
	 */
	public function setSchoolMeetings($schoolMeetings) {
		$this->schoolMeetings = $schoolMeetings;
	}

	/**
	 * @return mixed
	 */
	public function getTeachersMeetings() {
		return $this->teachersMeetings;
	}

	/**
	 * @param mixed $teachersMeetings
	 */
	public function setTeachersMeetings($teachersMeetings) {
		$this->teachersMeetings = $teachersMeetings;
	}

	public function addMeeting($date) {
		$this->datasource->executeUpdate("INSERT INTO rb_colloqui_periodici (anno, data) VALUES ({$_SESSION['__current_year__']->get_ID()}, '{$date}')");
	}

	public function deleteMeeting($id) {
		$this->datasource->executeUpdate("DELETE FROM rb_colloqui_periodici WHERE id = $id");
	}

	private function loadSchoolMeetings() {
		$months = [
			0 => ['num' => 9, 'desc' => 'Settembre'],
			1 => ['num' => 10, 'desc' => 'Ottobre'],
			2 => ['num' => 11, 'desc' => 'Novembre'],
			3 => ['num' => 12, 'desc' => 'Dicembre'],
			4 => ['num' => 1, 'desc' => 'Gennaio'],
			5 => ['num' => 2, 'desc' => 'Febbraio'],
			6 => ['num' => 3, 'desc' => 'Marzo'],
			7 => ['num' => 4, 'desc' => 'Aprile'],
			8 => ['num' => 5, 'desc' => 'Maggio'],
			9 => ['num' => 6, 'desc' => 'Giugno']
		];

		$sel_coll = "SELECT id, data, MONTH(data) AS month FROM rb_colloqui_periodici WHERE anno = ".$_SESSION['__current_year__']->get_ID()." AND ordine_scuola = 1 ORDER BY data";
		$res_coll = $this->datasource->executeQuery($sel_coll);
		$colloqui = [];
		if (count($res_coll) > 0) {
			foreach ($res_coll as $row) {
				if (!isset($colloqui[$row['month']])) {
					$colloqui[$row['month']] = [];
				}
				$colloqui[$row['month']][] = $row;
			}
		}
		$this->schoolMeetings = $colloqui;
	}

	/**
	 * @param int $teacher
	 * @param int $numberOfMeetings
	 * @return mixed
	 */
	public function getNextTeacherMeetings($teacher, $numberOfMeetings) {
		$sel_data = "SELECT valore FROM rb_parametri_utente WHERE id_parametro = 4 AND id_utente = $teacher";
		$res_data = $this->datasource->executeCount($sel_data);
		if($res_data && $res_data != null) {
			$r = explode(";", $res_data);
			$data['day'] = $r[0];
			$data['hour'] = $r[1];
			$data['mandatory'] = $r[2];
			$data['max'] = 0;
			if ($r[2] == 1 && isset($r[3])) {
				$data['max'] = $r[3];
			}
			/*
		 * misura la distanza dal lunedi`
		 */
			$d = $data['day'] - 1;
			$today = new \DateTime();

			$colloqui = $this->schoolMeetings;
			$teachers_meetings = [];
			foreach ($colloqui as $item) {
				foreach ($item as $row) {
					$date = new \DateTime($row['data']);
					$my_date = new \DateTime($row['data']);
					$my_date->add(new \DateInterval('P'.$d.'D'));
					if($my_date > $today) {
						$row['data'] = $my_date->format("Y-m-d");
						$teachers_meetings[] = $row;
					}
				}
			}
			if (count($teachers_meetings) > $numberOfMeetings) {
				$teachers_meetings = array_slice($teachers_meetings, 0, $numberOfMeetings);
			}
			$return_data = ['settings' => $data, 'meetings' => $teachers_meetings];
			return $return_data;
		}

		return null;
	}

	public function bookAMeeting($date, $teacher, $parent) {
		$this->datasource->executeUpdate("INSERT INTO rb_prenotazioni_colloqui (genitore, docente, data) VALUES ($parent, $teacher, '$date')");
	}

	public function deleteBooking($date, $teacher, $parent) {
		$this->datasource->executeUpdate("DELETE FROM rb_prenotazioni_colloqui WHERE genitore = $parent AND docente = $teacher AND data = '$date'");
	}
}