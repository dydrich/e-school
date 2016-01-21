<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 18/01/16
 * Time: 19.41
 */

namespace eschool;

require_once 'Classroom.php';


class ClassroomReservationBook
{
	private $reservations;

	public function __construct() {
		$this->reservations = [];
	}

	/**
	 * @return array
	 */
	public function getReservations() {
		return $this->reservations;
	}

	/**
	 * @param mixed $reservations
	 */
	public function setReservations($reservations) {
		$this->reservations = $reservations;
	}

	public function addReservation($day, $hour, $cls, $teacher) {
		if (!isset($this->reservations[$day])) {
			$this->reservations[$day] = [];
		}
		if (!isset($this->reservations[$day][$hour])) {
			$this->reservations[$day][$hour] = ['class' => $cls, 'teacher' => $teacher];
		}
	}

	public function deleteReservation($day, $hour) {
		unset ($this->reservations[$day][$hour]);
	}

	public function checkAvailability($day, $hour) {
		if (isset($this->reservations[$day][$hour])) {
			return false;
		}
		return true;
	}

	public function getDay($day) {
		if (isset($this->reservations[$day])) {
			return $this->reservations[$day];
		}
		return false;
	}

}
