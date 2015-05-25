<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro personale</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_print.css" type="text/css" media="print" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var stid = 0
		var subject = <?php echo $_SESSION['__materia__'] ?>;

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

		var check_form = function(){
			var ind = 0;
			var msg = "Il modulo non e' stato compilato correttamente. Sono stati riscontrati i seguenti errori:\n";
			var bool = true;
			if($('#voto').val() == "0"){
				ind++;
				msg += "\n"+ind+". Voto non inserito";
				$("#lab1").css({color: "#ff0000"});
				bool = false;
			}
			else {
				$("#lab1").css({color: "inherit"});
			}
			if($('#data_voto').val() == ""){
				ind++;
				msg += "\n"+ind+". Data non inserita";
				$("#lab3").css({color: "#ff0000"});
				bool = false;
			}
			else {
				$("#lab3").css({color: "inherit"});
			}
			if($('#tipo').val() == "0"){
				ind++;
				msg += "\n"+ind+". Tipologia di voto non inserita";
				$("#lab4").css({color: "#ff0000"});
				bool = false;
			}
			else {
				$("#lab4").css({color: "inherit"});
			}
			if($('#descrizione').val() == ""){
				ind++;
				msg += "\n"+ind+". Descrizione della prova non inserita";
				$("#lab5").css({color: "#ff0000"});
				bool = false;
			}
			else {
				$("#lab5").css({color: "inherit"});
			}
			if($('#argomento').val() == ""){
				ind++;
				msg += "\n"+ind+". Argomento della prova non inserito";
				$("#lab6").css({color: "#ff0000"});
				bool = false;
			}
			else {
				$("#lab6").css({color: "inherit"});
			}
			if(!bool)
				j_alert("error", msg);
			return bool;
		};

		var change_subject = function(id){
			document.location.href="index.php?subject="+id+"&q=<?php echo $q ?>";
		};

		var student = function(sid, quad){
			document.forms[0].action = "student.php?stid="+sid+"&q="+quad;
			document.forms[0].submit();
		};

		var show_menu = function(e, _stid, offset, show_context_menu){
			if (show_context_menu == 0) {
				return false;
			}
			if ($('#context_menu').is(":visible")) {
				$('#context_menu').slideUp(300);
				return;
			}
			$('#context_menu').css({'top': offset.top+"px"});
		    $('#context_menu').css({'left': offset.left+"px"});
		    $('#context_menu').slideDown(500);
		    stid = _stid;
		    return false;
		};

		var new_grade = function(){
			$('#context_menu').hide();
			$('#mark').dialog({
				autoOpen: true,
				show: {
					effect: "fade",
					duration: 500
				},
				hide: {
					effect: "fade",
					duration: 300
				},
				modal: true,
				width: 450,
				title: 'Nuovo voto',
				open: function(event, ui){

				}
			});
		};

		var grades = function(q){
			student(stid, q);
		};

		var add_note = function(q){
			$('#context_menu').hide();
			$('#stid').val(stid);
			$('#ndate').val('<?php echo date("d/m/Y") ?>');
			$('#pop_note').dialog({
				autoOpen: true,
				show: {
					effect: "fade",
					duration: 500
				},
				hide: {
					effect: "fade",
					duration: 300
				},
				modal: true,
				width: 450,
				title: 'Nuova nota',
				open: function(event, ui){

				}
			});
		};

		var notes = function(q){
			document.forms[0].action = "student_notes.php?stid="+stid+"&q="+q;
			document.forms[0].submit();
		};

		var register_grade = function(){
			if (!check_form()) {
				return false;
			}
			var url = "grade_manager.php";
			$.ajax({
				type: "POST",
				url: url,
				data:  {action: 'new',
						voto: $('#voto').val(),
						id_alunno: stid,
						data_voto: $('#data_voto').val(),
						descrizione: $('#descrizione').val(),
						tipologia: $('#tipo').val(),
						argomento: $('#argomento').val(),
						note: $('#note').val(),
						privato: $('#privato').val(),
						verifica: 0,
						q: <?php echo $q ?>
				},
				dataType: 'json',
				error: function(data, status, errore) {
					j_alert("error", "Si e' verificato un errore");
					return false;
				},
				succes: function(result) {
					j_alert("alert", "ok");
				},
				complete: function(data, status){
					r = data.responseText;
					var json = $.parseJSON(r);
					if(json.status == "kosql"){
						j_alert("error", "Errore SQL. \nQuery: "+json.query+"\nErrore: "+json.message);
						return;
					}
					else {
						$('#mark').dialog("close");
						$('#avgtipo'+$('#tipo').val()+'_'+stid).fadeOut(200);
						$('#numtipo'+$('#tipo').val()+'_'+stid).fadeOut(200);
						$('#avg_'+stid).fadeOut(200);
						$('#numvoti_'+stid).fadeOut(200);
						setTimeout(function(){
							if (json.all.avg >= 6) {
								$('#avg_'+stid).removeClass("attention");
							}
							else {
								$('#avg_'+stid).addClass("attention");
							}
							$('#avgtipo'+$('#tipo').val()+'_'+stid).text(json.data.avg);
							$('#numtipo'+$('#tipo').val()+'_'+stid).text(json.data.count);
							$('#avg_'+stid).text(json.all.avg);
							$('#numvoti_'+stid).text(json.all.count);
							$('#avgtipo'+$('#tipo').val()+'_'+stid).fadeIn(400);
							$('#numtipo'+$('#tipo').val()+'_'+stid).fadeIn(400);
							$('#avg_'+stid).fadeIn(400);
							$('#numvoti_'+stid).fadeIn(400);
						}, 200);
					}
				}
			});
		};

		var register_note = function(){
			var url = "note_manager.php";
			$.ajax({
				type: "POST",
				url: url,
				data:  $('#testform').serialize(true),
				dataType: 'json',
				error: function(data, status, errore) {
					j_alert("error", "Si e' verificato un errore");
					return false;
				},
				succes: function(result) {
					j_alert("alert", "ok");
				},
				complete: function(data, status){
					r = data.responseText;
					var json = $.parseJSON(r);
					if(json.status == "kosql"){
						j_alert("error", "Errore SQL. \nQuery: "+json.query+"\nErrore: "+json.message);
						return;
					}
					else {
						$('#pop_note').dialog("close");
					}
				}
			});
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#context_menu').mouseleave(function(event){
				$('#context_menu').hide();
			});
			$('#data_voto').datepicker({
				dateFormat: "dd/mm/yy",
				altFormat: "dd/mm/yy"
			});
			$('#ndate').datepicker({
				dateFormat: "dd/mm/yy",
				altFormat: "dd/mm/yy"
			});
			$('#subm').click(function(event){
				event.preventDefault();
				register_grade();
			});
			$('.st_link').click(function(event){
				var offset = $(this).offset();
				offset.top = offset.top + $(this).height();
				var stid = $(this).attr("data-id");
				var esonerato = $(this).attr("data-esonerato");
				var show_context_menu = 1;
				if (esonerato == 1 && (subject == 26 || subject == 30)) {
					show_context_menu = 0;
				}
				show_menu(event, stid, offset, show_context_menu);
			});
			$('#overlay').click(function(event) {
				if ($('#overlay').is(':visible')) {
					show_drawer(event);
				}
				$('#other_drawer').hide();
				$('#classeslist_drawer').hide();
			});
			$('.drawer_label span').click(function(event){
				var off = $(this).parent().offset();
				show_classlist(event, off);
			}).css({
				cursor: "pointer"
			});
			$('#showsub').click(function(event){
				var off = $(this).parent().offset();
				_show(event, off);
			});
		});

		var show_classlist = function(e, off) {
			if ($('#classeslist_drawer').is(":visible")) {
				$('#classeslist_drawer').hide('slide', 300);
				return;
			}
			var offset = $('#drawer').offset();
			var top = off.top;

			var left = offset.left + $('#drawer').width() + 1;
			$('#classeslist_drawer').css({top: top+"px", left: left+"px", zIndex: 1000});
			$('#classeslist_drawer').show('slide', 300);
			return true;
		};
	</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<?php
