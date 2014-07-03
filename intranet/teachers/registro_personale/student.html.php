<!DOCTYPE html>
<html>
<head>
<title>Registro di classe</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../registro_classe/reg_classe.css" type="text/css" media="screen,projection" />
<link href="../../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="../../../css/skins/aqua/theme.css" type="text/css" />
<script type="text/javascript" src="../../../js/prototype.js"></script>
<script type="text/javascript" src="../../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript" src="../../../js/window.js"></script>
<script type="text/javascript" src="../../../js/window_effects.js"></script>
<script type="text/javascript" src="../../../js/calendar.js"></script>
<script type="text/javascript" src="../../../js/lang/calendar-it.js"></script>
<script type="text/javascript" src="../../../js/calendar-setup.js"></script>
<script type="text/javascript">
var stid = 0;
var grade_id = 0;
<?php echo $change_subject->getJavascript() ?>

var avg_win;
function nuovo_voto(alunno, materia){
	var win = new Window({className: "mac_os_x",  url: "mark.php?q=<?php print $q ?>&alunno=<?php print $alunno['id_alunno'] ?>&action=new", width:400, height:290, zIndex: 100, resizable: true, title: "Dettaglio voto", showEffect:Effect.BlindDown, hideEffect: Effect.SwitchOff, draggable:true, wiredDrag: true});
	win.showCenter(true);	
}

function modifica_voto(){
	id_voto = grade_id;
	//act = window.open_centered("new_mark.php?alunno="+alunno+"&materia="+materia, "aaa", 400, 300, "");
	var win = new Window({className: "mac_os_x",  url: "mark.php?q=<?php print $q ?>&alunno=<?php print $alunno['id_alunno'] ?>&id_voto="+id_voto+"&action=update", width:400, height:290, zIndex: 100, resizable: true, title: "Dettaglio voto", showEffect:Effect.BlindDown, hideEffect: Effect.SwitchOff, draggable:true, wiredDrag: true});
	win.showCenter(true);		
}

var obiettivi = function(){
	document.location.href = "voto_obiettivi.php?idv="+grade_id+"&stid=<?php print $alunno['id_alunno'] ?>";
};

function avg(){
	avg_win = new Window({className: "mac_os_x", url: "show_grades.php?q=<?php echo $q ?>&alunno=<?php echo $alunno['id_alunno'] ?>",  width:400, zIndex: 100, resizable: true, title: "Calcolo media ponderata", showEffect:Effect.BlindDown, hideEffect: Effect.SwitchOff, draggable:true, wiredDrag: true});
	avg_win.showCenter(true);
}

function notes(){
	document.location.href = "";
}

function change_subject(id){
	document.location.href="student.php?subject="+id+"&q=<?php echo $q ?>&stid=<?php echo $student_id ?>";
}

function show_menu(e, _gi){
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
    $('context_menu').style.top = parseInt(tempY)+"px";
    $('context_menu').style.left = parseInt(tempX)+"px";
    $('context_menu').show();
    return false;
    <?php 
	}
    ?>
}

document.observe("dom:loaded", function(){
	$('context_menu').observe("mouseleave", function(event){
		this.hide();
	});
	$$('.grade_link').invoke("observe", "click", function(event){
		//alert(this.id);
		var strs = this.id.split("_");
		show_menu(event, strs[1]);
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
			<a href="#" onclick="avg()" style="margin-right: 10px;">Calcola media ponderata</a>|
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
<tbody>
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
<tr> 
	<td style="width: 10%; text-align: center"><a href="#" id="grade_<?php echo $row['id_voto'] ?>" class="grade_link" style="font-weight: bold; <?php if($row['voto'] < 6) print("color: rgb(172, 21, 21)") ?>"><?php echo $_voto ?></a></td> 
	<td style="width: 10%; text-align: center"><span style="font-weight: normal; "><?php print format_date($row['data_voto'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></span></td> 
	<td style="width: 5%; text-align: center"><span style="font-weight: normal; "><?php print substr($row['label'], 0, 1) ?></span></td> 
	<td style="width: 25%; text-align: center"><span style="font-weight: normal; "><?php print $row['descrizione'] ?></span></td>
	<td style="width: 25%; text-align: center"><span style="font-weight: normal; "><?php print $row['argomento'] ?></span></td>
	<td style="width: 25%; text-align: center"><span style="font-weight: normal; "><?php print utf8_decode($row['note']) ?></span></td>   
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
		<!-- <a href="student.php?stid=<?= $student_id ?>&q=1">1 Quadrimestre</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="student.php?stid=<?= $student_id ?>&q=2">2 Quadrimestre</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="student.php?stid=<?= $student_id ?>&q=0">Totale</a> -->
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
		$('media').innerHTML = "Media voto: <?php print $media_voto ?>";
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
<?php include "../footer.php" ?>
</body>
</html>