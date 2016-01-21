<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 18/01/16
 * Time: 19.42
 */

namespace eschool;

require_once 'ClassroomReservationBook.php';


class Classroom
{
	private $id;
	private $name;
	private $venue;
	private $datasource;
	private $reservationBook;

	public function __construct($id, $db, $name = null, $venue = null, $reserBook = null) {
		if ($db instanceof \MySQLDataLoader) {
			$this->datasource = $db;
		}
		else {
			$this->datasource = new \MySQLDataLoader($db);
		}

		if ($id != 0 && ($name == null || $venue == null)) {
			$this->loadClassroom($id);
		}
		else {
			$this->id = $id;
			$this->name = $name;
			$this->venue = $venue;
		}

		if ($reserBook instanceof ClassroomReservationBook) {
			$this->reservationBook = $reserBook;
		}
		else if ($reserBook) {
			$this->loadReservationBook();
		}
	}

	private function loadClassroom($id) {
		$data = $this->datasource->executeQuery("SELECT * FROM rb_aule_speciali WHERE id_lab = {$id}");
		$this->id = $id;
		$this->venue = $data[0]['sede'];
		$this->name = $data[0]['nome'];
	}

	private function loadReservationBook() {
		$data = $this->datasource->executeQuery("SELECT * FROM rb_prenotazioni_aule WHERE anno_scolastico = {$_SESSION['__current_year__']->get_ID()} AND aula = {$this->id} ORDER BY giorno DESC, ora");
		$this->reservationBook = new ClassroomReservationBook();
		if ($data) {
			foreach ($data as $item) {
				$this->reservationBook->addReservation($item['giorno'], $item['ora'], $item['classe'], $item['docente']);
			}
		}
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param mixed $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getVenue() {
		return $this->venue;
	}

	/**
	 * @param mixed $venue
	 */
	public function setVenue($venue) {
		$this->venue = $venue;
	}

	public function insert() {
		$statement = "INSERT INTO rb_aule_speciali (nome, sede) VALUES ('{$this->name}', {$this->venue})";
		$this->id = $this->datasource->executeUpdate($statement);
		return $this->id;
	}

	public function update() {
		$statement = "UPDATE rb_aule_speciali set nome = '{$this->name}', sede = {$this->venue} WHERE id_lab = {$this->id}";
		return $this->datasource->executeUpdate($statement);
	}

	public function delete() {
		$statement = "DELETE FROM rb_aule_speciali WHERE id_lab = {$this->id}";
		return $this->datasource->executeUpdate($statement);
	}

	/**
	 * @return ClassroomReservationBook
	 */
	public function getReservationBook() {
		return $this->reservationBook;
	}

	/**
	 * @param mixed $reservationBook
	 */
	public function setReservationBook(ClassroomReservationBook $reservationBook) {
		$this->reservationBook = $reservationBook;
	}

	public function isAvailable($date, $hour) {

	}

	public function getDay($day) {
		if ($this->reservationBook instanceof ClassroomReservationBook) {
			return $this->reservationBook->getDay($day);
		}
		else {
			return false;
		}
	}

	public function reserve ($day, $hour, $class, $teacher) {
		$id = $this->datasource->executeUpdate("INSERT INTO rb_prenotazioni_aule (anno_scolastico, aula, docente, giorno, classe, ora) VALUES ({$_SESSION['__current_year__']->get_ID()}, {$this->id}, {$teacher}, '{$day}', {$class}, {$hour})");
		$this->reservationBook->addReservation($day, $hour, $class, $teacher);
		return $id;
	}

	public function deleteReservation($day, $hour) {
		$this->datasource->executeUpdate("DELETE FROM rb_prenotazioni_aule  WHERE aula = {$this->id} AND giorno = '{$day}' AND ora = {$hour}");
		$this->reservationBook->deleteReservation($day, $hour);
	}

}
