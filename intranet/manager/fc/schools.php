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

$sel_schools = "SELECT fc_scuole_provenienza.descrizione AS school, fc_scuole_provenienza.codice AS code, fc_scuole_provenienza.id_scuola AS id_sc, fc_classi_provenienza.id_classe AS id_cl, fc_classi_provenienza.descrizione AS cls FROM fc_scuole_provenienza LEFT JOIN fc_classi_provenienza ON fc_scuole_provenienza.id_scuola = fc_classi_provenienza.id_scuola WHERE fc_scuole_provenienza.id_scuola <> 6 ORDER BY fc_classi_provenienza.id_scuola, id_classe";
$res_schools = $db->execute($sel_schools);
$schools = array();
while($sc = $res_schools->fetch_assoc()){
	if(!isset($schools[$sc['id_sc']])){
		$schools[$sc['id_sc']] = array();
		array_push($schools[$sc['id_sc']], $sc['school']."#".$sc['code']);
		$schools[$sc['id_sc']][1] = array();
	}
	if($sc['id_cl'] != "")
		array_push($schools[$sc['id_sc']][1], array("school_id" => $sc['id_sc'], "school" => $sc['school'], "class_id" => $sc['id_cl'], "class" => $sc['cls']));
}
//print_r($schools);
include "schools.html.php";

?>