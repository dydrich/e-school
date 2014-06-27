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

$sel = "SELECT * FROM nuove_classi ORDER BY sezione, classe";
$result = $db->execute($sel);
$classi = array();
while($classe = $result->fetch_assoc()){
	if(!isset($classi[$classe['sezione']])){
		$classi[$classe['sezione']] = array();
	}
	$classi[$classe['sezione']][$classe['classe']] = $classe;
}
$sezioni = array_keys($classi);
$alfabeto = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "Z");

include "nuove_classi.html.php";

?>