$label_subject = "";
if (count($materie) > 1) {
?>
	<div class="mdtabs">
	<?php
	foreach ($materie as $mat) {
		if (isset($_SESSION['__materia__']) && $_SESSION['__materia__'] == $mat['id']) {
			$label_subject = "::".$mat['mat'];
		}
		if ($mat['mat'] == "Materia alternativa") {
			$mat['mat'] = "Mat. alt.";
		}
	?>
		<div class="mdtab<?php if (isset($_SESSION['__materia__']) && $_SESSION['__materia__'] == $mat['id']) echo " mdselected_tab" ?>">
			<a href="#" onclick="change_subject(<?php echo $mat['id'] ?>)"><span><?php echo $mat['mat'] ?></span></a>
		</div>
	<?php
	}
	?>
	</div>
<?php
}

?>
<form action="student.php" method="post">
<?php
setlocale(LC_TIME, "it_IT.utf8");
$giorno_str = strftime("%A", strtotime(date("Y-m-d")));
?>
<table class="registro">
<thead>
<tr class="head_tr_no_bg">
	<td colspan="<?php echo $tot_col ?>" style="text-align: center; border-top: 0"><span id="ingresso" style=""><?php print $_SESSION['__classe__']->to_string() ?><?php echo $label_subject ?></span></td>
