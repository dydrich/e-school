<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

//print $_REQUEST['action'];
if(isset($_REQUEST['action']) && $_REQUEST['action'] == "send"){
	include_once $_SESSION['__path_to_root__'].'shared/sendmail.php';
}

if($_REQUEST['d'] == "students"){
	$area = "alunni";
	$sel_tos = "SELECT ";
}
else{
	$area = "genitori";
}

$navigation_label = "Registro elettronico - Comunicazioni {$area}";

include "send_mail.html.php";

?>