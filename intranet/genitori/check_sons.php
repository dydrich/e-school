<?php

$figli = explode(",", trim($_SESSION['__figli__']));
if(isset($_REQUEST['son'])){
	$_SESSION['__current_son__'] = $_REQUEST['son'];
}
else{
	if(!isset($_SESSION['__current_son__'])){
		$_SESSION['__current_son__'] = $figli[0];
	}
}
//$tab = array_search($_SESSION['__current_son__'], $figli);
//$limit = $limite * count($figli);
if(!isset($_SESSION['__sons__'])){
	/**
	 * controllo preliminare per vedere se i figli sono attivi
	 */
	$all_active = true;
	$c_active = $c_inactive = 0;
	$actives = array();
	$inactives = array();
	foreach ($figli as $figlio){
		$act = $db->executeCount("SELECT attivo FROM rb_alunni WHERE id_alunno = {$figlio}");
		if ($act != 1) {
			$all_active = false;
			$c_inactive++;
			$inactives[] = $figlio;
		}
		else {
			$c_active++;
			$actives[] = $figlio;
		}
	}
	$stds = array();
	if ($c_active > 0) {
		// alunni attivi
		$str_active = implode(",", $actives);
		$sel_alunni = "SELECT rb_alunni.*, ordine_di_scuola FROM rb_alunni, rb_classi WHERE rb_alunni.id_classe = rb_classi.id_classe AND id_alunno IN ({$str_active})";
		//print $sel_alunni;
		$res_alunni = $db->execute($sel_alunni);

		while($al = $res_alunni->fetch_assoc()){
			$stds[$al['id_alunno']] = array($al['nome']." ".$al['cognome'], $al['id_classe'], $al['ordine_di_scuola'], 1);
		}
	}
	if ($c_inactive > 0) {
		$str_inactive = implode(",", $inactives);
		$sel_alunni = "SELECT rb_alunni.*, ordine AS ordine_di_scuola FROM rb_alunni, rb__classi WHERE rb_alunni.id_classe = rb__classi.id_classe AND id_alunno IN ({$str_inactive})";
		//print $sel_alunni;
		$res_alunni = $db->execute($sel_alunni);

		while($al = $res_alunni->fetch_assoc()){
			$stds[$al['id_alunno']] = array($al['nome']." ".$al['cognome'], $al['id_classe'], $al['ordine_di_scuola'], 0);
		}
	}

	$_SESSION['__sons__'] = $stds;
}
else{
	$stds = $_SESSION['__sons__'];
}