</tr>
<tr class="title_tr">
	<td rowspan="2" style="width: 40%; font-weight: bold; padding-left: 8px">Alunno</td>
	<td colspan="2" style="width: 20%; text-align: center; font-weight: bold">Totale</td>
	<?php
	foreach ($labels as $k => $lab){
	?>
	<td colspan="<?php echo $col_colsp ?>" style="width: <?php echo $double_len ?>%; text-align: center; font-weight: bold"><?php echo $lab ?></td>
	<?php } ?>
</tr>
<tr class="title_tr">
	<td style="width: 10%; text-align: center; font-weight: bold">Media</td>
	<td style="width: 10%; text-align: center; font-weight: bold">N. voti</td>
	<?php
	foreach ($prove as $k => $p){
	?>
	<td style="width: <?php echo $len ?>%; text-align: center; font-weight: bold">Media</td>
	<td style="width: <?php echo $len ?>%; text-align: center; font-weight: bold">N. voti</td>
	<?php } ?>
</tr>
</thead>
<tbody>
<?php

$idx = 0;
$stringhe_voto = array();
$tot_classe = 0;
$t_voti = 0;
$studenti = array();
$medie_classe = array("scr" => 0, "ora" => 0, "tot" => 0);
while($al = $res_alunni->fetch_assoc()){
	$esonerato = 0;
	if (in_array($al['id_alunno'], $esonerati)) {
		$esonerato = 1;
	}
	$st = array();
	$st['id'] = $al['id_alunno'];
	$st['value'] = $al['cognome']." ".$al['nome'];

	if ($_SESSION['__materia__'] == 46 || $_SESSION['__materia__'] == 47) {
		if ($esonerato == 1) {
			array_push($studenti, $st);
		}
	}
	else if ($esonerato == 0) {
		array_push($studenti, $st);
	}

	foreach ($vars as $k => $vv){
		$vars[$k]["num_prove"] = 0;
		$vars[$k]["media"] = 0;
		$vars[$k]["somma"] = 0;
	}

	if (count($selected) > 0){
		$sel_voti = "SELECT rb_voti.* FROM rb_voti WHERE tipologia IN (".join(",", $selected).") AND rb_voti.alunno = ".$al['id_alunno']." AND materia = ".$_SESSION['__materia__']." AND anno = ".$_SESSION['__current_year__']->get_ID()." $int_time ORDER BY data_voto DESC";
	}
	else{
		$sel_voti = "SELECT rb_voti.* FROM rb_voti, rb_tipologia_prove WHERE rb_voti.tipologia = rb_tipologia_prove.id AND rb_tipologia_prove.default = 1 AND rb_voti.alunno = ".$al['id_alunno']." AND materia = ".$_SESSION['__materia__']." AND anno = ".$_SESSION['__current_year__']->get_ID()." $int_time ORDER BY data_voto DESC";
	}

	try{
		$res_voti = $db->executeQuery($sel_voti);
	} catch (MySQLException $ex){
		$ex->redirect();
	}

	$totale = 0;
	if($res_voti->num_rows < 1){
		$media = "--";
		$stringhe_voto[$idx] = "";
		--$numero_alunni;
		reset($totali_classe);
		while(list($k, $v) = each($totali_classe)){
			$totali_classe[$k]['num_alunni']--;
		}
		$num_voti = 0;
	}
	else{

		$media_orali = $media_scritti = $media = 0;
		$num_voti = $res_voti->num_rows;
		// stringa per il title del link
		$stringhe_voto[$idx] = "<p style='padding: 10px'>";
		while($row = $res_voti->fetch_assoc()){
			$totale += $row['voto'];
			$tot_classe += $row['voto'];
			/*
			if($row['tipologia'] == "S"){
				$tot_scritti += $row['voto'];
				$num_scritti++;
				$t_scr++;
				$t_voti++;
			}
			else if($row['tipologia'] == "O"){
				$tot_orali += $row['voto'];
				$num_orali++;
				$t_ora++;
				$t_voti++;
			}
			*/
			$vars[$row['tipologia']]['num_prove']++;
			$vars[$row['tipologia']]['somma'] += $row['voto'];
			$totali_classe[$row['tipologia']]['num_prove']++;
			//$totali_classe[$row['tipologia']]['somma'] += $row['voto'];
			$t_voti++;
			$stringhe_voto[$idx] .= format_date($row['data_voto'], SQL_DATE_STYLE, IT_DATE_STYLE, "/")."&nbsp;&nbsp;&nbsp;<span style='font-weight: bold'>".$row['voto']."</span><br />";
		}
		foreach ($vars as $k => $vs){
			if ($vs['num_prove'] == 0){
				$totali_classe[$k]['num_alunni']--;
			}
			else {
				$totali_classe[$k]['somma'] += $vs['somma'] / $vs['num_prove'];
			}
		}
		$media = round(($totale / $num_voti), 2);
		if(isset($num_orali) && $num_orali > 0 ) $media_orali = round(($tot_orali / $num_orali), 2);
		if(isset($num_scritti) && $num_scritti > 0) $media_scritti = round(($tot_scritti / $num_scritti), 2);
		$medie_classe['scr'] += $media_scritti;
		$medie_classe['ora'] += $media_orali;
		$medie_classe['tot'] += $media;
		$stringhe_voto[$idx] .= "</p>";
	}
	$sel_note = "SELECT COUNT(id_nota) FROM rb_note_didattiche WHERE alunno = ".$al['id_alunno']." AND materia = ".$_SESSION['__materia__']." $note_time ORDER BY data DESC";
	$num_note = $db->executeCount($sel_note);
	$_media = round($media);
	if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
		if ($_media == 0) {
			$_media = "--";
		}
		else if($_media < 5.5){
			$_media = 4;
		}
		else if ($_media > 6.49 && $_media < 8){
			$_media = 8;
		}

		if ($_media != "--") {
			$_voto = $voti_religione[$_media];
		}
		else {
			$_voto = $_media;
		}
	}
	else{
		$_voto = $media;
	}
	if (($_SESSION['__materia__'] == 46 || $_SESSION['__materia__'] == 47) && $esonerato == 0) {
		continue;
	}
