<?php

require_once "../../../lib/start.php";

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];

$sel_subs = "SELECT rb_reg_firme.*, sezione, anno_corso, data FROM rb_reg_firme, rb_reg_classi, rb_classi WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND id_registro = id_reg AND rb_reg_classi.id_classe = rb_classi.id_classe AND docente = {$_SESSION['__user__']->getUid()} AND materia = 33 ORDER BY data, ora, anno_corso, sezione";
$res_subs = $db->execute($sel_subs);

$table = "rb_vclassi_s{$ordine_scuola}";

$sel_classes = "SELECT * FROM {$table} ORDER BY sezione, anno_corso";
$res_classes = $db->execute($sel_classes);

/*
echo "Aggiornamento tabella reg_firme<br><br>";
$sel_firme = "SELECT id, firma from rb_reg_firme";
$res_firme = $db->execute($sel_firme);
while($row = $res_firme->fetch_assoc()){
	list($doc, $sub) = split(";", $row['firma']);
	echo "UPDATE rb_reg_firme SET docente = {$doc}, materia = {$sub} WHERE id = {$row['id']}";
	$db->execute("UPDATE rb_reg_firme SET docente = {$doc}, materia = {$sub} WHERE id = {$row['id']}");
	echo " ==> OK<br>";
}
*/

$navigation_label = "Registro della classe ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione();
$drawer_label = "Sostituzioni";

include "sostituzioni.html.php";
