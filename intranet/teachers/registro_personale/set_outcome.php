<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];

$alunno = $_POST['alunno'];
$esito = $_POST['outcome'];

$sel_es = "SELECT * FROM rb_esiti WHERE id_esito = {$esito}";
$res_es = $db->executeQuery($sel_es);
$row_es = $res_es->fetch_assoc();

$sel_idpubblicazione = "SELECT MAX(id_pagella) AS id_pagella FROM rb_pubblicazione_pagelle WHERE anno = {$_SESSION['__current_year__']->get_ID()}";
try{
	$res_idp = $db->executeQuery($sel_idpubblicazione);
} catch (MySQLException $ex){
	echo "kosql;".$ex->getQuery().";".$ex->getMessage();
	exit;
}
$row = $res_idp->fetch_assoc();
$idp = $row['id_pagella'];

$upd = "UPDATE rb_pagelle SET esito = {$esito} WHERE id_pubblicazione = {$idp} AND id_alunno = {$alunno}";
try{
	$res = $db->executeUpdate($upd);
} catch (MySQLException $ex){
	echo "kosql;".$ex->getQuery().";".$ex->getMessage();
	exit;
}

echo "ok;".$row_es['positivo'];