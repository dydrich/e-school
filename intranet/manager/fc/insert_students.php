<?php

include "../../../lib/start.php";

ini_set("display_errors", "1");

check_session();
check_permission(DIR_PERM|DSG_PERM);

$perms = ($_SESSION['__user__']->getPerms()) ? $_SESSION['__user__']->getPerms() : $_SESSION['__perms__'];

if(DIR_PERM&$perms)
	$_SESSION['__role__'] = "Dirigente scolastico";
else
	$_SESSION['__role__'] = "DSGA";

$sel_classes_from = "SELECT id_classe, CONCAT_WS(', ', fc_scuole_provenienza.descrizione, fc_classi_provenienza.descrizione) AS description FROM fc_classi_provenienza, fc_scuole_provenienza WHERE fc_classi_provenienza.id_scuola = fc_scuole_provenienza.id_scuola ORDER BY fc_scuole_provenienza.id_scuola, fc_classi_provenienza.descrizione ";
$res_classes_from = $db->executeQuery($sel_classes_from);

include "insert_students.html.php";

?>