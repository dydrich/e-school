<?php

include "../../lib/start.php";

check_session();
check_permission(ADM_PERM);

$sel_gruppi = "SELECT * FROM rb_gruppi WHERE gid BETWEEN 2 AND 5";
$res_gruppi = $db->executeQuery($sel_gruppi);

$sel_step = "SELECT * FROM rb_w_step";
$res_step = $db->executeQuery($sel_step);

if(isset($_REQUEST['id']) && ($_REQUEST['id'] != 0)){
	$sel_flow = "SELECT * FROM rb_w_workflow WHERE id_workflow = ".$_REQUEST['id'];
	//print $sel_flow;
	try{
		$res_flow = $db->executeQuery($sel_flow);
	} catch (MySQLException $ex){
		$ex->alert();
		exit;
	}
	$flow = $res_flow->fetch_assoc();
	//print $flow['codice_step'];
	$s_step = "SELECT * FROM rb_w_step WHERE id_step IN (".$flow['codice_step'].")";
	//print $s_step;
	try{
    	$r_step = $db->executeQuery($s_step);
	} catch (MySQLException $ex){
		$ex->alert();
		exit;
	}
	$stringa_step = $codice_step = "";
    while($s = $r_step->fetch_assoc()){
        $stringa_step .= $s['descrizione']."-&gt;"; 
        //$codice_step .= $s['id_step'].",";
    }
    $stringa_step = substr($stringa_step, 0, (count($stringa_step) - 6)); 
    $_i = $_REQUEST['id'];
    $sel_count_groups = "SELECT COUNT(*) AS ct FROM rb_gruppi WHERE permessi&".$flow['gruppi'];
    try{
    	$ct = $db->executeCount($sel_count_groups);
    } catch (MySQLException $ex){
		$ex->alert();
		exit;
	}
}else{
	$_i = 0;
}

include "dettaglio_workflow.html.php";

?>