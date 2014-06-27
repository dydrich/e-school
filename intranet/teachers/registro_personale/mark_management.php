<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$docente = $_SESSION['__user__']->getUid();
$anno = $_SESSION['__current_year__']->get_ID();
$materia = $_SESSION['__materia__'];

function insert_mark($request, $docente, $anno, $materia, $db){
	$alunno = $request['alunno'];
	$voto = $request['voto'];
	//$modificatori = $request['mod'];
	$private = $request['private'];
	$descrizione = $db->real_escape_string($request['descrizione']);
	$data_voto = format_date($request['data_voto'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
	$tipologia = $request['tipo'];
	$argomento = $db->real_escape_string($request['argomento']);
	$note = $db->real_escape_string($request['note']);
	$query = "INSERT INTO rb_voti (alunno, docente, materia, anno, voto, privato, descrizione, tipologia, note, data_voto, argomento) VALUES ($alunno, $docente, $materia, $anno, $voto, $private, '$descrizione', '$tipologia', '$note', '$data_voto', '$argomento')";
	if($res = $db->executeUpdate($query))
		$msg = "0";
	else
		$msg = "1";
	
	if($request['referer'] == "index.php")
		print("<script>parent.document.location.href = 'index.php?q={$request['q']}&subject={$materia}'</script>");
	else
		print("<script>parent.document.location.href = 'student.php?stid=$alunno&msg=$msg&q=".$request['q']."'</script>");
	//header("Location: student.php?stid=$alunno&msg=$msg&q=".$_REQUEST['q']);
}

function update_mark($request, $db, $materia){
	$alunno = $request['alunno'];
	$id_voto = $request['myid'];
	$voto = $request['voto'];
	//$modificatori = $request['mod'];
	$private = $request['private'];
	$descrizione = $db->real_escape_string($request['descrizione']);
	$data_voto = format_date($request['data_voto'], IT_DATE_STYLE, SQL_DATE_STYLE, "-");
	$tipologia = $request['tipo'];
	$argomento = $db->real_escape_string($request['argomento']);
	$note = $db->real_escape_string($request['note']);
	$query = "UPDATE rb_voti SET voto = $voto, privato = $private, descrizione = '$descrizione', tipologia = '$tipologia', note = '$note', data_voto = '$data_voto', argomento = '$argomento' WHERE id_voto = $id_voto";
	if($res = $db->executeUpdate($query))
		$msg = "2";
	else
		$msg = "3";
	
	if($request['referer'] == "index.php")
		print("<script>parent.document.location.href = 'index.php?q={$request['q']}&subject={$materia}'</script>");
	else
		print("<script>parent.document.location.href = 'student.php?stid=$alunno&msg=$msg&q=".$request['q']."'</script>");
	
}

function delete_mark($request, $db, $materia){
	$id_voto = $request['id_voto'];
	$alunno = $request['ia'];
	$query = "DELETE FROM rb_voti WHERE id_voto = $id_voto";
	if($res = $db->executeUpdate($query))
		$msg = "4";
	else
		$msg = "5";
	
	if($request['referer'] == "index.php")
		print("<script>parent.document.location.href = 'index.php?q={$request['q']}&subject={$materia}'</script>");
	else
		print("<script>parent.document.location.href = 'student.php?stid=$alunno&msg=$msg&q=".$request['q']."'</script>");
}

$action = $_REQUEST['do'];

switch($action){
	case "new":
		insert_mark($_REQUEST, $docente, $anno, $materia, $db);
		break;
	case "update":
		update_mark($_REQUEST, $db, $materia);
		break;
	case "delete":
		delete_mark($_REQUEST, $db, $materia);
		break;
	default:
		usage();
		break;
}
