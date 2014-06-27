<?php

require_once "../../../lib/start.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();
check_permission(DOC_PERM);

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$sid = $_REQUEST['sid'];

$sel_alunni = "SELECT rb_alunni.*, indirizzo, telefono1, telefono2, telefono3, email, messenger, blog FROM rb_alunni LEFT JOIN rb_indirizzi_alunni ON rb_alunni.id_alunno = rb_indirizzi_alunni.id_alunno LEFT JOIN rb_profili_alunni ON rb_indirizzi_alunni.id_alunno = rb_profili_alunni.id_alunno WHERE rb_alunni.id_alunno = $sid";
$res_alunno = $db->execute($sel_alunni);
$alunno = $res_alunno->fetch_assoc();

?>
<style>
tbody tr:hover { background-color: rgba(211, 222, 199, 0.6); }
</style>
<div style='font-weight: bold; font-size: 12px; text-align: center; margin-top: 10px' class='Titolo'>Profilo di <?php print $alunno['nome']." ".$alunno['cognome'] ?></div>
<div style='text-align: left; font-weight: normal; font-size: 11px; padding: 10px; margin-top: 0px; padding-bottom: 5px'>
<table style='width: 95%; margin: auto; '>
	<tr><td colspan='2' style='padding-bottom: 10px; '></tr>
<?php 
while(list($k, $v) = each($alunno)){
	$print = false;
	switch($k){
		case 'data_nascita':
			$k2 = "Data di nascita";
			$v = format_date($v, SQL_DATE_STYLE, IT_DATE_STYLE, "/");
			$print = true;
			break;
		case "telefono2":
			$k2 = "Cellulare";
			$print = true;
			break;
		case "telefono3":
			$k2 = "Altro telefono";
			$print = true;
			break;
		case "codice_fiscale":
		case "id_alunno":
		case "email":
		case "blog":
		case "messenger":
		case "indirizzo":
			$k2 = ucwords(str_replace("_", " ", $k));
			$print = true;
			break;
		case "ripetente":
			$k2 = ucwords(str_replace("_", " ", $k));
			$print = true;
			if($v == 0) $v = "NO";
			else if($v == 1) $v = "SI";
			break;
		default:
			$print = false;
			break;
	}
			
	if($print){
		
?>
	<tr>
		<td style='width: 30%; font-size: 11px; font-weight: bold; border-bottom: 1px solid #e8eaec'><?php print $k2 ?></td>
		<td style='width: 70%; font-size: 11px; font-weight: normal; border-bottom: 1px solid #e8eaec'><?php print $v ?>
	</tr>
<?php 
	}
}
?>
</table>
</div>