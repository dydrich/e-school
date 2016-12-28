<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 22/02/14
 * Time: 19.23
 * get all classes
 */

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

$params = '';
if (isset($_REQUEST['school_level'])) {
	$params = ' AND ordine_di_scuola = '.$_REQUEST['school_level'];
}

$sel_classi = "SELECT id_classe, anno_corso, sezione, codice, nome 
			  FROM rb_classi, rb_tipologia_scuola, rb_sedi 
			  WHERE id_sede = sede 
			  AND rb_classi.ordine_di_scuola = id_tipo $params
			  ORDER BY sezione, anno_corso";
$res_classi = $db->executeQuery($sel_classi);
$classe = array();
while ($row = $res_classi->fetch_assoc()){
	$classi[] = array("id" => $row['id_classe'], "classe" => $row['anno_corso'].$row['sezione'], "sede" => $row['nome']);
}

$response = array("status" => "ok", "message" => "", "data" => $classi);

header("Content-type: application/json");
$res = json_encode($response);
echo $res;
exit;
