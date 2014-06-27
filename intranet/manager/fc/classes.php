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
	
if(isset($_REQUEST['update'])){
	//print $_REQUEST['cls'];
	$cls = explode(",", $_REQUEST['cls']);
	$index = 0;
	foreach($cls as $a){
		$desc = strtoupper("1".trim($a));
		$ins = "INSERT INTO fc_classi (descrizione) VALUE ('$desc')";
		try{
			$res = $db->executeUpdate($ins);
		} catch (MySQLException $ex){
			$ex->redirect();
		}
		/*
		 * class - color association
		 * stored in session
		 */
		
	}
}
	
$sel_classes = "SELECT fc_classi.id_classe AS id, fc_classi.descrizione AS descrizione, COUNT(fc_alunni.id_alunno) AS alunni FROM fc_classi LEFT JOIN fc_alunni ON fc_classi.id_classe = fc_alunni.id_classe GROUP BY fc_classi.id_classe, descrizione ORDER BY descrizione";
try{
	$res_classes = $db->executeQuery($sel_classes);
} catch(MySQLException $ex){
	$ex->redirect();
}
$n_cls = $res_classes->num_rows;

$sel_mv = "SELECT ROUND(AVG(voto), 2) FROM fc_alunni";
$mv = $db->executeCount($sel_mv);

include "classes.html.php";

?>