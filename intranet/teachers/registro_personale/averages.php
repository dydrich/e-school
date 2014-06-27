<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$voti = explode(",", $_REQUEST['voti']);
$pesi = explode(",", $_REQUEST['pesi']);

$media_aritmetica = $media_ponderata = 0;
$somma = $somma_pesata = $somma_pesi = 0;

for($i = 0; $i < count($voti); $i++){
	$somma += $voti[$i];
	$somma_pesata += ($voti[$i]*$pesi[$i]);
	$somma_pesi += $pesi[$i];
}

if(count($voti) > 0){
	$media_aritmetica = $somma / count($voti);
	$media_ponderata = round(($somma_pesata / $somma_pesi), 2);
}
else{
	$media_ponderata = 0;
}
header("Content-type: text/plain");
print "ok;$media_ponderata";
exit;

?>