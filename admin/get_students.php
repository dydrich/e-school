<?php

require_once "../lib/start.php";

check_session(AJAX_CALL);
check_permission(ADM_PERM);

header("Content-type: text/plain");

$cls = $_POST['cls'];
if(!is_numeric($cls)){
	echo "ko;Classe non esistente";
	exit;
}
if($_POST['action'] == "student_insert"){
	$sel_sts = "SELECT id_alunno, CONCAT_WS(' ', cognome, nome) AS name FROM rb_alunni WHERE id_classe = {$cls} AND attivo = '1' AND id_alunno NOT IN (SELECT DISTINCT id_alunno FROM rb_reg_alunni, rb_reg_classi WHERE id_reg = id_registro AND id_anno = {$_SESSION['__current_year__']->get_ID()} AND rb_reg_alunni.id_classe = {$cls}) ORDER BY cognome, nome";
}
else {
	$sel_sts = "SELECT id_alunno, CONCAT_WS(' ', cognome, nome) AS name FROM rb_alunni WHERE id_classe = {$cls} AND attivo = '1'";
}
try{
	$res_sts = $db->executeQuery($sel_sts);
} catch(MySQLException $ex){
	echo "kosql;".$ex->getQuery().";".$ex->getMessage();
	exit;
}

$sts = array();
$out = "ok;";
while($row = $res_sts->fetch_assoc()){
	$sts[] = $row['id_alunno']."#".$row['name'];
}

$out .= join("|", $sts);
echo $out; exit;