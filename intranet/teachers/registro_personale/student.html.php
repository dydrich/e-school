<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro personale: dettaglio studente</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var stid = <?php echo $student_id ?>;
		var grade_id = 0;
		var grade_type = 0;
		var action = "";
		var subj = <?php echo $materia ?>;
		var can_modify = true;
		var grades_count = <?php echo count($array_voti) ?>;

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
					q: <?php echo $q ?>,
					id_alunno: stid,
					tipologia: grade_type
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
						grades_count--;
						$('#tr_'+grade_id).hide(400);
						$('#media').text("Media voto: "+json.all.avg);
						if (grades_count == 0) {
							$('#tbody').append($('<tr id="norecords"><td colspan="6" style="height: 50px; text-align: center; font-weight: bold; text-transform: uppercase">Nessun voto presente</td></tr>'));
							$('#media').text("");
						}
						if ($('#mark').is(":visible")) {
							$('#mark').dialog("close");
						}
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
						$('#voto').val(json.grade.voto);
						$('#privato').val(json.grade.privato);
						$('#data_voto').val(json.grade.data_voto);
						$('#tipo').val(json.grade.tipologia);
						$('#descrizione').val(json.grade.descrizione);
						$('#argomento').val(json.grade.argomento);
						$('#note').val(json.grade.note);
						$('#id_verifica').val(json.grade.id_verifica);
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
					note: $('#note').val(),
					privato: $('#privato').val(),
					verifica: $('#id_verifica').val(),
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
						if (action == 'new'){
							if (grades_count == 0) {
								$('#norecords').hide(400);
							}
							grades_count++;
							grade_id = json.id;
							id_voto = json.id;
							var tr = document.createElement("tr");
							tr.setAttribute("id", "tr_"+grade_id);
							var td1 = document.createElement("td");
							td1.setAttribute("id", "provv"+grade_id);
							td1.setAttribute("style", "width: 10%; text-align: center");
							var _a = document.createElement("a");
							_a.setAttribute("href", "#");
							_a.setAttribute("id", "grade_"+grade_id);
							_a.setAttribute("data-id", grade_id);
							_a.setAttribute("data-permission", 1);
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
							if (json.previous == "") {
								$('#tbody').prepend($(tr));
							}
							else {
								$(tr).insertAfter($('#tr_' + json.previous));
							}
							$('#tr_'+grade_id).hide();
						}
						$('#mark').dialog("close");
						$('#grade_'+grade_id).text(json.voto);
						$('#grade_'+grade_id).addClass("_bold");
						if (voto < 6) {
							$('#grade_'+grade_id).addClass("attention");
						}
						else {
							$('#grade_'+grade_id).removeClass("attention");
						}
						$('#grade_'+grade_id).click(function(event){
							var offset = $('#provv'+grade_id).offset();
							offset.top = offset.top + $('#provv'+grade_id).height();
							show_menu(event, grade_id, 1, $('#tipo').val(), offset);
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
						$('#media').text("Media voto: "+json.all.avg);
						if (action == 'new') {
							$('#tr_'+grade_id).show(1400);
						}
					}
				}
			});
		};

		var obiettivi = function(){
			if (can_modify == 0) {
				j_alert("error", "Modifica non permessa");
				return false;
			}
			document.location.href = "voto_obiettivi.php?idv="+grade_id+"&stid=<?php print $alunno['id_alunno'] ?>";
		};

		var avg = function(){
			$('#drawer').hide();
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

				},
				close: function (event) {
					$('#overlay').hide();
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

		var show_menu = function(e, _gi, modify, type, offset){
			can_modify = modify;
			grade_id = _gi;
			grade_type = type;

			$('#context_menu').css({'top': offset.top+"px"});
			$('#context_menu').css({'left': offset.left+"px"});
			$('#context_menu').slideDown(500);
		    return false;
		};

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

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#context_menu').mouseleave(function(event){
				$('#context_menu').hide();
			});
			$('.grade_link').click(function(event){
				//alert(this.id);
				var offset = $(this).parent().offset();
				offset.top = offset.top + $(this).parent().height();
				show_menu(event, $(this).attr("data-id"), $(this).attr("data-permission"), $(this).attr("data-type"), offset);
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

	</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<?php
$label_subject = "";
if (count($_SESSION['__subjects__']) > 1) {
	?>
<div class="mdtabs">
	<?php
	foreach ($_SESSION['__subjects__'] as $mat) {
		if (isset($_SESSION['__materia__']) && $_SESSION['__materia__'] == $mat['id']) {
			$label_subject = "::".$mat['mat'];
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
<form>
<?php 
setlocale(LC_TIME, "it_IT.utf8");
$giorno_str = strftime("%A", strtotime(date("Y-m-d")));
?>
	<div style="top: -8px; margin-left: 825px; margin-bottom: -39px" class="rb_button">
		<a href="#" id="weighted_avg">
			<img src="../../../images/62.png" style="padding: 12px 0 0 13px" />
		</a>
	</div>
	<div style="top: -8px; margin-left: 895px; margin-bottom: -26px" class="rb_button">
		<a href="#" onclick="nuovo_voto(<?php print $alunno['id_alunno'] ?>, <?php print $_SESSION['__materia__'] ?>)">
			<img src="../../../images/39.png" style="padding: 12px 0 0 12px" />
		</a>
	</div>
<table class="registro">
<thead>
<tr class="head_tr_no_bg">
	<td colspan="4" style="width: 50%; text-align: center"><span id="uscita" style="font-weight: normal; text-transform: uppercase; color: #000000"><?php print $alunno['cognome']." ".$alunno['nome'] ?><?php echo $label_subject ?></span></td>
	<td colspan="2" style="text-align: left"><span id="media" style="font-weight: bold; "></span>
	<?php 
	if($num_note > 0){
	?>
	&nbsp;(<a href="student_notes.php?stid=<?php print $alunno['id_alunno'] ?>&q=<?php print $q ?>" style="font-weight: normal"> <?php echo $num_note ?> note</a>)
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
<tr id="norecords">
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
	<td style="width: 10%; text-align: center"><a href="#" id="grade_<?php echo $row['id_voto'] ?>" data-id="<?php echo $row['id_voto'] ?>" data-type="<?php echo $row['tipologia'] ?>" data-permission="<?php echo $can_modify ?>" class="grade_link" style="font-weight: bold; <?php if($row['voto'] < 6) print("color: rgb(172, 21, 21)") ?>"><?php echo $_voto ?></a></td>
	<td style="width: 10%; text-align: center"><span id="data_<?php echo $row['id_voto'] ?>" style="font-weight: normal; "><?php print format_date($row['data_voto'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></span></td>
	<td style="width: 5%; text-align: center"><span id="type_<?php echo $row['id_voto'] ?>" style="font-weight: normal; "><?php print substr($row['label'], 0, 1) ?></span></td>
	<td style="width: 25%; text-align: center"><span id="desc_<?php echo $row['id_voto'] ?>" style="font-weight: normal; "><?php print utf8_decode($row['descrizione']) ?></span></td>
	<td style="width: 25%; text-align: center"><span id="topic_<?php echo $row['id_voto'] ?>" style="font-weight: normal; "><?php print utf8_decode($row['argomento']) ?></span></td>
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
	<td colspan="6" style="text-align: center; font-weight: normal; height: 35px">&nbsp;
		<a href="student.php?stid=<?php echo $student_id ?>&q=1" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 3px" src="../../../images/24.png" />1 Quadrimestre
		</a>
		<a href="student.php?stid=<?php echo $student_id ?>&q=2" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
			<img style="margin-right: 5px; position: relative; top: 3px" src="../../../images/24.png" />2 Quadrimestre
		</a>
		<a href="student.php?stid=<?php echo $student_id ?>&q=0" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 3px" src="../../../images/24.png" />Totale
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
	<td colspan="2" style="text-align: right"><a href="<?php echo $link_n ?>" style="margin-right: 30px; font-weight: normal; text-decoration: none"><?php echo $text_n ?> &gt;&gt;</a></td>
</tr>
</tfoot>
</table>
</form>
</div>
<!-- menu contestuale -->
<div id="context_menu" style="position: absolute; width: 170px; height: 60px; display: none">
    <a style="font-weight: normal" href="#" onclick="modifica_voto()">Modifica il voto</a><br />
	<a style="font-weight: normal" href="#" onclick="del_mark()">Elimina il voto</a><br />
	<?php
	if (isset($_SESSION['__user_config__']['registro_obiettivi'][0]) && 1 == $_SESSION['__user_config__']['registro_obiettivi'][0]): ?>
    <a style="font-weight: normal" href="#" onclick="obiettivi()">Gestisci obiettivi</a><br />
	<?php endif; ?>
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
					<input type='hidden' name='id_verifica' id="id_verifica" value='' />
					<input type='hidden' name='alunno' value='<?php if (isset($_REQUEST['alunno'])) echo $_REQUEST['alunno'] ?>' />
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
	<p style='text-align: center; padding-top: 0' id='titolo_w' class="material_label _bold">Calcola media ponderale</p>
	<form id='avgform' action='' method='post'>
		<table style='text-align: left; width: 95%; margin: auto; border-collapse: collapse' id='att'>
			<tr class="accent_decoration">
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
					#$background = "background-color: #e8eaec";
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
						<input onchange='update_avg("<?php echo join(',', $array_voti) ?>")' style='width: 90%; font-size: 11px; margin: auto; ' class='android' type='text' value='1' id='pound<?php print $dx ?>' class="android" maxlength='2' />
					</td>
				</tr>
				<?php
				$dx++;
				$vt += $_row['voto'];
			}
			$media_voto = $mv = "";
			if ($res_voti->num_rows > 0) {
				$media_voto = round(($vt / $res_voti->num_rows), 2);
				if ($materia == 26 || $materia == 30) {
					$m = RBUtilities::convertReligionGrade($media_voto);
					$mv = $voti_religione[$m];
				}
				else {
					$mv = $media_voto;
				}
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
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_label"><span>Classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></span></div>
		<div class="drawer_link submenu"><a href="index.php"><img src="../../../images/4.png" style="margin-right: 10px; position: relative; top: 5%" />Registro personale</a></div>
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
<div id="other_drawer" class="drawer" style="height: 180px; display: none; position: absolute">
	<?php if (!isset($_REQUEST['__goals__']) && (isset($_SESSION['__user_config__']['registro_obiettivi']) && (1 == $_SESSION['__user_config__']['registro_obiettivi'][0]))): ?>
	<div class="drawer_link ">
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
</body>
</html>
