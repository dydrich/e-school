<?php

include "../../../lib/start.php";

check_session();
check_permission(DOC_PERM);

$anno = substr($_SESSION['__current_year__']->get_data_chiusura(), 6);
$argo = $_SESSION['__classe__']->get_ID();
$path = "/www/MW_qPayyGPcq/scuolamediatre.it/download/statistiche/";
$quadrimestre = "primo";

$db->select_db("information_schema");
$sel_suff = "SELECT table_name FROM tables WHERE table_name LIKE 'dati%' ORDER BY table_name DESC LIMIT 1";
$res_suff = $db->execute($sel_suff);
$dt = $res_suff->fetch_assoc();
if($dt['table_name'] == "dati_".$anno."_2q"){
	$year = $anno;
	$path .= $year."/";
	$quadrimestre = "secondo";
	$legend = "Dati statistici finali a. s. ".($anno)." - ".($anno - 1);
	$pos = 2;
	$anno_classe = $_SESSION['__classe__']->get_anno();
}
else if($dt['table_name'] == "dati_".$anno."_1q"){
	// esistono i dati del 1q
	// recupero quelli
	$path .= $anno."/";
	$year = $anno;
	$legend = "Dati statistici 1 quadrimestre a. s. ".($anno - 1)." - ".($anno);
	$pos = 1;
	$anno_classe = $_SESSION['__classe__']->get_anno();
}
else if($dt['table_name'] == "dati_".($anno - 1)."_2q"){
	$year = ($anno - 1);
	$path .= $year."/";
	$quadrimestre = "secondo";
	$legend = "Dati statistici finali a. s. ".($anno - 2)." - ".($anno - 1);
	$pos = 2;
	$anno_classe = ($_SESSION['__classe__']->get_anno() - 1);
}

/*
if((date("Y-m-d") < $anno."-03-01")){
	// siamo in periodo precedente agli scrutini 1q
	// recuperare i dati dell'anno precedente
	$year = ($anno - 1);
	$path .= $year."/";
	$quadrimestre = "secondo";
	$legend = "Dati statistici finali a. s. ".($anno - 2)." - ".($anno - 1);
	$pos = 2;
	$anno_classe = ($_SESSION['__classe__']->get_anno() - 1);
	//$anno_classe = $classe['classe'];
}
else{
	// esistono i dati del 1q
	// recupero quelli
	$path .= $anno."/";
	$year = $anno;
	$legend = "Dati statistici 1 quadrimestre a. s. ".($anno - 1)." - ".($anno);
	$pos = 1;
	$anno_classe = $_SESSION['__classe__']->get_anno();
}
*/
$classe = $anno_classe.$_SESSION['__classe__']->get_sezione();
$filename = $classe."_".$argo."_".$year.".xml";
//print $filename;

$doc = new DOMDocument('1.0');
$doc->formatOutput = true;
$doc->preserveWhiteSpace = false;
$doc->load($path.$filename);
//print "Aperto file $filename";
$xpath = new DOMXPath($doc);
$statistiche = $doc->getElementsByTagName("statistiche")->item($pos);
//print $statistiche->localName." == ".$pos;

include "statistiche.html.php";

?>