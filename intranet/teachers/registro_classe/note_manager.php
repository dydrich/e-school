<?php

require_once "../../../lib/start.php";

check_session();
check_permission(DOC_PERM);

header("Content-type: text/plain");

$teacher = $_SESSION['__user__']->getUid();
$class = $_SESSION['__classe__']->get_ID();
$stid = $_REQUEST['stid'] ? $_REQUEST['stid'] : "NULL";
$type = $_REQUEST['type'];
$desc = utf8_encode($db->real_escape_string($_REQUEST['desc']));
$date = format_date($_REQUEST['_date'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");

switch($_REQUEST['action']){
	case "insert":
		$statement = "INSERT INTO rb_note_disciplinari (docente, classe, alunno, tipo, descrizione, data, anno) VALUES ($teacher, $class, $stid, $type, '$desc', '$date', {$_SESSION['__current_year__']->get_ID()})";
		try{
			$id = $db->executeUpdate($statement);
		} catch (MySQLException $ex){
			print "ko|".$ex->getMessage()."|".$ex->getQuery();
			exit;
		}
		break;
	case "update":
		$id_nota = $_REQUEST['id_nota'];
		$statement = "UPDATE rb_note_disciplinari SET tipo = $type, descrizione = '$desc', data = '$date' WHERE id_nota = $id_nota";
		try{
			$db->executeUpdate($statement);
		} catch (MySQLException $ex){
			print "ko|".$ex->getMessage()."|".$ex->getQuery();
			exit;
		}
		$id = $id_nota;
		break;
	case "delete":
		$id_nota = $_REQUEST['id_nota'];
		$statement = "DELETE FROM rb_note_disciplinari WHERE id_nota = $id_nota";
		try{
			$db->executeUpdate($statement);
		} catch (MySQLException $ex){
			print "ko|".$ex->getMessage()."|".$ex->getQuery();
			exit;
		}
		$id = $id_nota;
		break;
}
$_SESSION['query'] = $statement;

print "ok|$id";
exit;

?>
