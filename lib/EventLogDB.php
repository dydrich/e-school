<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 22/12/13
 * Time: 17.16
 */

namespace eschool;

require_once 'data_source.php';
require_once 'EventLog.php';

class EventLogDB extends EventLog{

	private $datasource;

	public function __construct($dt, $dl)
	{
		parent::__construct($dt);
		$this->datasource = $dl;
	}


	public function logFailedLogin()
	{
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$query = "INSERT INTO rb_log (data_ora, utente, tipo_evento, numeric1, text1, text2) VALUES (NOW(), 0, 2, {$this->data['area']}, '{$ip_address}', '{$this->data['username']}')";
		$this->datasource->executeUpdate($query);
	}

	public function logDeletedDocument()
	{
		$query = "INSERT INTO rb_log (data_ora, utente, tipo_evento, numeric1, numeric2, text1) VALUES (NOW(), {$_SESSION['__user__']->getUid()}, 1, '{$this->data['docId']}', '{$this->data['year']}', '{$this->data['prog']}')";
		$this->datasource->executeUpdate($query);
	}


} 