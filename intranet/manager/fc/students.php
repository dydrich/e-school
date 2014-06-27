<?php

include "../../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(DIR_PERM|DSG_PERM);

$perms = ($_SESSION['__user__']->getPerms()) ? $_SESSION['__user__']->getPerms() : $_SESSION['__perms__'];
//$nome = ($_SESSION['__user__']) ? $_SESSION['__user__']->getFullName() : $_SESSION['__fname__']." ".$_SESSION['__lname__'];

if(DIR_PERM&$perms)
		$_SESSION['__role__'] = "Dirigente scolastico";
	else
		$_SESSION['__role__'] = "DSGA";
	
$query_params = "";
$order = "cognome, nome";
if(isset($_REQUEST['order'])){
	switch ($_REQUEST['order']){
		case "from":
			$order = "fc_alunni.classe_provenienza, cognome, nome";
			break;
		case "rip":
			$order = "ripetente DESC, cognome, nome";
			break;
		case "sex":
			$order = "sesso, cognome, nome";
			break;
		case "grade":
			$order = "voto DESC, cognome, nome";
			break;
		case "cls":
			$order = "fc_alunni.id_classe, cognome, nome";
			break;
		case "h":
			$order = "H DESC, cognome, nome";
			break;
		default:
			$order = "cognome, nome";
		break;
	}
}
if(isset($_REQUEST['q'])){
	switch($_REQUEST['q']){
		case "assigned":
			$query_params = "AND fc_alunni.id_classe IS NOT NULL";
			break;
		case "not_assigned":
			$query_params = "AND fc_alunni.id_classe IS NULL";
			break;
		default:
			$query_params = "";
			break;
	}
}
	
$sel_students = "SELECT id_alunno, CONCAT_WS(' ', cognome, nome) AS name, ripetente, H, sesso, voto, CONCAT_WS('. ', diagnosi_h, note) AS note, fc_scuole_provenienza.id_scuola AS school, fc_alunni.id_classe, classe_provenienza, CONCAT_WS(', ', fc_scuole_provenienza.codice, fc_classi_provenienza.descrizione) AS class_from FROM fc_alunni, fc_classi_provenienza, fc_scuole_provenienza WHERE fc_alunni.classe_provenienza = fc_classi_provenienza.id_classe AND fc_classi_provenienza.id_scuola = fc_scuole_provenienza.id_scuola $query_params ORDER BY $order";
try{
	$res_students = $db->executeQuery($sel_students);
} catch(MySQLException $ex){
	$ex->redirect();
}
$n_std = $res_students->num_rows;

/*
 * let's match colors and classes
 */
$sel_cls = "SELECT * FROM fc_classi ORDER BY descrizione";
$res_cls = $db->executeQuery($sel_cls);
$classes_and_colors = array();
$x = 1;
while($cls = $res_cls->fetch_assoc()){
	$classes_and_colors[$cls['id_classe']] = array("id" => $cls['id_classe'], "name" => $cls['descrizione'], "color" =>$_SESSION['__colors__'][$x]['color']);
	$x++;
}
/*
$sel_classes_from = "SELECT id_classe, id_scuola FROM fc_classi_provenienza WHERE id_scuola <> 5";
$res_classes_from = $db->executeQuery($sel_classes_from);
$colors_from = array();
$x = 1;
while($class_from = $res_classes_from->fetch_assoc()){
	$colors_from[$class_from['id_classe']] = $_SESSION['__colors__'][$x]['color'];
	$x++;
}
*/

$sel_cls = "SELECT * FROM fc_classi ORDER BY descrizione";
$res_cls = $db->executeQuery($sel_cls);

include "students.html.php";

?>