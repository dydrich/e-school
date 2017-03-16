<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 1/17/17
 * Time: 6:54 PM
 */
require_once "../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$response = array("status" => "ok", "message" => "Operazione completata");

$y = $_SESSION['__current_year__']->get_ID();

switch ($_POST['action']) {
	case 'admin':
		$presidente = $db->real_escape_string($_POST['presidente']);
		$vice = $_POST['vice'];
		$segretario = $_POST['segretario'];
		if ($_POST['record'] == 0) {
			$statement = "INSERT INTO rb_dati_amministrativi_esame (anno, presidente, vice, segretario) 
						  VALUES ({$y}, '{$presidente}', {$vice}, {$segretario})";
		}
		else {
			$statement = "UPDATE rb_dati_amministrativi_esame 
						  SET presidente = '{$presidente}', 
						  vice = {$vice}, 
						  segretario = {$segretario}
						  WHERE id = ".$_POST['record'];
		}
		try {
			$id = $db->executeUpdate($statement);
			$response['id'] = $id;
		} catch (MySQLException $ex) {
			$response['status'] = 'kosql';
			$response['message'] = "Si è verificato un errore di sistema";
			$response['dbg_message'] = $ex->getMessage();
			$response['sql'] = $ex->getQuery();
			$res = json_encode($response);
			echo $res;
			exit;
		}
		break;
}

$res = json_encode($response);
echo $res;
exit;
