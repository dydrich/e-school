<?php

require_once '../../lib/RBUtilities.php';

$ordine_scuola = $_SESSION['__classe__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

switch($q){
	case 0:
		$int_time = "AND data_voto <= NOW()";
		$nt_time = "AND data <= NOW()";
		$scr_par = "";
		$label = "Medie voto totali al ".date("d/m/Y");
		break;
	case 1:
		$int_time = "AND data_voto <= '{$fine_q}'";
		$nt_time = "AND data <= '$fine_q'";
		$scr_par = "AND quadrimestre = {$q}";
		$label = "Medie voto primo quadrimestre";
		break;
	case 2:
		$int_time = "AND (data_voto > '$fine_q' AND data_voto <= NOW()) ";
		$nt_time = "AND (data > '$fine_q' AND data <= NOW()) ";
		$scr_par = "AND quadrimestre = {$q}";
		$label = "Medie voto secondo quadrimestre";
}

$id_religione = 26;
$id_sostegno = 27;
$comportamento = 2;
if ($ordine_scuola == 2){
	$id_religione = 30;
	$id_sostegno = 41;
	$comportamento = 40;
}
/*
 * se caricati i dati degli scrutini, le materie vanno recuperate da quella tabella
 */
$res_materie = null;
$year = $_SESSION['__current_year__']->get_ID();
$sel_scr = "SELECT id_materia, rb_materie.materia FROM rb_materie, rb_scrutini WHERE rb_materie.id_materia = rb_scrutini.materia {$scr_par} AND anno = {$year} AND classe = {$_SESSION['__classe__']->get_ID()} AND id_materia > 2 AND id_materia NOT IN ($id_sostegno, $comportamento) AND tipologia_scuola = {$ordine_scuola} GROUP BY id_materia, rb_materie.materia ORDER BY posizione_pagella";
//print $sel_scr;
try {
	$res_scr = $db->executeQuery($sel_scr);
} catch (MySQLException $ex) {
	$ex->redirect();
}
if ($res_scr->num_rows < 1) {
	$sel_materie = "SELECT * FROM rb_materie WHERE pagella = 1 AND id_materia > 2 AND id_materia NOT IN ($id_sostegno, $comportamento) AND tipologia_scuola = {$ordine_scuola} ORDER BY id_materia ";
	$res_materie = $db->execute($sel_materie);
}
else {
	$res_materie = $res_scr;
}

$voti_religione = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area genitori</title>
<link rel="stylesheet" href="../teachers/reg.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
function show_div(div, subjectID){
	// recupero i voti da visualizzare
	if($(div).style.display == "none"){
		url = "../common/get_marks.php";
		var req = new Ajax.Request(url,
				  {
				    	method:'post',
				    	parameters: {subjectID: subjectID, q: <?php print $q ?>, ric: "<?php echo $area ?>"},
				    	onSuccess: function(transport){
				      		var response = transport.responseText || "no response text";
				      		if(response == "ko"){
								alert("Dati non accessibili. Riprova tra qualche secondo");
								//alert(response);
								return;
				      		}
				      		//alert(response);
				      		$(div).innerHTML = "";
				      		var dati = response.split(";");
				      		if(dati[1] == "kosql"){
								sqlalert();
								exit;
				      		}
				      		if(dati[1] == 0){
				      			$(div).innerHTML = "<span style='padding-left: 30px; padding-bottom: 20px'>Nessun voto presente</span>";
				      		}
				      		else{
				      			var html = "<table style='margin-left: 30px; width: 80%; margin-bottom: 20px'>";
				      			//alert(html);
					      		for(i = 2; i < dati.length; i++){
						      		riga = dati[i].split("#");
					      			html += "<tr style=''><td style='width: 20%; border-bottom: 1px solid #dddddd'>"+riga[0]+"</td><td style='width: 55%; border-bottom: 1px solid #dddddd'>"+riga[4]+"</td><td style='width: 25%;border-bottom: 1px solid #dddddd; text-align: center'>"+riga[1]+riga[2]+"</td></tr>";
					      		}
					      		html += "</table>";
					      		$(div).innerHTML = html;
				      		}
						    $('line_'+subjectID).setStyle({'backgroundColor': 'rgba(230,230,230,1)'});
				    	},
				    	onFailure: function(){ alert("Si e' verificato un errore..."); }
				  });
		Effect.BlindDown(div, { duration: 2.0 });
	}	
	else{
		Effect.SlideUp(div, { duration: 2.0 });
		$('line_'+subjectID).setStyle({'backgroundColor': ''});
	}
}
</script>
<style type="text/css">
TD{height: 20px}
TD a {text-decoration: none; font-size: 12px}
</style>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
<?php if($area == "genitori") include "sons_menu.php" ?>
<?php include "class_working.php" ?>
</div>
<div id="left_col">
	<div style="width: 90%; height: 30px; margin: 10px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
		<?php echo $label ?>, classe <?php echo $_SESSION['__classe__']->get_anno(),$_SESSION['__classe__']->get_sezione() ?>
	</div>
	<div style="width: 90%; margin: 0 auto 20px auto; height: 30px; text-align: center; font-weight: bold; border: 1px solid rgb(211, 222, 199); outline-style: double; outline-color: rgb(211, 222, 199); background-color: rgba(211, 222, 199, 0.7)">
		<div style="width: 55%; float: left; position: relative; top: 30%">Materia</div>
		<div style="width: 15%; float: left; position: relative; top: 30%">Media voto</div>
		<div style="width: 15%; float: left; position: relative; top: 30%">Numero voti</div>
		<div style="width: 15%; float: left; position: relative; top: 30%">Note</div>
	</div>
<?php 
$c = 1;
while($row = $res_materie->fetch_assoc()){
	if(!$_SESSION['__classe__']->isMusicale()){
		if((isset($row['idpadre']) && $row['idpadre'] == 13) || (isset($row['id_classe']) && $row['id_classe'] == 13))
			continue;
	}
	$sel_voti = "SELECT ROUND(AVG(voto), 2) AS avg, COUNT(voto) AS count FROM rb_voti WHERE alunno = {$alunno} AND anno = ".$_SESSION['__current_year__']->get_ID()." AND materia = ".$row['id_materia']." AND privato = 0 $int_time ";
	//print $sel_voti;
	$res_voti = $db->execute($sel_voti);
	$vt = $res_voti->fetch_assoc();
	if($vt['count'] == 0){
		$media = "-";
	}
	else if ($row['id_materia'] != $id_religione) {
		$media = $vt['avg'];
	}
	else {
		$media = $voti_religione[RBUtilities::convertReligionGrade($vt['avg'])];
	}
		
	$sel_tipi = "SELECT * FROM rb_tipi_note_didattiche ORDER BY id_tiponota";
	$sel_note = "SELECT rb_note_didattiche.*, rb_tipi_note_didattiche.descrizione AS tipo_nota FROM rb_note_didattiche, rb_tipi_note_didattiche WHERE id_tiponota = tipo AND alunno = {$alunno} AND materia = ".$row['id_materia']." AND anno = {$year} $nt_time ORDER BY data DESC";
	$res_note = $db->execute($sel_note);
	if($res_note->num_rows == 0)
		$note = "--";
	else
		$note = "<a href='elenco_note_didattiche.php?q=".$q."&materia=".$row['id_materia']."'>".$res_note->num_rows."</a>";
?>
	<div style="border-bottom: 1px solid #dddddd; width: 90%; height: auto; margin: auto;" class="_hov">
		<table style="width: 100%; ">
			<tr id="line_<?php print $row['id_materia'] ?>">
			<td style="padding-left: 20px; width: 55%; height: 20px; font-weight: normal; color: inherit;">
				<a href="#" onclick="show_div('div_<?php print $row['id_materia'] ?>', <?php print $row['id_materia'] ?>)"><?php print $row['materia'] ?></a>
			</td>
			<td style="text-align: center; width: 15%; height: 20px; font-weight: normal; " <?php if($vt['avg'] != "-" && $vt['avg'] < 6) print ("class='attention'") ?>><?php print $media ?></td>
			<td style="text-align: center; width: 15%; height: 20px; font-weight: normal; color: inherit;"><?php print $vt['count'] ?></td>
			<td style="text-align: center; width: 15%; height: 20px; font-weight: normal; color: inherit;"><?php echo $note ?></td>
			</tr>
		</table>
		<div id="div_<?php print $row['id_materia'] ?>" style="display: none; "></div>
	</div>	
<?php 
	$c++;
}
?>
	<div style="width: 90%; text-align: center; margin: 30px auto 0 auto; height: 35px; border-width: 1px 0 1px 0; border-style: solid; border-color: #dddddd">
		<a href="voti.php?q=1" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 5px" src="../../images/quad.png" />1 Quadrimestre
		</a>
		<a href="voti.php?q=2" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
			<img style="margin-right: 5px; position: relative; top: 5px" src="../../images/quad.png" />2 Quadrimestre
		</a>
		<a href="voti.php?q=0" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 5px" src="../../images/quad.png" />Totale
		</a>
	</div>
</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
