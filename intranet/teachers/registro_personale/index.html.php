<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Registro di classe</title>
<link rel="stylesheet" href="../registro_classe/reg_classe.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../registro_classe/reg_print.css" type="text/css" media="print" />
<link rel="stylesheet" href="../../../modules/communication/theme/style.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../js/jquery_themes/custom-theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">
var stid = 0;
<?php echo $change_subject->getJavascript() ?>

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
		alert(msg);
	return bool;
};

var change_subject = function(id){
	document.location.href="index.php?subject="+id+"&q=<?php echo $q ?>";
};

var student = function(sid, quad){
	document.forms[0].action = "student.php?stid="+sid+"&q="+quad;
	document.forms[0].submit();
};

var show_menu = function(e, _stid){
	if (IE) { 
        tempX = event.clientX + document.body.scrollLeft;
        tempY = event.clientY + document.body.scrollTop;
    } else {  
        tempX = e.pageX;
        tempY = e.pageY;
    }  
    
    if (tempX < 0){tempX = 0;}
    if (tempY < 0){tempY = 0;}  
    $('#context_menu').css({'top': parseInt(tempY)+"px"});
    $('#context_menu').css({'left': parseInt(tempX)+"px"});
    $('#context_menu').show();
    stid = _stid;
    return false;
};

var new_grade = function(){
	//var win = new Window({className: "mac_os_x",  url: "mark.php?q="+quad+"&alunno="+stid+"&action=new&referer=list", width:400, height:250, zIndex: 100, resizable: true, title: "Dettaglio voto", showEffect:Effect.BlindDown, hideEffect: Effect.SwitchOff, draggable:true, wiredDrag: true});
	//win.showCenter(true);
	$('#context_menu').hide();
	$('#mark').dialog({
		autoOpen: true,
		show: {
			effect: "appear",
			duration: 500
		},
		hide: {
			effect: "slide",
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
			effect: "appear",
			duration: 500
		},
		hide: {
			effect: "slide",
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
				$note: $('#note').val(),
				privato: $('#privato').val(),
				verifica: 0,
				q: <?php echo $q ?>
		},
		dataType: 'json',
		error: function(data, status, errore) {
			alert("Si e' verificato un errore");
			return false;
		},
		succes: function(result) {
			alert("ok");
		},
		complete: function(data, status){
			r = data.responseText;
			var json = $.parseJSON(r);
			if(json.status == "kosql"){
				alert("Errore SQL. \nQuery: "+json.query+"\nErrore: "+json.message);
				return;
			}
			else {
				$('#avgtipo'+$('#tipo').val()+'_'+stid).text(json.data.avg);
				$('#numtipo'+$('#tipo').val()+'_'+stid).text(json.data.count);
				$('#avg_'+stid).text(json.all.avg);
				$('#numvoti_'+stid).text(json.all.count);
				$('#mark').dialog("close");
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
			alert("Si e' verificato un errore");
			return false;
		},
		succes: function(result) {
			alert("ok");
		},
		complete: function(data, status){
			r = data.responseText;
			var json = $.parseJSON(r);
			if(json.status == "kosql"){
				alert("Errore SQL. \nQuery: "+json.query+"\nErrore: "+json.message);
				return;
			}
			else {
				$('#pop_note').dialog("close");
			}
		}
	});
};

$(function(){
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
});
</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<!-- div nascosto, per la scelta della materia -->
<?php $change_subject->toHTML() ?>
<form action="student.php" method="post">
<?php
setlocale(LC_TIME, "it_IT");
$giorno_str = strftime("%A", strtotime(date("Y-m-d")));
?>
<table class="registro">
<thead>
<tr class="head_tr">
	<td colspan="<?php echo $tot_col ?>">
		<?php print $_SESSION['__current_year__']->to_string() ?><?php print $label ?>
		<?php if (!isset($_REQUEST['__goals__']) && (isset($_SESSION['__user_config__']['registro_obiettivi']) && (1 == $_SESSION['__user_config__']['registro_obiettivi'][0]))): ?><a href="index.php?q=<?php echo $q ?>&subject=<?php echo $_SESSION['__materia__'] ?>&__goals__=1" style="font-weight: normal; float: left; margin-left: 10px; ">Registro per obiettivi</a><?php endif; ?>
		<?php if($change_subject->subjectNumber() > 1){ ?><a href="summary.php" style="font-weight: normal; float: right; margin-right: 10px; ">Riepilogo</a><?php } else if($_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) { ?><a href="dettaglio_medie.php" style="float: right; margin-right: 15px; font-weight: normal">Dettaglio classe</a><?php } ?>
	</td>
</tr>
<tr style="height: 25px">
	<td colspan="3" style="text-align: center; "><span id="ingresso" style="font-weight: bold; "><?php print $_SESSION['__classe__']->to_string() ?></span></td>
	<td colspan="<?php echo $right_cols ?>" style="text-align: center; ">Materia: <span id="uscita" style="font-weight: bold; text-transform: uppercase"><?php $change_subject->printLink(); ?></span></td>
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
	$st = array();
	$st['id'] = $al['id_alunno'];
	$st['value'] = $al['cognome']." ".$al['nome'];
	array_push($studenti, $st);
	
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
		if($_media < 5.5){
			$_media = 4;
		}
		else if ($_media > 6.49 && $_media < 8){
			$_media = 8;
		}
		$_voto = $voti_religione[$_media];
	}
	else{
		$_voto = $media;
	}
