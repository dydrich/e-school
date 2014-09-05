<!DOCTYPE html>
<html>
<head>
<title>Registro di classe</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../../../css/site_themes/blue_red/reg_classe.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../modules/communication/theme/style.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/jquery/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">
var stid = <?php echo $student_id ?>;
var grade_id = 0;
var action = "";
var subj = <?php echo $materia ?>;
var can_modify = true;

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

var nuovo_voto = function(alunno, materia){
	grade_id = 0;
	action = "new";
	$('#voto').val(0);
	$('#privato').val(0);
	$('#data_voto').val("");
	$('#tipo').val(0);
	$('#descrizione').val("");
	$('#argomento').val("");
	$('#note').val("");
	$('#titolo').text("Nuovo voto");
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

var del_mark = function(){
	if(!confirm("Sei sicuro di voler cancellare questo voto?")){
		return false;
	}
	id_voto = grade_id;
	action = "delete";
	$.ajax({
		type: "POST",
		url: "grade_manager.php",
		data:  {
			action: "delete",
			id_voto: id_voto,
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
				$('#tr_'+grade_id).hide();
				$('#mark').dialog("close");
			}
		}
	});
};

var modifica_voto = function(){
	if (can_modify == 0) {
		alert("Modifica non permessa");
		return false;
	}
	id_voto = grade_id;
	action = "update";
	$('#titolo').text("Modifica voto");
	$('#del_button').show();
	$.ajax({
		type: "POST",
		url: "grade_manager.php",
		data:  {action: 'get',
			id_voto: id_voto,
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
				$('#voto').val(json.grade.voto);
				$('#privato').val(json.grade.privato);
				$('#data_voto').val(json.grade.data_voto);
				$('#tipo').val(json.grade.tipologia);
				$('#descrizione').val(json.grade.descrizione);
				$('#argomento').val(json.grade.argomento);
				$('#note').val(json.grade.note);
			}
		}
	});
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

var register_grade = function(){
	if (!check_form()) {
		return false;
	}
	var url = "grade_manager.php";
	voto = $('#voto').val();
	data_voto = $('#data_voto').val();
	tipo = $('#tipo option:selected').text();
	desc = $('#descrizione').val();
	topic = $('#argomento').val();
	note = $('#note').val();
	$.ajax({
		type: "POST",
		url: url,
		data:  {
			action: action,
			voto: $('#voto').val(),
			id_alunno: stid,
			id_voto: grade_id,
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
				if (action == 'new'){
					grade_id = json.id;
					var tr = document.createElement("tr");
					var td1 = document.createElement("td");
					td1.setAttribute("style", "width: 10%; text-align: center");
					var _a = document.createElement("a");
					_a.setAttribute("href", "#");
					_a.setAttribute("id", "grade_"+grade_id);
					td1.appendChild(_a);
					tr.appendChild(td1);

					var td2 = document.createElement("td");
					td2.setAttribute("style", "width: 10%; text-align: center");
					var span = document.createElement("span");
					span.setAttribute("id", "data_"+grade_id);
					td2.appendChild(span);
					tr.appendChild(td2);

					var td3 = document.createElement("td");
					td3.setAttribute("style", "width: 5%; text-align: center");
					span = document.createElement("span");
					span.setAttribute("id", "type_"+grade_id);
					td3.appendChild(span);
					tr.appendChild(td3);

					var td4 = document.createElement("td");
					td4.setAttribute("style", "width: 25%; text-align: center");
					span = document.createElement("span");
					span.setAttribute("id", "desc_"+grade_id);
					td4.appendChild(span);
					tr.appendChild(td4);

					var td5 = document.createElement("td");
					td5.setAttribute("style", "width: 25%; text-align: center");
					span = document.createElement("span");
					span.setAttribute("id", "topic_"+grade_id);
					td5.appendChild(span);
					tr.appendChild(td5);

					var td6 = document.createElement("td");
					td6.setAttribute("style", "width: 25%; text-align: center");
					span = document.createElement("span");
					span.setAttribute("id", "note_"+grade_id);
					td6.appendChild(span);
					tr.appendChild(td6);

					$('#tbody').prepend(tr);

				}

				$('#grade_'+grade_id).text(json.voto);
				$('#grade_'+grade_id).addClass("_bold");
				if (voto < 6) {
					$('#grade_'+grade_id).css({color:"rgb(172, 21, 21)"});
				}
				else {
					$('#grade_'+grade_id).css({color:""});
				}
				$('#grade_'+grade_id).click(function(event){
					//alert(this.id);
					show_menu(event, grade_id);
				});
				$('#data_'+grade_id).css({fontWeight: "normal"});
				$('#data_'+grade_id).text(data_voto);
				$('#type_'+grade_id).css({fontWeight: "normal"});
				$('#type_'+grade_id).text(tipo.substr(0, 1));
				$('#desc_'+grade_id).css({fontWeight: "normal"});
				$('#desc_'+grade_id).text(desc);
				$('#topic_'+grade_id).css({fontWeight: "normal"});
				$('#topic_'+grade_id).text(topic);
				$('#note_'+grade_id).css({fontWeight: "normal"});
				$('#note_'+grade_id).text(note);
				$('#mark').dialog("close");
			}
		}
	});
};

