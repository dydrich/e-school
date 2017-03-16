<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 3/1/17
 * Time: 5:37 PM
 */
require_once "../../lib/start.php";

check_session();
check_permission(DIR_PERM|DSG_PERM|SEG_PERM);

$_SESSION['__path_to_root__'] = "../../";
$_SESSION['__path_to_mod_home__'] = "./";

$response = array("status" => "ok", "message" => "Operazione completata");

$anno = $_SESSION['__current_year__']->get_ID();

switch ($_POST['action']) {
	case 'create_records':
		/*
		 * commissione d'esame:
		 * comprende tutti i docenti delle classi terze
		 */
		$sel_cls = "SELECT anno_corso, sezione, id_classe 
			FROM rb_classi 
			WHERE anno_corso = 3 
			AND ordine_di_scuola = 1 
			AND rb_classi.anno_scolastico = $anno 
			ORDER BY sezione ";
		$res_cls = $db->executeQuery($sel_cls);
		$idx = 1;
		while ($r = $res_cls->fetch_assoc()) {
			$idc = $db->executeUpdate("INSERT INTO rb_commissioni_esame (numero, classe, sezione, anno) 
								VALUES ($idx, {$r['id_classe']}, '{$r['sezione']}', $anno)");
			$idx++;
			$sel_comm = "SELECT DISTINCT(uid) AS uid
			FROM rb_cdc, rb_utenti 
			WHERE id_docente = uid 
			AND id_anno = $anno 
			AND rb_cdc.id_classe = {$r['id_classe']}
			AND id_materia <> 26 
			ORDER BY cognome, nome";
			try{
				$res_comm = $db->executeQuery($sel_comm);
			} catch (MySQLException $ex){
				$ex->redirect();
			}
			while ($row = $res_comm->fetch_assoc()) {
				$db->executeUpdate("INSERT INTO rb_docenti_commissione_esame (commissione, docente, sostituto) VALUES ($idc, {$row['uid']}, NULL)");
			}

			$sel_sost = "SELECT DISTINCT(uid) AS uid 
			FROM rb_utenti, rb_assegnazione_sostegno 
			WHERE anno = {$anno} 
			AND docente = uid 
			AND classe = {$r['id_classe']}";
			try{
				$res_sost = $db->execute($sel_sost);
			} catch (MySQLException $ex){
				$ex->redirect();
			}
			while ($row = $res_sost->fetch_assoc()) {
				$db->executeUpdate("INSERT INTO rb_docenti_commissione_esame (commissione, docente, sostituto) VALUES ($idc, {$row['uid']}, NULL)");
			}
		}
		break;
}

$res = json_encode($response);
echo $res;
exit;
