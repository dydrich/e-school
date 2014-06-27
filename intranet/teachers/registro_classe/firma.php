<?php

/**
    modifica la materia nella visualizazione del docente
    e nel database: il lato client usa Ajax per l'update
*/

require_once "../../../lib/start.php";

check_session();
check_permission(DOC_PERM);

$ora = $_POST['ora'];
$anno = $_SESSION['__current_year__']->get_ID();
$response = array("status" => "ok", "message" => "");

header("Content-type: application/json");

switch ($_POST['action']){
	case 'sign':
		$uid = $_SESSION['__user__']->getUid();
		$mat = $_POST['mat'];
		$id_registro = $_POST['id_reg'];
		$id_ora = $_POST['id_ora'];
		$update = false;
		if ($id_ora != 0){
			$update = true;
		}
		if ($update) {
			$statement = "UPDATE rb_reg_firme SET firma = NULL, docente = {$uid}, materia = {$mat} WHERE id = {$id_ora}";
		}
		else {
			$statement = "INSERT INTO rb_reg_firme (id_registro, ora, firma, docente, materia, anno) VALUES ({$id_registro}, {$ora}, NULL, {$uid}, {$mat}, {$anno})";
		}
		try{
			$begin = $db->execute("BEGIN");
			$hid = $db->executeUpdate($statement);
			$commit = $db->execute("COMMIT");
		} catch(MySQLException $ex){
			$rollback = $db->execute("ROLLBACK");
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		if ($id_ora == 0) {
			$id_ora = $hid;
		}
		$sel_m = "SELECT materia FROM rb_materie WHERE id_materia = $mat";
		$res_m = $db->executeQuery($sel_m);
		$m = $res_m->fetch_assoc();
		$response['subject'] = $m['materia'];
		$response['id_ora'] = $id_ora;
		$res = json_encode($response);
		echo $res;
		exit;
		break;
	case 'unsign':
		$id_registro = $_POST['id_reg'];
		$id_ora = $_POST['id_ora'];
		$statement = "UPDATE rb_reg_firme SET docente = NULL, materia = NULL, argomento = NULL WHERE id = {$id_ora} AND id_registro = {$id_registro}";
		try{
			$begin = $db->execute("BEGIN");
			$hid = $db->executeUpdate($statement);
			$commit = $db->execute("COMMIT");
		} catch(MySQLException $ex){
			$rollback = $db->execute("ROLLBACK");
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		$res = json_encode($response);
		echo $res;
		exit;
		break;
	case 'sign_compresence':
		$uid = $_SESSION['__user__']->getUid();
		$teacher = $_SESSION['__user__']->getFullName();
		$id_registro = $_POST['id_reg'];
		$id_ora = $_POST['id_ora'];
		$update = false;
		if ($id_ora != 0){
			$update = true;
		}
		if ($update) {
			$statement = "UPDATE rb_reg_firme SET docente_compresenza = {$uid} WHERE id = {$id_ora}";
		}
		else {
			$statement = "INSERT INTO rb_reg_firme (id_registro, ora, firma, docente, materia, anno, docente_compresenza) VALUES ({$id_registro}, {$ora}, NULL, NULL, NULL, {$anno}, {$uid})";
		}
		try{
			$begin = $db->execute("BEGIN");
			$hid = $db->executeUpdate($statement);
			$commit = $db->execute("COMMIT");
		} catch(MySQLException $ex){
			$rollback = $db->execute("ROLLBACK");
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		if ($id_ora == 0) {
			$id_ora = $hid;
		}
		$response['teacher'] = $teacher;
		$response['id_ora'] = $id_ora;
		$res = json_encode($response);
		echo $res;
		exit;
		break;
	case 'sign_support':
		$uid = $_SESSION['__user__']->getUid();
		$teacher = $_SESSION['__user__']->getFullName();
		$id_registro = $_POST['id_reg'];
		$id_ora = $_POST['id_ora'];
		$cls = $_SESSION['__classe__']->get_ID();
		$day = $_REQUEST['day'];
		$update = false;
		if ($id_ora != 0){
			$sel_row = "SELECT COUNT(*) FROM rb_reg_firme_sostegno WHERE id_ora = {$id_ora} AND docente = {$uid} AND ora = {$ora}";
			$rows = $db->executeCount($sel_row);
			if ($rows > 0){
				$update = true;
			}
		}
		else {
			$statement = "INSERT INTO rb_reg_firme (id_registro, ora, firma, docente, materia, anno, docente_compresenza) VALUES ({$id_registro}, {$ora}, NULL, NULL, NULL, {$anno}, NULL)";
			try{
				$begin = $db->execute("BEGIN");
				$hid = $db->executeUpdate($statement);
				$commit = $db->execute("COMMIT");
			} catch(MySQLException $ex){
				$rollback = $db->execute("ROLLBACK");
				$response['status'] = "kosql";
				$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
				$response['message'] = "Errore nella registrazione dei dati";
				$res = json_encode($response);
				echo $res;
				exit;
			}
			$id_ora = $hid;
		}
		if ($update) {
			$statement = "UPDATE rb_reg_firme_sostegno SET docente = {$uid} WHERE id_ora = {$id_ora}";
		}
		else {
			$statement = "INSERT INTO rb_reg_firme_sostegno (id_registro, ora, docente, classe, anno, id_ora) VALUES ({$id_registro}, {$ora}, {$uid}, {$cls}, {$anno}, {$id_ora})";
		}
		try{
			$begin = $db->execute("BEGIN");
			$hid = $db->executeUpdate($statement);
			$commit = $db->execute("COMMIT");
		} catch(MySQLException $ex){
			$rollback = $db->execute("ROLLBACK");
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		if ($id_ora == 0) {
			$id_ora = $hid;
		}
		
		/* 
		 * gestione link ad attivita
		 */
		$sel_ass = "SELECT id_alunno, cognome, nome FROM rb_alunni, rb_assegnazione_sostegno WHERE alunno = id_alunno AND anno = {$_SESSION['__current_year__']->get_ID()} AND classe = {$cls} AND docente = {$uid}";
		$res_ass = $db->execute($sel_ass);
		$alunni = array();
		while ($row = $res_ass->fetch_assoc()){
			$sel_activities = "SELECT rb_attivita_sostegno.id, data FROM rb_attivita_sostegno, rb_assegnazione_sostegno WHERE rb_attivita_sostegno.alunno = rb_assegnazione_sostegno.alunno AND rb_assegnazione_sostegno.anno = {$_SESSION['__current_year__']->get_ID()} AND docente = {$uid} AND classe = {$cls} AND rb_attivita_sostegno.alunno = {$row['id_alunno']} AND data = '{$day}'";
			$res_activities = $db->execute($sel_activities);
			if ($res_activities->num_rows > 0){
				$r = $res_activities->fetch_assoc();
				$row['attivita'] = $r;
			}
			else {
				$row['attivita'] = array("id" => 0, "data" => '');
			}
			$alunni[] = $row;
		}
		
		/*
		 * fine gestione link ad attivita
		 */
		
		
		$response['teacher'] = $teacher;
		$response['id_ora'] = $id_ora;
		$response['alunni'] = $alunni;
		$response['day'] = $day;
		$res = json_encode($response);
		echo $res;
		exit;
		break;
	case 'unsign_compresence':
		$id_registro = $_POST['id_reg'];
		$id_ora = $_POST['id_ora'];
		$statement = "UPDATE rb_reg_firme SET docente_compresenza = NULL, materia_compresenza = NULL WHERE id = {$id_ora} AND id_registro = {$id_registro}";
		try{
			$begin = $db->execute("BEGIN");
			$hid = $db->executeUpdate($statement);
			$commit = $db->execute("COMMIT");
		} catch(MySQLException $ex){
			$rollback = $db->execute("ROLLBACK");
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		$res = json_encode($response);
		echo $res;
		exit;
		break;
	case 'unsign_support':
		$id_registro = $_POST['id_reg'];
		$id_ora = $_POST['id_ora'];
		$uid = $_SESSION['__user__']->getUid();
		$cls = $_SESSION['__classe__']->get_ID();
		$statement = "DELETE FROM rb_reg_firme_sostegno WHERE id_ora = {$id_ora} AND id_registro = {$id_registro} AND ora = {$ora} AND docente = {$uid} AND classe = {$cls}";
		try{
			$begin = $db->execute("BEGIN");
			$db->executeUpdate($statement);
			$commit = $db->execute("COMMIT");
		} catch(MySQLException $ex){
			$rollback = $db->execute("ROLLBACK");
			$response['status'] = "kosql";
			$response['dbg_message'] = "Query: {$ex->getQuery()} ------ Errore: {$ex->getMessage()}";
			$response['message'] = "Errore nella registrazione dei dati";
			$res = json_encode($response);
			echo $res;
			exit;
		}
		$res = json_encode($response);
		echo $res;
		exit;
		break;
}
