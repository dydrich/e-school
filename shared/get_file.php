<?php

require_once "../lib/start.php";
require_once "../lib/MimeType.php";

ini_set("display_errors", "1");

$ff_open = $_GET['f'];
$opener = $_SERVER['HTTP_REFERER'];
$pathinfo = pathinfo($opener);

if(isset($_GET['delete'])){
	$dir = $_GET['dir'];
	if ($dir == "pagelle"){
		/*
		$al = $_GET['a'];
		$g = $_GET['g'];
		$idp = $_GET['idp'];
		$read = "INSERT INTO rb_lettura_pagelle (id_pubblicazione, alunno, data_lettura, genitore) VALUES ({$idp}, {$al}, NOW(), {$g})";
		echo $read;
		$insert_read = $db->executeQuery($read);
		*/
		$dir = "download/pagelle";
	}
	if(file_exists("../{$dir}/{$ff_open}")){
		$mime = MimeType::getMimeContentType($file);
		
		$fp = fopen("../{$dir}/{$ff_open}", "r");
		header("Content-Type: ".$mime['ctype']);
		header("Content-Disposition: attachment; filename=".$ff_open);
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: public");
		fpassthru($fp);
		@fclose($fp);
		if ($_GET['delete'] == 1){
			unlink("../{$dir}/{$ff_open}");
		}
		exit;
	}
	else{
		$_SESSION['pathinfo'] = $pathinfo;
		$_SESSION['download'] = "/{$dir}/".$ff_open;
		//header("Location: ".$pathinfo['dirname']."/no_file.php");
		echo "file non trovato: ".$_SESSION['download'];
	}
}

$tipo = $_GET['dt'];
$file = basename($ff_open);
$insert = true;
if ($_GET['dir'] && ($tipo == "" || $tipo == "pagelle")){
	$fpath = "../download/{$_GET['dir']}/";
	$insert = false;
}
else {
	$fpath = "../download/{$tipo}/";
}

$opener = $_SERVER['HTTP_REFERER'];
$pathinfo = pathinfo($opener);

if(file_exists($fpath.$ff_open)){
	if ($tipo == 7 || $tipo == "pagelle"){
		$uid = 0;
	}
	else {
		$uid = $_SESSION['__user__']->getUid();
	}
	if ($insert){
		// registro il download nel db
		$id_type = $_REQUEST['dt'];
		$id = $_REQUEST['id'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$ins = "INSERT INTO rb_downloads (doc_id, doc_type, ip_address, data_dw, user) VALUES ($id, $id_type, '".$ip."', NOW(), ".$uid.")";
		try{
			$rs = $db->executeUpdate($ins);
		} catch (MySQLException $ex){
			$ex->redirect();
		}
		// aggiorno il contatore 
		$table = "";
		if($id_type == 1){
			$table = "rb_stud_works";
			$field = "id_work";
		}
		else {
			$table = "rb_documents";
			$field = "id";
		}
		$upd = "UPDATE $table SET dw_counter = (dw_counter + 1) WHERE $field = $id";
		try{
			$rs_upd = $db->executeUpdate($upd);
		} catch (MySQLException $ex){
			$ex->redirect();
		}
	}
	
	if ($_GET['dir'] == "pagelle" && !isset($_GET['noread'])){
		$al = $_GET['a'];
		$g = $_GET['g'];
		$idp = $_GET['idp'];
		$insert_read = $db->execute("INSERT INTO rb_lettura_pagelle (id_pubblicazione, alunno, data_lettura, genitore) VALUES ({$idp}, {$al}, NOW(), {$g})");
	}
	
	$mime = MimeType::getMimeContentType($file);
	
    $fp = fopen($fpath.$ff_open, "r");
    header("Content-Type: ".$mime['ctype']);
    header("Content-Disposition: attachment; filename=".$file);
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Pragma: public");
    fpassthru($fp);
    @fclose($fp);
    exit;
}
else{
	$_SESSION['pathinfo'] = $pathinfo;
	$_SESSION['download'] = $ff_open;
    //header("Location: ".$pathinfo['dirname']."/no_file.php");
	echo "file non trovato: ".$_SESSION['download'];
}
?>