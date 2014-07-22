<?php

require_once "../../lib/start.php";

check_session(AJAX_CALL);
check_permission(ADM_PERM);

header("Content-type: text/plain");

if($_POST['action'] != 2){
	$nome = $db->real_escape_string(utf8_encode(trim($_POST['nome'])));
	$desc = $db->real_escape_string(utf8_encode(trim($_POST['descrizione'])));
	$referenti = trim($_POST['teachers']);
	$ref = explode("#", $referenti);
	$anno_inizio = isset($_POST['anno']) ? $_POST['anno'] : $_SESSION['__current_year__']->get_ID();
	$anno = $_SESSION['__current_year__']->get_ID();
	$attivo = $_POST['attivo'];
}
switch($_POST['action']){
	case 1:     // inserimento
		$statement = "INSERT INTO rb_progetti (nome, descrizione, anno_inizio, attivo) VALUES ('$nome', '$desc', '$anno_inizio', $attivo)";
		try{
			$db->executeUpdate("BEGIN");
			$recordset = $db->executeUpdate($statement);
			foreach ($ref as $f){
				$db->executeUpdate("INSERT INTO rb_responsabili_progetto (progetto, docente, anno) VALUES ($recordset, $f, $anno)");
			}
			$db->executeUpdate("COMMIT");
		} catch (MySQLException $ex){
			$db->executeUpdate("ROLLBACK");
			print "ko|".$ex->getMessage()."|".$ex->getQuery();
			exit;
		}
		break;
	case 2:     // cancellazione
		/*
		* prima di cancellare occorre verificare che non esistano documenti caricati in archivio
		* relativi al progetto in esame. Questo controllo e' fatto anche al momento del submit,
		* in javascript, ma viene ripetuto per sicurezza
		*/
		$sel_docs = "SELECT COUNT(*) FROM rb_documents WHERE progetto = ".$_REQUEST['_i'];
    	$count_docs = $db->executeCount($sel_docs);
		if($count_docs > 0){
			print "stop|Impossibile cancellare il progetto: sono presenti dei documenti ad esso associati";
        	exit;
   		}
        $statement = "DELETE FROM rb_progetti WHERE id_progetto = ".$_POST['_i'];
        try{
        	$db->executeUpdate("BEGIN");
        	$recordset = $db->executeUpdate($statement);
        	$db->executeUpdate("DELETE FROM rb_responsabili_progetto WHERE progetto = ".$_POST['_i']);
        	$db->executeUpdate("COMMIT");
        } catch (MySQLException $ex){
        	$db->executeUpdate("ROLLBACK");
        	print "ko|".$ex->getMessage()."|".$ex->getQuery();
        	exit;
        }
        break;
	case 3:     // modifica
		$statement = "UPDATE rb_progetti SET nome = '$nome', descrizione = '$desc', anno_inizio = '$anno_inizio', attivo = $attivo WHERE id_progetto = ".$_POST['_i'];
		try{
        	$db->executeUpdate("BEGIN");
        	$recordset = $db->executeUpdate($statement);
        	$db->executeUpdate("DELETE FROM rb_responsabili_progetto WHERE progetto = ".$_POST['_i']);
        	foreach ($ref as $f){
        		$db->executeUpdate("INSERT INTO rb_responsabili_progetto (progetto, docente, anno) VALUES (".$_POST['_i'].", $f, $anno)");
        	}
        	$db->executeUpdate("COMMIT");
        } catch (MySQLException $ex){
        	$db->executeUpdate("ROLLBACK");
        	print "ko|".$ex->getMessage()."|".$ex->getQuery();
        	exit;
        }
        break;
}

print "ok";
exit;
