<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

header("Content-type: text/plain");

$teacher = $_SESSION['__user__']->getUid();
$class = $_SESSION['__classe__']->get_ID();
$subject = $_SESSION['__materia__'];
$year = $_SESSION['__current_year__']->get_ID();
$stid = $_REQUEST['stid'];
$type = $_REQUEST['type'];
$desc = utf8_encode($_REQUEST['desc']);
$date = format_date($_REQUEST['_date'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");

switch($_REQUEST['action']){
	case "insert":
		$statement = "INSERT INTO rb_note_didattiche (docente, classe, alunno, materia, anno, tipo, note, data) VALUES ($teacher, $class, $stid, $subject, $year, $type, '$desc', '$date')";
		try{
			$id = $db->executeUpdate($statement);
		} catch (MySQLException $ex){
			print "ko|".$ex->getMessage()."|".$ex->getQuery();
			exit;
		}
		break;
	case "update":
		$id_nota = $_REQUEST['id_nota'];
		$statement = "UPDATE rb_note_didattiche SET tipo = $type, note = '$desc', data = '$date' WHERE id_nota = $id_nota";
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
		$statement = "DELETE FROM rb_note_didattiche WHERE id_nota = $id_nota";
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
