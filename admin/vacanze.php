<?php
/**
 * elimina dal'archivio i dati relativi ai giorni di vacanza in reg_classe e reg_alunni
 * per pulizia dell'archivio e interventi posteriori al popolamento delle tabelle
 */

require_once "../lib/start.php";

check_session();
//check_permission(ADM_PERM);

foreach($holydays as $day){
	$del1 = "DELETE FROM rb_reg_alunni WHERE id_registro IN (SELECT id_reg FROM rb_reg_classi WHERE data = '".$day."')";
	$r1 = $db->executeUpdate($del1);
	$del2 = "DELETE FROM rb_reg_classi WHERE data = '".$day."'";
	$r2 = $db->executeUpdate($del2);
	print "Cancellata $day<br />";
}