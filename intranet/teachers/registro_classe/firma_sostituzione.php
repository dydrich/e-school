<?php

require_once "../../../lib/start.php";

check_session();
check_permission(DOC_PERM);

$id_registro = $_POST['id_registro'];
$ora = $_POST['ora'];
$update = $_POST['update'];

if($update == 1){
	$upd = "UPDATE rb_reg_firme SET firma = NULL, docente = {$_SESSION['__user__']->getUid()}, materia = 33, argomento = 'Sostituzione' WHERE id_registro = $id_registro AND ora = $ora";
}
else{
	$upd = "INSERT INTO rb_reg_firme (id_registro, ora, firma, docente, materia, argomento) VALUES ({$id_registro}, {$ora}, NULL, {$_SESSION['__user__']->getUid()}, 33, 'Sostituzione')";
}
try{
	$db->executeUpdate($upd);
} catch(MySQLException $ex){
	echo "kosql#".$ex->getMessage()."#".$ex->getQuery();
	exit;
}

echo "ok";
