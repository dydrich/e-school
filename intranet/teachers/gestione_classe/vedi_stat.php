<?php

include "../../../lib/start.php";

check_session();
check_permission(DOC_PERM);

ini_set('display_errors', 1);

$anno = substr($_SESSION['__current_year__']->get_data_chiusura(), 6);
$argo = $_SESSION['__classe__']->get_ID();

$legend = "Statistiche a. s. ".($_REQUEST['anno_rif'] - 1)."-".$_REQUEST['anno_rif'].", ".$_REQUEST['quadrimestre']." quadrimestre: media voto ";
if($_REQUEST['statistica'] == "mvg")
	$legend .= "totale";
else 	
	$legend .= strtoupper(preg_replace("/_/", " ", $_REQUEST['statistica']));
$legend2 = $legend." - classi ";
if($_REQUEST['anno_classe'] == 1) 
	$legend2 .= "prime"; 
else if ($_REQUEST['anno_classe'] == 2) 
	$legend2 .= "seconde"; 
else 
	$legend2 .= "terze";

include "vedi_stat.html.php";

?>