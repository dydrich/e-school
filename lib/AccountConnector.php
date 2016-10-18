<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 10/15/16
 * Time: 6:23 PM
 */

namespace eschool;


class AccountConnector
{
	private $datasource;

	/**
	 * AccountConnector constructor.
	 */
	public function __construct(\MySQLDataLoader $dl) {
		$this->datasource = $dl;
	}

	public function getAccountsToConnect() {
		$accounts = [];
		$query = "SELECT cognome, nome, count( * ) AS count
				  FROM rb_utenti, rb_docenti
				  WHERE id_docente = uid
				  GROUP BY cognome, nome
				  HAVING count > 1";
		$res = $this->datasource->executeQuery($query);
		if ($res && $res != null) {
			foreach ($res as $item) {
				$name = $item['cognome'] . " " . $item['nome'];
				$accounts[$name] = ['name' => $name, 'uids' => []];
				$uids = $this->datasource->executeQuery("SELECT uid, rb_materie.materia 
														FROM rb_utenti, rb_docenti, rb_materie 
														WHERE cognome = '{$item['cognome']}' 
														AND nome = '{$item['nome']}'
														AND id_docente = uid
														AND rb_docenti.materia = id_materia
														");

				foreach ($uids as $uid) {
					$accounts[$name]['uids'][$uid['uid']] = ['uid' => $uid['uid'], 'subject' => $uid['materia']];
				}
			}
		}
		return $accounts;
	}

	public function connect($uid1, $uid2) {
		$this->datasource->executeUpdate("INSERT INTO rb_account_collegati (id_base, id_collegato) VALUES ($uid1, $uid2) ");
	}

	public function disconnect($id) {
		$this->datasource->executeUpdate("DELETE FROM rb_account_collegati WHERE id = $id ");
	}

	public function getConnectedAccounts() {
		$accounts = [];
		$query = "SELECT id, id_base, id_collegato FROM rb_account_collegati WHERE anno IS NULL OR anno = ".$_SESSION['__current_year__']->get_ID();
		$res = $this->datasource->executeQuery($query);
		if ($res && $res != null) {
			foreach ($res as $item) {
				$accounts[$item['id']] = ['name' => '', 'uids' => []];
				$data = $this->datasource->executeQuery("SELECT CONCAT_WS(' ', cognome, nome) AS name, rb_materie.materia 
														FROM rb_utenti, rb_docenti, rb_materie 
														WHERE uid = {$item['id_base']}
														AND id_docente = uid
														AND rb_docenti.materia = id_materia
														");
				$accounts[$item['id']]['name'] = $data[0]['name'];
				$accounts[$item['id']]['uids'][$item['id_base']] = ['uid' => $item['id_base'], 'subject' => $data[0]['materia']];
				$sub2 = $this->datasource->executeCount("SELECT rb_materie.materia 
														FROM rb_utenti, rb_docenti, rb_materie 
														WHERE uid = {$item['id_collegato']}
														AND id_docente = uid
														AND rb_docenti.materia = id_materia
														");
				$accounts[$item['id']]['uids'][$item['id_collegato']] = ['uid' => $item['id_collegato'], 'subject' => $sub2];
			}
		}
		return $accounts;
	}

	public function checkAccountToConnect($u1, $u2) {
		$id = $this->datasource->executeCount("SELECT id FROM rb_account_collegati
												WHERE (id_base = $u1 AND id_collegato = $u2) 
												OR (id_base = $u2 AND id_collegato = $u1)");
		return $id != null;
	}
}