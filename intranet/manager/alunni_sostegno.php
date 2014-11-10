<?php

require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = setNavigationLabel($_SESSION['__school_order__']);

$anno = $_SESSION['__current_year__']->get_ID();
$school = $_SESSION['__school_order__'];

$sel_sos = "SELECT CONCAT_WS(' ', rb_alunni.cognome, rb_alunni.nome) AS stud, CONCAT(anno_corso, sezione) AS classe, legge104 AS ore, id_alunno AS alunno FROM rb_alunni, rb_classi WHERE rb_classi.id_classe = rb_alunni.id_classe AND ordine_di_scuola = {$school} AND attivo = '1' AND legge104 IS NOT NULL ORDER BY rb_alunni.cognome, rb_alunni.nome";
$res_sos = $db->execute($sel_sos);

$drawer_label = "Elenco alunni con sostegno";

include "alunni_sostegno.html.php";