var obiettivi = function(){
	if (can_modify == 0) {
		alert("Modifica non permessa");
		return false;
	}
	document.location.href = "voto_obiettivi.php?idv="+grade_id+"&stid=<?php print $alunno['id_alunno'] ?>";
};

var avg = function(){
	$('#wavg').dialog({
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
		title: 'Media',
		open: function(event, ui){

		}
	});
};

var update_avg = function(st){
	var num_voti = <?php print $res_voti->num_rows ?>;
	var voti = st.split(",");
	var pesi = [];
	for(var i = 0; i < num_voti; i++){
		var fld = $('#pound'+i).val();
		pesi.push(fld);
		//alert(fld.value);
	}
	media_aritmetica = media_ponderale = somma = somma_pesata = somma_pesi = 0;
	if(voti.length > 0){
		for(var i = 0; i < voti.length; i++){
			peso = parseFloat($('#pound'+i).val());
			somma += voti[i];
			somma_pesata += (voti[i]*peso);
			somma_pesi += peso;
		}

		media_aritmetica = somma / voti.length;
		media_ponderale = somma_pesata / somma_pesi;
	}
	else{
		media_ponderale = 0;
	}
	//alert(somma_pesata+"/"+somma_pesi);
	media_ponderale = +media_ponderale.toFixed(2);
	if (subj == 26 || subj == 30) {
		media_ponderale = convertReligionGrades(media_ponderale, true);
	}
	$('#avgp').text(media_ponderale);
};

var convertReligionGrades = function(grade, media){
	var rel_grades = new Array();
	rel_grades[4] = "Insufficiente";
	rel_grades[6] = "Sufficiente";
	rel_grades[8] = "Buono";
	rel_grades[9] = "Distinto";
	rel_grades[10] = "Ottimo";
	if (media) {
		if (grade < 5.5){
			grade = 4;
		}
		else if (grade < 7.5){
			grade = 6;
		}
		else {
			grade = Math.round(grade);
		}
	}
	return rel_grades[grade];
};

var notes = function(){
	document.location.href = "";
};

var change_subject = function(id){
	document.location.href="student.php?subject="+id+"&q=<?php echo $q ?>&stid=<?php echo $student_id ?>";
};

var show_menu = function(e, _gi, modify){
	can_modify = modify;
	grade_id = _gi;
	<?php 
	if (isset($_SESSION['__user_config__']['registro_obiettivi'][0]) && 0 == $_SESSION['__user_config__']['registro_obiettivi'][0]){
	?>
	modifica_voto();
	<?php
	}
	else {
	?>
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
    return false;
    <?php 
	}
    ?>
};