?>
<tr>
	<td style="width: 40%; padding-left: 8px; font-weight:bold; "><?php if($idx < 9) print "&nbsp;&nbsp;"; ?><?php echo ($idx+1).". " ?>
		<a href="#" onclick="show_menu(event, <?php echo $al['id_alunno'] ?>)" style="font-weight: normal; color: inherit; padding-left: 8px"><?php print $al['cognome']." ".$al['nome']?></a>
		<?php if($num_note > 0){?><!-- &nbsp;(<?php echo $num_note ?> note didattiche) --><?php } ?>
	</td>
	<td style="width: 10%; text-align: center; font-weight: bold;"><span id="avg_<?php echo $al['id_alunno']; ?>" class="<?php if($media < $_SESSION['__config__']['limite_sufficienza'] && $media > 0) print("attention") ?>"><?php print $_voto ?></span></td>
	<td id="numvoti_<?php echo $al['id_alunno']; ?>" style="width: 10%; text-align: center; font-weight: bold;"><?php print $num_voti ?></td>
	<?php 
	foreach ($vars as $k => $vs){
		if ($vs['num_prove'] > 0){
			$sp_media = round(($vs['somma'] / $vs['num_prove']), 2);
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
	<td id="avgtipo<?php echo $k ?>_<?php echo $al['id_alunno']; ?>" style="width: <?php echo $len ?>%; text-align: center; font-weight: bold;"><?php if($vs['num_prove'] < 1) echo "--"; else echo $_voto ?></td>
	<td id="numtipo<?php echo $k ?>_<?php echo $al['id_alunno']; ?>" style="width: <?php echo $len ?>%; text-align: center; font-weight: bold;"><?php echo $vs['num_prove'] ?></td>
	<?php } ?>	
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
		if($_media < 5.5){
			$_media = 4;
		}
		else if ($_media > 6.49 && $_media < 8){
			$_media = 8;
		}
		$_voto = $voti_religione[$_media];
	}
	else{
		$_voto = $m_tot;
	}
}
?>
</tbody>
<tfoot>
<tr style="height: 30px; background-color: #e8eaec">
	<td style="width: 40%; font-weight: bold; padding-left: 8px">
		Totale classe
	</td>
	<td style="width: 10%; text-align: center; font-weight: bold"><span class="<?php if($medie_classe['tot'] < $_SESSION['__config__']['limite_sufficienza']) print("attention") ?>"><?php echo $_voto ?></span></td>
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
		<input type="hidden" name="id_materia" value="<?php print $idm ?>" />
		<input type="hidden" name="materia" value="<?php print $_mat ?>" />
			<a href="index.php?q=1&subject=<?php echo $_SESSION['__materia__'] ?>" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />1 Quadrimestre
			</a>
			<a href="index.php?q=2&subject=<?php echo $_SESSION['__materia__'] ?>" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />2 Quadrimestre
			</a>
			<a href="index.php?q=0&subject=<?php echo $_SESSION['__materia__'] ?>" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />Totale
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
    <div id="context_menu" style="position: absolute; width: 170px; height: 60px; display: none">
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
					<select id="private" style='width: 30%; font-size: 11px; padding-top: 3px; margin-left: 30px' name='private'>
						<option value="0">No</option>
						<option value="1">Si</option>
					</select>
				</td>
			</tr>
			<tr>
				<td style='width: 25%' id='lab3'>Data *</td>
				<td>
					<input id='data_voto' type='date' style='text-align: right; width: 33%; padding-top: 3px' name='data_voto' />

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
					<input type='hidden' name='alunno' value='<?php print $_REQUEST['alunno'] ?>' />
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
</body>
</html>