?>
<tr id="tr<?php echo $al['id_alunno'] ?>">
	<td style="width: 40%; padding-left: 8px; font-weight:bold; <?php if($esonerato == 1 && ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30)) echo "background-color: #DDDDDD" ?>"><?php if($idx < 9) print "&nbsp;&nbsp;"; ?><?php echo ($idx+1).". " ?>
		<a href="#" data-id="<?php echo $al['id_alunno'] ?>" data-esonerato="<?php echo $esonerato ?>" class="st_link" style="font-weight: normal; color: inherit; padding-left: 8px"><?php print $al['cognome']." ".$al['nome']?></a>
		<?php if($num_note > 0){?><!-- &nbsp;(<?php echo $num_note ?> note didattiche) --><?php } ?>
	</td>
	<?php
	if (in_array($al['id_alunno'], $esonerati) && ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30)) {
	?>
	<td colspan="<?php echo ($tot_col - 1) ?>" class="_bold _center" style="background-color: #DDDDDD">Esonerato</td>
	<?php
	}
	else {
	?>
		<td style="width: 10%; text-align: center; font-weight: bold;">
			<span id="avg_<?php echo $al['id_alunno']; ?>" class="<?php if ($media < $_SESSION['__config__']['limite_sufficienza'] && $media > 0) print("attention") ?>"><?php print $_voto ?></span>
		</td>
		<td id="numvoti_<?php echo $al['id_alunno']; ?>"
		    style="width: 10%; text-align: center; font-weight: bold;"><?php print $num_voti ?>
		</td>
		<?php
		foreach ($vars as $k => $vs) {
			if ($vs['num_prove'] > 0) {
				$sp_media = round(($vs['somma'] / $vs['num_prove']), 2);
			}
			else {
				$sp_media = 0;
			}
			$_media = round($sp_media);
			if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30) {
				if ($_media < 5.5) {
					$_media = 4;
				}
				else {
					if ($_media > 6.49 && $_media < 8) {
						$_media = 8;
					}
				}
				$_voto = $voti_religione[$_media];
			}
			else {
				$_voto = $sp_media;
			}
			?>
			<td id="avgtipo<?php echo $k ?>_<?php echo $al['id_alunno']; ?>" style="width: <?php echo $len ?>%; text-align: center; font-weight: bold;"><?php if ($vs['num_prove'] < 1) { echo "--"; } else { echo $_voto; } ?>
			</td>
			<td id="numtipo<?php echo $k ?>_<?php echo $al['id_alunno']; ?>" style="width: <?php echo $len ?>%; text-align: center; font-weight: bold;"><?php echo $vs['num_prove'] ?></td>
		<?php
		}
		?>
	<?php
	}
	?>
