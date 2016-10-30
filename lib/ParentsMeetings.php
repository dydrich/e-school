<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 10/30/16
 * Time: 10:03 AM
 */

namespace eschool;


class ParentsMeetings{
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
	}

	/**
	 * @return mixed
	 */
	public function getSchoolOrder() {
		return $this->schoolOrder;
	}

	/**
	 * @param mixed $schoolOrder
	 * @return ParentsMeetings
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
	 * @return ParentsMeetings
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

	public function addTeacherMeeting() {

	}
}