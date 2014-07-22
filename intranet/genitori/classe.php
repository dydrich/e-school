<?php

require_once "../../lib/start.php";
require_once "../../lib/SessionUtils.php";

ini_set("display_errors", "1");

check_session();
check_permission(GEN_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

include "check_sons.php";
$page = "classe.php";

if(isset($_REQUEST['son'])){
	$utils = SessionUtils::getInstance($db);
	$utils->registerCurrentClassFromUser($_REQUEST['son'], "__classe__");
}

$classe = $_SESSION['__classe__'];
$idclasse = $classe->get_ID();
	
$sel_alunni = "SELECT rb_alunni.*, rb_genitori.uid, rb_genitori.nome AS n_gen, rb_genitori.cognome AS c_gen FROM rb_alunni LEFT JOIN rb_genitori_figli ON rb_alunni.id_alunno = rb_genitori_figli.id_alunno LEFT JOIN rb_genitori ON rb_genitori_figli.id_genitore = rb_genitori.uid WHERE id_classe = $idclasse ORDER BY rb_alunni.cognome, rb_alunni.nome";
//print $sel_alunni;
$res_alunni = $db->execute($sel_alunni);
$alunni = array();
while($alunno = $res_alunni->fetch_assoc()){
	array_push($alunni, array("alunno" => $alunno['cognome']." ".$alunno['nome'], "genitore" => $alunno['c_gen']." ".$alunno['n_gen']));
}
$num_righe = intval((count($alunni) + 1) / 2);
//print $num_righe;

$navigation_label = "Registro elettronico genitori: alunno ".$_SESSION['__sons__'][$_SESSION['__current_son__']][0];

include "classe.html.php";
