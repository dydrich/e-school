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
	$sel_alunni = "SELECT rb_alunni.*, ordine_di_scuola FROM rb_alunni, rb_classi WHERE rb_alunni.id_classe = rb_classi.id_classe AND id_alunno IN (".$_SESSION['__figli__'].")";
	//print $sel_alunni;
	$res_alunni = $db->execute($sel_alunni);
	$stds = array();
	while($al = $res_alunni->fetch_assoc()){
		$stds[$al['id_alunno']] = array($al['nome']." ".$al['cognome'], $al['id_classe'], $al['ordine_di_scuola']);
	}
	$_SESSION['__sons__'] = $stds;
}
else{
	$stds = $_SESSION['__sons__'];
}

?>