$(function(){
	$('#context_menu').mouseleave(function(event){
		$('#context_menu').hide();
	});
	$('.grade_link').click(function(event){
		//alert(this.id);
		var strs = this.id.split("_");
		show_menu(event, strs[1], strs[2]);
	});
	$('#data_voto').datepicker({
		dateFormat: "dd/mm/yy",
		altFormat: "dd/mm/yy"
	});
	$('#subm').click(function(event){
		event.preventDefault();
		register_grade();
	});
	$('#del_button').click(function(event){
		event.preventDefault();
		del_mark();
	});
	$('#weighted_avg').click(function(event){
		event.preventDefault();
		avg();
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
<form>
<?php 
setlocale(LC_TIME, "it_IT");
$giorno_str = strftime("%A", strtotime(date("Y-m-d")));
?>
<table class="registro">
<thead>
<tr class="head_tr">
	<td colspan="6" style="text-align: center; font-weight: bold"><?php print $_SESSION['__current_year__']->to_string() ?>::classe <?php print $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?> 
		<span style="float: right; padding-right: 10px" >
			<a href="#" id="weighted_avg" style="margin-right: 10px;">Calcola media ponderale</a>|
			<a href="#" onclick="nuovo_voto(<?php print $alunno['id_alunno'] ?>, <?php print $_SESSION['__materia__'] ?>)" style="margin-right: 10px; margin-left: 10px">Nuovo voto</a>|
			<a href="pdf_media_materia.php?stid=<?php print $student_id ?>&q=<?php print $q ?>" style="margin-left: 10px">PDF</a></span></td>
</tr>
<tr class="head_tr_no_bg">
	<td colspan="4" style="width: 50%; text-align: center"><span id="ingresso" style="font-weight: bold; "><?php print $alunno['cognome']." ".$alunno['nome'] ?>: <?php $change_subject->printLink() ?></span></td> 
	<td colspan="2" style="text-align: center"><span id="media" style="font-weight: bold; "></span>
	<?php 
	if($num_note > 0){
	?>
	&nbsp;(<a href="student_notes.php?stid=<?php print $alunno['id_alunno'] ?>&q=<?php print $q ?>" style="font-weight: normal">Sono presenti <?= $num_note ?> note didattiche</a>)
	<?php 
	}
	?>
	</td>
</tr>
<tr class="title_tr"> 
	<td style="width: 10%; text-align: center; border-width: 1px 0px 1px 1px; border-style: solid"><span style="font-weight: bold; ">Voto</span></td> 
	<td style="width: 10%; text-align: center; border-width: 1px 0px 1px 1px; border-style: solid"><span style="font-weight: bold; ">Data</span></td> 
	<td style="width: 5%; text-align: center; border-width: 1px 0px 1px 1px; border-style: solid"><span style="font-weight: bold; ">Tipo</span></td> 
	<td style="width: 25%; text-align: center; border-width: 1px 0px 1px 1px; border-style: solid"><span style="font-weight: bold; ">Prova</span></td> 
	<td style="width: 25%; text-align: center; border-width: 1px 0px 1px 1px; border-style: solid"><span style="font-weight: bold; ">Argomento</span></td> 
	<td style="width: 25%; text-align: center; border-width: 1px 1px 1px 1px; border-style: solid"><span style="font-weight: bold; ">Note</span></td>   
</tr> 
</thead>
<tbody id="tbody">
<?php
if($res_voti->num_rows < 1){
?>
<tr>
	<td colspan="6" style="height: 50px; text-align: center; font-weight: bold; text-transform: uppercase">Nessun voto presente</td>
</tr>	
<?php 	
}
$background = "";
$index = 1;
$tot_voti = 0;
$other_rs = $res_voti;
$res_voti->data_seek(0);
$array_voti = array();
while($row = $res_voti->fetch_assoc()){
	$can_modify = 1;
	if ($row['docente'] != $_SESSION['__user__']->getUid()) {
		$can_modify = 0;
	}
	array_push($array_voti, $row['voto']);
	if($index % 2)
		$background = "background-color: #e8eaec";
	else
		$background = "";
	if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
		$_media = round($row['voto']);
		if($_media < 5.5){
			$_media = 4;
		}
		else if ($_media > 6.49 && $_media < 8){
			$_media = 8;
		}
		$_voto = $voti_religione[$_media];
	}
	else{
		$_voto = $row['voto'];
	}
?>
<tr id="tr_<?php echo $row['id_voto'] ?>">
	<td style="width: 10%; text-align: center"><a href="#" id="grade_<?php echo $row['id_voto'] ?>_<?php echo $can_modify ?>" class="grade_link" style="font-weight: bold; <?php if($row['voto'] < 6) print("color: rgb(172, 21, 21)") ?>"><?php echo $_voto ?></a></td>
	<td style="width: 10%; text-align: center"><span id="data_<?php echo $row['id_voto'] ?>" style="font-weight: normal; "><?php print format_date($row['data_voto'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></span></td>
	<td style="width: 5%; text-align: center"><span id="type_<?php echo $row['id_voto'] ?>" style="font-weight: normal; "><?php print substr($row['label'], 0, 1) ?></span></td>
	<td style="width: 25%; text-align: center"><span id="desc_<?php echo $row['id_voto'] ?>" style="font-weight: normal; "><?php print $row['descrizione'] ?></span></td>
	<td style="width: 25%; text-align: center"><span id="topic_<?php echo $row['id_voto'] ?>" style="font-weight: normal; "><?php print $row['argomento'] ?></span></td>
	<td style="width: 25%; text-align: center"><span id="note_<?php echo $row['id_voto'] ?>" style="font-weight: normal; "><?php print utf8_decode($row['note']) ?></span></td>
</tr>
<?php 
	$index++;
	$tot_voti += $row['voto'];
}
?>
</tbody>
<tfoot>
<tr>
	<td colspan="6" style="height: 15px"></td>
</tr>
<tr class="nav_tr"> 
	<td colspan="6" style="text-align: center; font-weight: bold; height: 35px">&nbsp;
		<a href="student.php?stid=<?php echo $student_id ?>&q=1" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />1 Quadrimestre
		</a>
		<a href="student.php?stid=<?php echo $student_id ?>&q=2" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
			<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />2 Quadrimestre
		</a>
		<a href="student.php?stid=<?php echo $student_id ?>&q=0" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />Totale
		</a>

	<?php 
	if($tot_voti > 0){
		$media_voto = round(($tot_voti / $res_voti->num_rows), 2);
		if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
			if($media_voto < 5.5){
				$media_voto = 4;
			}
			else if ($media_voto > 6.49 && $media_voto < 8){
				$media_voto = 8;
			}
			$media_voto = $voti_religione[round($media_voto)];
		}
	?>
	<script type="text/javascript">
		$('#media').text("Media voto: <?php print $media_voto ?>");
	</script>
	<?php 
	}
	?>
	</td> 
</tr>
<?php

$previous = get_sibling($_SESSION['students'], $student_id, PREVIOUS);
$next = get_sibling($_SESSION['students'], $student_id, NEXT);
if($previous == INDEX_OUT_OF_BOUND){
	$link_p = "#";
	$text_p = "";
}
else{
	$link_p = "student.php?stid=".$previous['id']."&q=$q";
	$text_p = $previous['value'];
}
if($next == INDEX_OUT_OF_BOUND){
	$link_n = "#";
	$text_n = "";
}
else{
	$link_n = "student.php?stid=".$next['id']."&q=$q";
	$text_n = $next['value'];
}
?>
<tr style="height: 30px"> 
	<td colspan="4" style="text-align: left"><a href="<?php echo $link_p ?>" style="margin-left: 30px; font-weight: normal; text-decoration: none">&lt;&lt; <?php echo $text_p ?></a></td> 
	<td colspan="2" style="text-align: right"><a href="<?php echo $link_n  ?>" style="margin-right: 30px; font-weight: normal; text-decoration: none"><?php echo $text_n ?> &gt;&gt;</a></td> 
</tr>
</tfoot>
</table>
</form>
</div>
<!-- menu contestuale -->
<div id="context_menu" style="position: absolute; width: 170px; height: 60px; display: none">
    <a style="font-weight: normal" href="#" onclick="modifica_voto()">Modifica il voto</a><br />
    <a style="font-weight: normal" href="#" onclick="obiettivi()">Gestisci obiettivi</a><br />
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
					<input id='data_voto' type='text' style='text-align: right; width: 33%; padding-top: 3px' name='data_voto' />

					<span style='margin-right: 42px; margin-left: 10px' id='lab4'>Tipo *</span>
					<select id='tipo' name='tipo' style='font-size: 11px; width: 30%'>
						<option value='0'>Seleziona</option>
						<?php
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
			<input type='button' id='del_button' value='Elimina' style='width: 50px; font-size: 11px; padding: 2px; margin-right: 10px; display: none' />
			<input type="button" id="subm" value="Invia" style="width: 50px; font-size: 11px; padding: 2px" />
			<input type='hidden' name='ia' />
		</div>
	</form>
</div>
<!-- calcolo media ponderale -->
<div id="wavg" style="display: none">
	<p style='text-align: center; padding-top: 20px; font-weight: bold' id='titolo_w'>Calcola media ponderale</p>
	<form id='avgform' action='' method='post'>
		<table style='text-align: left; width: 95%; margin: auto; border-collapse: collapse' id='att'>
			<tr style="border-bottom: 1px solid">
				<td style='width: 10%'>Voto</td>
				<td style='width: 20%; text-align: center'>Data</td>
				<td style='width: 50%; text-align: center'>Prova</td>
				<td style='width: 20%; '>Peso (&lt;100)</td>
			</tr>
			<?php
			$vt = 0;
			$background = "";
			$dx = 0;
			$res_voti->data_seek(0);
			reset($array_voti);
			while($_row = $res_voti->fetch_assoc()){
				if($dx % 2) {
					$background = "background-color: #e8eaec";
				}
				else {
					$background = "";
				}
				$sh_grade = $_row['voto'];
				if ($materia == 26 || $materia == 30) {
					$sh_grade = $voti_religione[$_row['voto']];
					$sh_grade = strtoupper(substr($sh_grade, 0, 3));
				}
				?>
				<tr>
					<td style='width: 10%; text-align: right; padding-right: 10px; <?php print $background ?>'><?php echo $sh_grade ?></td>
					<td style='width: 20%; text-align: center; <?php print $background ?>'><?php echo format_date($_row['data_voto'], SQL_DATE_STYLE, IT_DATE_STYLE, '/') ?></td>
					<td style='width: 50%; padding-left: 10px; <?php print $background ?>'><?php echo $_row['descrizione'] ?></td>
					<td style='width: 20%; <?php print $background ?>'>
						<input onchange='update_avg("<?php echo join(',', $array_voti) ?>")' style='width: 90%; border: 1px solid gray; font-size: 11px; margin: auto' type='text' value='1' id='pound<?php print $dx ?>' maxlength='2' />
					</td>
				</tr>
				<?php
				$dx++;
				$vt += $_row['voto'];
			}
			$media_voto = round(($vt / $res_voti->num_rows), 2);
			if ($materia == 26 || $materia == 30) {
				$m = RBUtilities::convertReligionGrade($media_voto);
				$mv = $voti_religione[$m];
			}
			else {
				$mv = $media_voto;
			}
			?>
			<tr>
				<td colspan='4'>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='4' style='font-weight: bold; font-size: 1.1em'>
					Media ponderale: <span id='avgp' style='<?php if($media_voto < 6) print('color: red') ?>'><?php echo $mv ?></span>
				</td>
			</tr>
			<tr>
				<td colspan='4'>&nbsp;<input type='hidden' id='string' name='string' /></td>
			</tr>
		</table>
	</form>
</div>
<?php include "../footer.php" ?>
</body>
</html>
