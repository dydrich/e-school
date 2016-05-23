<?php

require_once '../../lib/RBUtilities.php';

$ordine_scuola = $_SESSION['__classe__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

switch($quadrimestre){
	case 0:
		$int_time = "AND data_voto <= NOW()";
		$nt_time = "AND data <= NOW()";
		$scr_par = "";
		$drawer_label = "Medie voto totali al ".date("d/m/Y");
		break;
	case 1:
		$int_time = "AND data_voto <= '{$fine_q}'";
		$nt_time = "AND data <= '$fine_q'";
		$scr_par = "AND quadrimestre = {$quadrimestre}";
		$drawer_label = "Medie voto primo quadrimestre";
		break;
	case 2:
		$int_time = "AND (data_voto > '$fine_q' AND data_voto <= NOW()) ";
		$nt_time = "AND (data > '$fine_q' AND data <= NOW()) ";
		$scr_par = "AND quadrimestre = {$quadrimestre}";
		$drawer_label = "Medie voto secondo quadrimestre";
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
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#overlay').click(function(event) {
				if ($('#overlay').is(':visible')) {
					show_drawer(event);
				}
				$('#other_drawer').hide();
			});
			$('#showsub').click(function(event){
				var off = $(this).parent().offset();
				_show(event, off);
			});
		});

		var _show = function(e, off) {
			if ($('#other_drawer').is(":visible")) {
				$('#other_drawer').hide('slide', 300);
				return;
			}
			var offset = $('#drawer').offset();
			var top = off.top;

			var left = offset.left + $('#drawer').width() + 1;
			$('#other_drawer').css({top: top+"px", left: left+"px", zIndex: 1000});
			$('#other_drawer').show('slide', 300);
			return true;
		};

		function show_div(div, subjectID){
			// recupero i voti da visualizzare
			if($('#'+div).is(":hidden")){
				url = "../common/get_marks.php";
				$.ajax({
					type: "POST",
					url: url,
					data: {subjectID: subjectID, q: <?php print $quadrimestre ?>, ric: "<?php echo $area ?>"},
					dataType: 'json',
					error: function() {
						j_alert("error", "Errore di trasmissione dei dati");
					},
					succes: function() {

					},
					complete: function(data){
						$('#'+div).html("");
						r = data.responseText;
						if(r == "null"){
							return false;
						}
						var json = $.parseJSON(r);
						if (json.status == "kosql"){
							alert(json.message);
							console.log(json.dbg_message);
						}
						else if(json.status == "ko") {
							j_alert("error", "Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore al webmaster");
							return;
						}
						else {

							if(json.numero_voti == 0){
								$('#'+div).html("<span style='padding-left: 30px; padding-bottom: 20px'>Nessun voto presente</span>");
							}
							else{
								var html = "<table style='margin-left: 30px; width: 80%; margin-bottom: 20px'>";
								//alert(json.voti.length);
								for(data in json.voti){
									riga = json.voti[data];
									if (riga.data != "" && riga.data != undefined) {
										html += "<tr class='manager_row_xsmall'><td style='width: 20%; '>"+riga.data+"</td><td style='width: 55%; '>"+riga.desc+"</td><td style='width: 25%; text-align: center'>"+riga.voto+"</td></tr>";
										//$('<div style="width: 80%; margin-left: 30px; background-color: rgba(30, 67, 137, .1); border-bottom: 1px solid rgba(30, 67, 137, .2)">'+riga.data+': <span class="_bold _right">'+riga.voto+'</span></div>').appendTo($('#'+div));
									}
								}
								html += "</table>";
								$('#'+div).html(html);
							}
							$('#line_'+subjectID).addClass("_bold");
						}
					}
				});
				$('#'+div).fadeIn(400);
			}
			else{
				$('#'+div).fadeOut(300);
				$('#line_'+subjectID).removeClass("_bold");
			}
		}
	</script>
<style type="text/css">
</style>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "class_working.php" ?>
</div>
<div id="left_col">
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
	if($res_note->num_rows == 0) {
		$note = "--";
	}
	else {
		$note = "<a href='elenco_note_didattiche.php?q=" . $quadrimestre . "&materia=" . $row['id_materia'] . "'>" . $res_note->num_rows . " note</a>";
	}
?>
	<div style="width: 90%; height: auto; margin: auto;" class="_hov">
		<table style="width: 100%; ">
			<tr id="line_<?php print $row['id_materia'] ?>" class="bottom_decoration">
			<td style="padding-left: 20px; width: 55%; height: 20px; color: inherit;">
				<a href="#" onclick="show_div('div_<?php print $row['id_materia'] ?>', <?php print $row['id_materia'] ?>)"><?php print $row['materia'] ?></a>
			</td>
			<td style="text-align: center; width: 15%; height: 20px; " <?php if($vt['avg'] != "-" && $vt['avg'] < 6) print ("class='attention'") ?>><?php print $media ?></td>
			<td style="text-align: center; width: 15%; height: 20px; color: inherit;"><?php print $vt['count'] ?></td>
			<td style="text-align: center; width: 15%; height: 20px; color: inherit;"><?php echo $note ?></td>
			</tr>
		</table>
		<div id="div_<?php print $row['id_materia'] ?>" style="display: none; "></div>
	</div>	
<?php 
	$c++;
}
?>
	<div class="navigate">
		<a href="voti.php?q=1" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 0" src="../../images/24.png" /><span style="top: -3px">1 Quadrimestre</span>
		</a>
		<a href="voti.php?q=2" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
			<img style="margin-right: 5px; position: relative; top: 0" src="../../images/24.png" /><span style="top: -3px">2 Quadrimestre</span>
		</a>
		<a href="voti.php?q=0" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 0" src="../../images/24.png" /><span style="top: -3px">Totale</span>
		</a>
	</div>
</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<?php if ($area == "genitori" && count($_SESSION['__sons__']) > 1): ?>
			<div class="drawer_link separator">
				<a href="#" id="showsub"><img src="../../images/69.png" style="margin-right: 10px; position: relative; top: 5%"/>Seleziona alunno</a>
			</div>
		<?php endif; ?>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=<?php echo $area ?>"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=<?php echo $area ?>"><img src="<?php echo $_SESSION['__path_to_root__'] ?>images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<?php if ($area == "genitori" && count($_SESSION['__sons__']) > 1){
	$height = 36 * (count($_SESSION['__sons__']));
?>
	<div id="other_drawer" class="drawer" style="height: <?php echo $height ?>px; display: none; position: absolute">
		<?php
		$indice = 1;
		reset($_SESSION['__sons__']);
		while(list($key, $val) = each($_SESSION['__sons__'])){
			$cl = "";
			if ($key == $_SESSION['__current_son__']) {
				$cl = " _bold";
			}
			?>
			<div class="drawer_link">
				<a href="<?php print $page ?>?son=<?php print $key ?>" class="<?php echo $cl ?>"><?php print $val[0] ?></a>
			</div>
		<?php
		}
		?>
	</div>
<?php
}
?>
</body>
</html>
