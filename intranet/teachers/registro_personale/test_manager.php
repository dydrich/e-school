<?php

require_once "../../../lib/start.php";

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

header("Content-type: text/plain");

if($_REQUEST['do'] != "delete"){
	list($date_from, $time_from) = explode(" ", $_REQUEST['date_time']);
	$date_from = format_date($date_from, IT_DATE_STYLE, SQL_DATE_STYLE, "-")." ".$time_from;
	$teacher = $_SESSION['__user__']->getUid();
	$year = $_SESSION['__current_year__']->get_ID();
	$subj = $_SESSION['__materia__'];
	$class = $_SESSION['__classe__']->get_ID();
	$test = $_REQUEST['test'];
	$subject = $db->real_escape_string($_REQUEST['subject']);
	$notes = $db->real_escape_string($_REQUEST['notes']);
	$tipo = $_REQUEST['tipo'];
	$act_id = 0;
}

switch($_REQUEST['do']){
	case "insert":
		$query_activity = "INSERT INTO rb_impegni (data_assegnazione, data_inizio, data_fine, docente, classe, anno, materia, descrizione, note, tipo) VALUES (NOW(), '$date_from', '$date_from' + INTERVAL 1 HOUR, $teacher, $class, $year, $subj, '$test', '$notes', '1')";
		try{
			$db->executeUpdate("BEGIN");
			if($date_from > date("Y-m-d"))
				$act_id = $db->executeUpdate($query_activity);
			else
				$act_id = "NULL";
			$query_test = "INSERT INTO rb_verifiche (id_docente, id_classe, id_anno, data_verifica, data_assegnazione, id_materia, id_attivita, prova, argomento, note, tipologia) VALUES ({$teacher}, {$class}, {$year}, '{$date_from}', NOW(), {$subj}, {$act_id}, '{$test}', '{$subject}', '{$notes}', {$tipo})";
			$test_id = $db->executeUpdate($query_test);
			$db->executeUpdate("COMMIT");
		} catch (MySQLException $ex){
			$db->executeUpdate("ROLLBACK");
			print ("ko|".$ex->getMessage()."|".$ex->getQuery());
			exit;
		}
		break;
	case "update":
		$query_test = "UPDATE rb_verifiche SET data_verifica = '$date_from', prova = '$test', argomento = '$subject', note = '$notes', tipologia = {$tipo} WHERE id_verifica = ".$_REQUEST['id_verifica'];
		$upd_grades = "UPDATE rb_voti SET descrizione = '$test', argomento = '$subject', data_voto = '$date_from', tipologia = {$tipo} WHERE id_verifica = ".$_REQUEST['id_verifica'];
		try{
			$db->executeUpdate("BEGIN");
			$db->executeUpdate($query_test);
			$db->executeUpdate($upd_grades);
			$db->executeUpdate("COMMIT");
		} catch (MySQLException $ex){
			$db->executeUpdate("ROLLBACK");
			print ("ko|".$ex->getMessage()."|".$ex->getQuery());
			exit;
		}
		break;
	case "delete":
		//$query_grades = "DELETE FROM rb_voti WHERE id_verifica = ".$_REQUEST['id_verifica'];
		$query_test = "DELETE FROM rb_verifiche WHERE id_verifica = ".$_REQUEST['id_verifica'];
		try{
			$db->executeUpdate("BEGIN");
			//$db->executeUpdate($query_grades);
			$db->executeUpdate($query_test);
			$db->executeUpdate("COMMIT");
		} catch (MySQLException $ex){
			$db->executeUpdate("ROLLBACK");
			print ("ko|".$ex->getMessage()."|".$ex->getQuery());
			exit;
		}
		break;
}

print "ok|".$_REQUEST['do'];
exit;

?>