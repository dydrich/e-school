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
	
$_SESSION['__class_id__'] = $_REQUEST['id_classe'];
	
$sel_desc = "SELECT fc_scuole_provenienza.descrizione AS sc, fc_classi_provenienza.descrizione AS descrizione FROM fc_classi_provenienza, fc_scuole_provenienza WHERE fc_classi_provenienza.id_scuola = fc_scuole_provenienza.id_scuola AND id_classe = ".$_REQUEST['class_id'];
$class_desc = $db->executeQuery($sel_desc);
$sc = $class_desc->fetch_assoc();

$order = "";
if(isset($_REQUEST['order']) && $_REQUEST['order'] == "cls")
	$order = "id_classe, ";

/* students list */	
$sel_students = "SELECT id_alunno, CONCAT_WS(' ', cognome, nome) AS name, fc_alunni.id_classe, H, sesso, voto, CONCAT_WS('. ', diagnosi_h, note) AS note, fc_scuole_provenienza.id_scuola AS school, fc_alunni.classe_provenienza FROM fc_alunni, fc_classi_provenienza, fc_scuole_provenienza WHERE fc_alunni.classe_provenienza = fc_classi_provenienza.id_classe AND fc_classi_provenienza.id_scuola = fc_scuole_provenienza.id_scuola AND fc_alunni.classe_provenienza = ".$_REQUEST['class_id']." ORDER BY $order cognome, nome";
try{
	$res_students = $db->executeQuery($sel_students);
} catch(MySQLException $ex){
	$ex->redirect();
}
$n_std = $res_students->num_rows;

/* summary */
$sel_sex = "SELECT sesso, COUNT(sesso) AS count FROM fc_alunni WHERE classe_provenienza = ".$_REQUEST['class_id']." GROUP BY sesso";
$res_sex = $db->executeQuery($sel_sex);
$male = $female = 0;
while($sx = $res_sex->fetch_assoc()){
	if($sx['sesso'] == 'M')
		$male = $sx['count'];
	else
		$female = $sx['count'];
}

$sel_h = "SELECT H FROM fc_alunni WHERE classe_provenienza = ".$_REQUEST['class_id']." AND H IS NOT NULL AND H <> 0";
$res_h = $db->executeQuery($sel_h);
$h = $dsa = 0;
while($al = $res_h->fetch_assoc()){
	if($al['H'] < 4)
		$dsa++;
	if($al['H'] > 1)
		$h++;
}

$sel_avg = "SELECT ROUND(AVG(voto), 2) FROM fc_alunni WHERE classe_provenienza = ".$_REQUEST['class_id'];
$avg = $db->executeCount($sel_avg);

/* class colors */
$sel_cls = "SELECT * FROM fc_classi ORDER BY descrizione";
$res_cls = $db->executeQuery($sel_cls);
$classes_and_colors = array();
$x = 1;
while($cls = $res_cls->fetch_assoc()){
	$classes_and_colors[$cls['id_classe']] = array("id" => $cls['id_classe'], "name" => $cls['descrizione'], "color" =>$_SESSION['__colors__'][$x]['color']);
	$x++;
}

include "class_from.html.php";

?>