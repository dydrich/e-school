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
	
$sel_classes = "SELECT * FROM fc_classi ORDER BY descrizione";
$res_classes = $db->executeQuery($sel_classes);
$n_cls = $res_classes->num_rows;

$sel_students = "SELECT COUNT(id_alunno) FROM fc_alunni";
$n_std = $db->executeCount($sel_students);
if($n_std > 0){
	$sel_not_assigned = "SELECT COUNT(id_alunno) FROM fc_alunni WHERE id_classe IS NULL";
	$not_assigned = $db->executeCount($sel_not_assigned);
}

/*
 * color for class visualization
 * stored in session
 */
if(!isset($_SESSION['__colors__'])){
	$sel_colors = "SELECT * FROM fc_backgrounds ORDER BY id";
	$res_colors = $db->executeQuery($sel_colors);
	$_SESSION['__colors__'] = array();
	while($color = $res_colors->fetch_assoc()){
		$_SESSION['__colors__'][$color['id']] = array("color" => $color['color'], "is_used" => false);
	}
}

$_SESSION['__fc__'] = array();
$sel_fc = "SELECT * FROM fc_classi ORDER BY id_classe";
$res_fc = $db->executeQuery($sel_fc);
$x = 1;
while($cl1 = $res_fc->fetch_assoc()){
	$_SESSION['__fc__'][$cl1['id_classe']] = array("class" => $cl1['descrizione'], "color" => $_SESSION['__colors__'][$x]['color']);
	$_SESSION['__colors__'][$x]['is_used'] = true;
	$x++;
}

include "index.html.php";

?>