</tr>
<?php
	$idx++;
}
$_SESSION['students'] = $studenti;
?>
<?php
$m_tot = $m_scr = $m_ora = 0;
if($numero_alunni > 0){
	$m_tot = round(($medie_classe['tot'] / $numero_alunni), 2);
	$m_scr = round(($medie_classe['scr'] / $numero_alunni), 2);
	$m_ora = round(($medie_classe['ora'] / $numero_alunni), 2);

	$_media = round($m_tot);
	if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
		if ($_media == 0) {
			$_media = "--";
		}
		else if($_media < 5.5){
			$_media = 4;
		}
		else if ($_media > 6.49 && $_media < 8){
			$_media = 8;
		}
		if ($_media != "--") {
			$_voto = $voti_religione[$_media];
		}
		else {
			$_voto = $_media;
		}
	}
	else{
		$_voto = $m_tot;
	}
}
else {
	$_voto = "--";
}
?>
</tbody>
<tfoot>
<tr class="riepilogo">
	<td style="width: 40%; font-weight: bold; padding-left: 8px">
		Totale classe
	</td>
	<td style="width: 10%; text-align: center; font-weight: bold"><span class="<?php if($medie_classe['tot'] > 0 && $medie_classe['tot'] < $_SESSION['__config__']['limite_sufficienza']) print("attention") ?>"><?php echo $_voto ?></span></td>
	<td style="width: 10%; text-align: center; font-weight: bold"></td>
	<?php
	foreach ($totali_classe as $tc){
		if ($tc['num_alunni'] > 0){
			$sp_media = round(($tc['somma'] / $tc['num_alunni']), 2);
		}
		else {
			$sp_media = 0;
		}

		$_media = round($sp_media);
		if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
			if($_media < 5.5){
				$_media = 4;
			}
			else if ($_media > 6.49 && $_media < 8){
				$_media = 8;
			}
			$_voto = $voti_religione[$_media];
		}
		else{
			$_voto = $sp_media;
		}
	?>
	<td style="width: <?php echo $len ?>%; text-align: center; font-weight: bold"><?php if($tc['num_alunni'] < 1) echo "--"; else echo $_voto; ?></td>
	<td style="width: <?php echo $len ?>%; text-align: center; font-weight: bold"><?php //echo $tc['somma']." / ".$tc['num_alunni'] ?></td>
	<?php } ?>
