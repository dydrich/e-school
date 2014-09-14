<?php

require_once "../../../lib/start.php";
require_once "../../../lib/Classbook.php";
require_once "../../../lib/RBUtilities.php";

check_session();
check_permission(SEG_PERM|DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

if ($_SESSION['__area__'] == "manager"){
	$school_year = $_SESSION['__school_year__'][$_SESSION['__school_order__']];
}
else{
	$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
	$school_year = $_SESSION['__school_year__'][$ordine_scuola];
}

$path = $_SESSION['__path_to_root__']."/download/registri/".$_SESSION['__current_year__']->get_ID()."/classi/";
@mkdir($path, 0755, true);

$cls = $_POST['cls'];
$rb = RBUtilities::getInstance($db);
$classe = $rb->loadClassFromClassID($cls);

$cb = new Classbook($classe, $school_year, "", $db, $path);
$cb->createPDF();

$response = array("status" => "ok", "message" => "");
header("Content-type: application/json");

$res = json_encode($response);
echo $res;
exit;
