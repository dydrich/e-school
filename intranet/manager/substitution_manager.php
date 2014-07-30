<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 26/07/14
 * Time: 19.18
 */
require_once "../../lib/start.php";
require_once "../../lib/data_source.php";
require_once "../../lib/Substitution.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(SEG_GROUP|DSG_PERM|DIR_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$school = $_SESSION['__school_level__'][$_SESSION['__school_order__']];

$action = $_REQUEST['action'];
if ($action == 'new' || $action == "update") {
	$data = array();
	$data['id_docente_assente'] = $_REQUEST['docID'];
	$data['id_supplente'] = $_REQUEST['supID'];
	$data['data_inizio_supplenza'] = format_date($_REQUEST['inizio'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
	$data['data_fine_supplenza'] = format_date($_REQUEST['fine'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
	$cls = $_REQUEST['classi'];
	$res_cls = $db->executeQuery("SELECT id_classe, anno_corso, sezione FROM rb_classi WHERE id_classe IN (".implode(",", $cls).")");
	$classi = array();
	while ($row = $res_cls->fetch_assoc()) {
		$classi[$row['id_classe']] = $row['anno_corso'].$row['sezione'];
	}
	$data['classi'] = $classi;
}

switch($action){
	case "new":
		$sub = new \eschool\Substitution(0, $_SESSION['__school_order__'], $_SESSION['__current_year__']->get_ID(), $db, $data);
		$sub->save();
		echo json_encode($response);
		exit;
		break;
	case "update":
		$sub = new \eschool\Substitution($_REQUEST['id'], $_SESSION['__school_order__'], $_SESSION['__current_year__']->get_ID(), $db, $data);
		$sub->save();
		echo json_encode($response);
		exit;
		break;
	case "delete":
		$data = null;
		$sub = new \eschool\Substitution($_REQUEST['id'], $_SESSION['__school_order__'], $_SESSION['__current_year__']->get_ID(), $db, $data);
		$sub->delete();
		$response['message'] = "Supplenza cancellata";
		echo json_encode($response);
		exit;
		break;
	case "get":
		$id_supp = $_REQUEST['id'];
		$sel_supplenza = "SELECT rb_supplenze.*, classe, anno_corso, sezione FROM rb_supplenze, rb_classi_supplenza, rb_classi WHERE rb_supplenze.id_supplenza = rb_classi_supplenza.id_supplenza AND rb_supplenze.id_supplenza = {$id_supp} AND id_classe = classe ";
		$res_supplenza = $db->execute($sel_supplenza);
		$supplenza = array();
		$ids = 0;
		while ($row = $res_supplenza->fetch_assoc()) {
			if ($ids != $row['id_supplenza']) {
				$supplenza['id'] = $id;
				$supplenza['data_inizio_supplenza'] = $row['data_inizio_supplenza'];
				$supplenza['data_fine_supplenza'] = $row['data_fine_supplenza'];
				$supplenza['id_docente_assente'] = $row['id_docente_assente'];
				$supplenza['id_supplente'] = $row['id_supplente'];
				$supplenza['tit'] = $db->executeCount("SELECT CONCAT_WS(' ', cognome, nome) FROM rb_utenti WHERE uid = {$row['id_docente_assente']}");
				$supplenza['sup'] = $db->executeCount("SELECT CONCAT_WS(' ', cognome, nome) FROM rb_utenti WHERE uid = {$row['id_supplente']}");
				$supplenza['days'] = $db->executeCount("SELECT COUNT(id_reg) FROM rb_reg_classi WHERE id_classe = {$row['classe']} AND (data BETWEEN '{$row['data_inizio_supplenza']}' AND '{$row['data_fine_supplenza']}')");
				$supplenza['classi'] = array();
			}
			$ids = $row['id_supplenza'];
			$supplenza['classi'][$row['classe']] = $row['anno_corso'].$row['sezione'];
		}
		$response['data'] = $supplenza;
		echo json_encode($response);
		exit;
		break;
	default:

		break;
}