</tr>
<tr>
	<td colspan="<?php echo $tot_col ?>" style="text-align: right; font-weight: bold; margin-right: 30px">&nbsp;
	</td>
</tr>
<tr class="nav_tr">
	<td colspan="<?php echo $tot_col ?>" style="text-align: center; height: 40px">
		<input type="hidden" name="id_materia" value="<?php if (isset($idm)) echo $idm ?>" />
		<input type="hidden" name="materia" value="<?php if (isset($_mat)) echo $_mat ?>" />
			<a href="index.php?q=1&subject=<?php echo $_SESSION['__materia__'] ?>" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
				<img style="margin-right: 5px; position: relative; top: 2px" src="../../../images/24.png" />1 Quadrimestre
			</a>
			<a href="index.php?q=2&subject=<?php echo $_SESSION['__materia__'] ?>" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
				<img style="margin-right: 5px; position: relative; top: 2px" src="../../../images/24.png" />2 Quadrimestre
			</a>
			<a href="index.php?q=0&subject=<?php echo $_SESSION['__materia__'] ?>" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
				<img style="margin-right: 5px; position: relative; top: 2px" src="../../../images/24.png" />Totale
			</a>
		<!-- <a href="index.php?q=1">1 Quadrimestre</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="index.php?q=2">2 Quadrimestre</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="index.php?q=0">Totale</a> -->
	</td>
</tr>
</tfoot>
</table>
</form>
<p></p>
</div>
<!-- menu contestuale -->
    <div id="context_menu" style="position: absolute; width: 170px; height: 70px; display: none">
    	<a style="font-weight: normal" href="#" onclick="grades(<?php echo $q ?>)">Elenco voti</a><br />
    	<a style="font-weight: normal" href="#" onclick="new_grade(<?php echo $q ?>)">Nuovo voto</a><br />
    	<a style="font-weight: normal" href="#" onclick="notes(<?php echo $q ?>)">Elenco note</a><br />
    	<a style="font-weight: normal; margin-bottom: 10px; display: block" href="#" onclick="add_note(<?php echo $q ?>)">Nuova nota</a>
    </div>
