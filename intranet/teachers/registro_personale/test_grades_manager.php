<?php

require_once "../../../lib/start.php";
require_once "../../../lib/Test.php";
require_once "../../../lib/Grade.php";

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

header("Content-type: application/json");
$response = array("status" => "ok", "message" => "Operazione completata");

$voto = $_REQUEST['voto'];
$alunno = $_REQUEST['alunno'];
$id_verifica = $_REQUEST['verifica'];
$id_voto = $_REQUEST['id_voto'];
$docente = $_SESSION['__user__']->getUid();
$materia = $_SESSION['__materia__'];
$anno = $_SESSION['__current_year__']->get_ID();
$argomento = $descrizione = $data_voto = "";

/*
 * recupero i dati della verifica
 */
$sel_test = "SELECT * FROM rb_verifiche WHERE id_verifica = $id_verifica";
try{
	$res_test = $db->executeQuery($sel_test);
} catch (MySQLException $ex){
	$response['status'] = "kosql";
	$response['message'] = $ex->getMessage();
	$response['query'] = $ex->getQuery();
	echo json_encode($response);
	exit;
}
$verifica = $res_test->fetch_assoc();

$test = new \eschool\Test($id_verifica, new MySQLDataLoader($db), null, false);

$argomento = $db->real_escape_string($verifica['argomento']);
$descrizione = $verifica['prova'];
$data_voto = substr($verifica['data_verifica'], 0, 10);
$tipo = $verifica['tipologia'];

$sel_obj = "SELECT * FROM rb_obiettivi_verifica WHERE id_verifica = {$id_verifica}";
$res_obj = $db->executeQuery($sel_obj);
$obj = array();
if ($res_obj->num_rows > 0){
	while ($row = $res_obj->fetch_assoc()){
		$obj[] = $row['id_obiettivo'];
	}
}

if($id_voto != 0){
	// esiste gia' il voto: vado in update o delete
	if($voto == 0){
		// alunno assente: delete
		$query = "DELETE FROM rb_voti WHERE id_voto = $id_voto";
		$obj = "DELETE FROM rb_voti_obiettivo WHERE id_voto = {$id_voto}";
		$idv = 0;
	}
	else{
		// update
		$query = "UPDATE rb_voti SET voto = $voto, id_verifica = $id_verifica WHERE id_voto = $id_voto";
		$obj = "UPDATE rb_voti_obiettivo SET voto = {$voto} WHERE id_voto = {$id_voto}";
		$idv = $id_voto;
	}
	try{
		$db->executeUpdate($query);
		$db->executeUpdate($obj);
	} catch (MySQLException $ex){
		$response['status'] = "kosql";
		$response['message'] = $ex->getMessage();
		$response['query'] = $ex->getQuery();
		echo json_encode($response);
		exit;
	}
}
else{
	// nuovo voto
	if($voto == 0){
		// alunno assente: do_nothing()
	}
	else{
		// insert
		$query = "INSERT INTO rb_voti (alunno, docente, materia, anno, voto, descrizione, tipologia, data_voto, argomento, id_verifica, from_file, inserimento) VALUES ($alunno, $docente, $materia, $anno, $voto, '$descrizione', {$tipo}, '$data_voto', '$argomento', $id_verifica, 'test_grades_manager', NOW())";
	}
	try{
		$idv = $db->executeUpdate($query);
		if (count($obj) > 0){
			foreach ($obj as $ob){
				$db->executeUpdate("INSERT INTO rb_voti_obiettivo (id_voto, obiettivo, voto) VALUES ({$idv}, {$ob}, {$voto})");
			}
		}
	} catch (MySQLException $ex){
		$response['status'] = "kosql";
		$response['message'] = $ex->getMessage();
		$response['query'] = $ex->getQuery();
		echo json_encode($response);
		exit;
	}
}

$sel_media = "SELECT ROUND(AVG(voto), 2) FROM rb_voti WHERE id_verifica = $id_verifica";
$media = $db->executeCount($sel_media);
$mat_m = $media;
$sel_alunni = "SELECT COUNT(alunno) FROM rb_voti WHERE id_verifica = $id_verifica";
$count_alunni = $db->executeCount($sel_alunni);

$voti_religione = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");
if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
	if($media < 5.5){
		$media = $voti_religione["4"];
	}
	else if ($media > 6.49 && $media < 8){
		$media = $voti_religione["8"];
	}
	else {
		$media = $voti_religione[round($media)];
	}
}

$response['media'] = $media;
$response['count'] = $count_alunni;
$response['idv'] = $idv;
$response['media_o'] = $mat_m;
$response['media_arr'] = round($mat_m);
echo json_encode($response);
exit;
