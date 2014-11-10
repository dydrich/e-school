<?php

require_once "../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$navigation_label = "scuola secondaria";
$drawer_label = "E-profile";

if(isset($_REQUEST['save']) && $_REQUEST['save'] == 1){
	$action = "profile";
	include "save_profile.php";
}

$sel_profile = "SELECT * FROM rb_profili_alunni WHERE id_alunno = ".$_SESSION['__user__']->getUid();
$res_profile = $db->execute($sel_profile);
if($res_profile->num_rows > 0)
	$profile = $res_profile->fetch_assoc();

include "dati.html.php";
