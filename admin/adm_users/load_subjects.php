<?php

require_once "../../lib/start.php";

check_session();
check_permission(ADM_PERM|APS_PERM|AMS_PERM|AIS_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "../";

header("Content-type: application/json");

$uid = $_POST['uid'];
if(!is_numeric($uid)){
	echo "ko;L'utente richiesto non esiste";
	exit;
}

$tip_sc = $db->executeCount("SELECT tipologia_scuola FROM rb_docenti WHERE id_docente = {$uid}");
$params = "";
if($tip_sc != 999){
	$params = "AND tipologia_scuola = {$tip_sc}";
}

$sel_m = "SELECT id_materia, rb_materie.materia FROM rb_materie WHERE idpadre IS NULL AND (id_materia <> 1) {$params} ORDER BY rb_materie.materia";
try{
	$res_m = $db->executeQuery($sel_m);
} catch(MYSQLException $ex){
	echo "kosql#".$ex->getQuery()."#".$ex->getMessage();
	exit;
}

$materie = array();
while($m = $res_m->fetch_assoc()){
	$materie[$m['id_materia']] = array("id" => $m['id_materia'], "materia" => $m['materia']);
}
$height = $res_m->num_rows * 20;

echo "ok#".json_encode($materie)."#".$height;
exit;