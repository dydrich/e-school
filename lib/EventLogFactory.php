<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 22/12/13
 * Time: 18.55
 */

namespace eschool;

require_once 'EventLogDB.php';


class EventLogFactory {

	protected $data;
	protected $datasource;
	private static $instance;

	private function __construct($data, $datasource) {
		$this->data = $data;
		$this->datasource = $datasource;
	}

	public static function getInstance($data, $ds) {
		if(empty(self::$instance)){
			self::$instance = new EventLogFactory($data, $ds);
		}
		return self::$instance;
	}

	public function getEventLog() {
		return new EventLogDB($this->data, $this->datasource);
	}

} 
