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

$class_id = $_REQUEST['class_id'];

// they come from...
$sel_from = "SELECT fc_classi_provenienza.id_classe, CONCAT_WS(', ', fc_scuole_provenienza.descrizione, fc_classi_provenienza.descrizione) AS class_from, fc_classi_provenienza.id_scuola FROM fc_classi_provenienza, fc_scuole_provenienza WHERE fc_scuole_provenienza.id_scuola = fc_classi_provenienza.id_scuola AND fc_classi_provenienza.id_scuola <> 5 ORDER BY id_scuola, id_classe";
$res_from = $db->execute($sel_from);

include "stud_filter.html.php";

?>