<!-- fine menu contestuale -->
<!--
codice per il popup nuovo voto
-->
<div id="mark" style="display: none">
	<p style='text-align: center; padding-top: 5px; font-weight: bold' id='titolo'>Nuovo voto</p>
	<form id='myform' action='' method='post'>
		<table style='text-align: left; width: 95%; margin: auto' id='att'>
			<tr>
				<td style='width: 25%' id='lab1'>Voto *</td>
				<td style='width: 75%'>
					<select name='voto' id='voto' style='font-size: 11px; width: 33%'>
						<option value='0'>Seleziona</option>
						<?php
						if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
							foreach ($voti_religione as $k => $g){
								?>
								<option value='<?php echo $k ?>'><?php echo $g ?></option>
							<?php
							}
						}
						else {
							$i = 100;
							while($i > 9){
								?>
								<option value='<?php print ($i / 10) ?>' <?php if(isset($voto) && $voto['voto'] == ($i / 10)) echo "selected" ?>><?php print ($i / 10) ?></option>
								<?php
								$i -= 5;
							}
						}
						?>
					</select>&nbsp;&nbsp;&nbsp;
					<span style='width: 30%; margin-right: 5px' id='lab2'>Privato</span>
					<select id="privato" style='width: 30%; font-size: 11px; padding-top: 3px; margin-left: 30px' name='privato'>
						<option value="0">No</option>
						<option value="1">Si</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style='width: 25%' id='lab3'>Data *</td>
				<td>
					<input id='data_voto' type='text' style='text-align: right; width: 33%; padding-top: 3px' name='data_voto' />

					<span style='margin-right: 42px; margin-left: 10px' id='lab4'>Tipo *</span>
					<select id='tipo' name='tipo' style='font-size: 11px; width: 30%'>
						<option value='0'>Seleziona</option>
						<?php
						$res_prove->data_seek(0);
						while($row = $res_prove->fetch_assoc()){
							?>
							<option value="<?php echo $row['id'] ?>" <?php if (isset($voto['tipologia']) && ($row['id'] == $voto['tipologia'])) echo "selected" ?>><?php echo $row['label'] ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td style='width: 25%' id='lab5'>Prova *</td>
				<td>
					<input value="" name='descrizione' id='descrizione' type='text' style='width: 100%; font-size: 11px' />
				</td>
			</tr>
			<tr>
				<td style='width: 25%' id='lab6'>Argomento *</td>
				<td>
					<textarea style='width: 100%; height: 40px; font-size: 11px' name='argomento' id="argomento"></textarea>
				</td>
			</tr>
			<tr>
				<td style='width: 25%'>Note</td>
				<td>
					<textarea style='width: 100%; height: 40px; font-size: 11px' name='note' id="note"></textarea>
					<input type='hidden' name='id_materia' value='<?php print $_SESSION['__materia__'] ?>' />
					<input type='hidden' name='alunno' value='<?php if(isset($_REQUEST['alunno'])) echo $_REQUEST['alunno'] ?>' />
				</td>
			</tr>
		</table>
		<div style='width: 95%; text-align: right; margin: 20px 0 20px 0'>
			<input type="button" id="subm" value="Invia" style="width: 50px; font-size: 11px; padding: 2px" />
			<input type='hidden' name='ia' />
		</div>
	</form>
</div>
<!-- popup nota -->
<div id="pop_note" style="display: none">
	<p style='text-align: center; padding-top: 5px; font-weight: bold; padding-bottom: 10px' id='titolo_nota'>Note didattiche</p>
	<form id='testform' method='post' onsubmit="_submit()">
		<table style='text-align: left; width: 95%; margin: auto' id='att'>
			<tr>
				<td style="width: 25%; font-weight: bold">Tipo nota *</td>
				<td style="width: 75%; " colspan="3">
					<select id="ntype" name="ntype" style="font-size: 11px; border: 1px solid gray; width: 100%">
						<?php
						while($t = $res_types->fetch_assoc()){
							?>
							<option value="<?php echo $t['id_tiponota'] ?>"><?php echo utf8_decode($t['descrizione']) ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td style="width: 25%; font-weight: bold">Data *</td>
				<td style="width: 75%; font-weight: normal" colspan="3">
					<input type="hidden" name="action" id="action" value="new" />
					<input type="hidden" name="id_nota" id="id_nota" value="0" />
					<input type="hidden" name="stid" id="stid" value="" />
					<input type="text" style="font-size: 11px; border: 1px solid gray; width: 99%" id="ndate" name="ndate" readonly="readonly" value="" />
				</td>
			</tr>
			<tr>
				<td style="width: 25%; font-weight: bold">Note </td>
				<td style="width: 75%; " colspan="3">
					<textarea style="width: 100%; height: 40px; font-size: 11px; border: 1px solid gray" id="desc" name="desc"></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="4" style="padding-top: 20px; text-align: right;">
					<input type="button" id="manage_link" onclick="register_note()" value="Registra" style="width: 70px; padding: 2px" />
				</td>
			</tr>
			<tr>
				<td colspan="4" style="height: 10px"></td>
			</tr>
		</table>
	</form>
</div>
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_label"><span>Classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></span></div>
		<?php if(count($_SESSION['__subjects__']) > 1){ ?>
		<div class="drawer_link submenu">
			<a href="summary.php"><img src="../../../images/10.png" style="margin-right: 10px; position: relative; top: 5%"/>Riepilogo</a>
		</div>
		<?php
		}
		if($_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID()) || $_SESSION['__user__']->getUsername() == 'rbachis') { ?>
		<div class="drawer_link submenu">
			<a href="dettaglio_medie.php"><img src="../../../images/9.png" style="margin-right: 10px; position: relative; top: 5%"/>Dettaglio classe</a>
		</div>
		<?php
		}
		?>
		<?php if($is_teacher_in_this_class && $_SESSION['__user__']->getSubject() != 27 && $_SESSION['__user__']->getSubject() != 44) { ?>
		<div class="drawer_link submenu separator">
			<a href="#" id="showsub"><img src="../../../images/68.png" style="margin-right: 10px; position: relative; top: 5%"/>Altro</a>
		</div>
		<div class="drawer_link submenu"><a href="../registro_classe/registro_classe.php?data=<?php echo date("Y-m-d") ?>"><img src="../../../images/28.png" style="margin-right: 10px; position: relative; top: 5%" />Registro di classe</a></div>
		<div class="drawer_link submenu separator"><a href="../gestione_classe/classe.php"><img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />Gestione classe</a></div>
		<div class="drawer_link"><a href="../index.php"><img src="../../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="../profile.php"><img src="../../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../../modules/documents/load_module.php?module=docs&area=teachers"><img src="../../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=teachers"><img src="<?php echo $_SESSION['__path_to_root__'] ?>images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
	<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../../shared/do_logout.php"><img src="../../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<div id="other_drawer" class="drawer" style="height: <?php echo $other_drawer_height ?>px; display: none; position: absolute">
	<?php if (!isset($_REQUEST['__goals__']) && (isset($_SESSION['__user_config__']['registro_obiettivi']) && (1 == $_SESSION['__user_config__']['registro_obiettivi'][0]))): ?>
	<div class="drawer_link">
		<a href="index.php?q=<?php echo $q ?>&subject=<?php echo $_SESSION['__materia__'] ?>&__goals__=1"><img src="../../../images/31.png" style="margin-right: 10px; position: relative; top: 5%"/>Registro per obiettivi</a>
	</div>
	<?php endif; ?>
	<?php if ($ordine_scuola == 1): ?>
	<div class="drawer_link">
		<a href="absences.php"><img src="../../../images/52.png" style="margin-right: 10px; position: relative; top: 5%"/>Assenze</a>
	</div>
	<?php endif; ?>
	<div class="drawer_link">
		<a href="tests.php"><img src="../../../images/79.png" style="margin-right: 10px; position: relative; top: 5%"/>Verifiche</a>
	</div>
	<div class="drawer_link">
		<a href="riepilogo_verifiche.php"><img src="../../../images/69.png" style="margin-right: 10px; position: relative; top: 5%"/>Riepilogo verifiche</a>
	</div>
	<div class="drawer_link">
		<a href="lessons.php"><img src="../../../images/62.png" style="margin-right: 10px; position: relative; top: 5%"/>Lezioni</a>
	</div>
	<div class="drawer_link separator">
		<a href="scrutini.php?q=<?php echo $_q ?>"><img src="../../../images/34.png" style="margin-right: 10px; position: relative; top: 5%"/>Scrutini</a>
	</div>
	<?php
	}
	else { ?>
	<div class="drawer_link separator">
		<a href="scrutini_classe.php?q=<?php echo $_q ?>"><img src="../../../images/34.png" style="margin-right: 10px; position: relative; top: 5%"/>Scrutini</a>
	</div>
	<?php } ?>
</div>
<div id="classeslist_drawer" class="drawer" style="height: <?php echo (36 * (count($_SESSION['__user__']->getClasses()) - 1)) ?>px; display: none; position: absolute">
	<?php
	foreach ($_SESSION['__user__']->getClasses() as $cl) {
		if ($cl['id_classe'] != $_SESSION['__classe__']->get_ID()) {
			?>
			<div class="drawer_link ">
				<a href="<?php echo getFileName() ?>?reload=1&cls=<?php echo $cl['id_classe'] ?>">
					<img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%"/>
					Classe <?php echo $cl['classe'] ?>
				</a>
			</div>
		<?php
		}
	}
	?>
</div>
</body>
</html>
