<?php

$ordine_scuola = $_SESSION['__classe__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

if(isset($_REQUEST['order'])){
	$order = $_REQUEST['order'];
}
else{
	$order = "data";
}

if(isset($_REQUEST['q'])){
	$q = $_REQUEST['q'];
}
else{
	$q = 0;
}

if(isset($_REQUEST['tipo'])){
	$q_type = "AND tipo = ".$_REQUEST['tipo'];
}
else{
	$q_type = "";
}

switch($q){
	case 0:
		$int_time = "AND data <= NOW()";
		$label = "";
		break;
	case 1:
		$int_time = "AND data <= '".$fine_q."'";
		$label = ", primo quadrimestre";
		break;
	case 2:
		$int_time = "AND (data > '".$fine_q."' AND data <= NOW()) ";
		$label = ", secondo quadrimestre";
}

$sel_alunno = "SELECT * FROM rb_alunni WHERE id_alunno = $student_id";
$sel_materia = "SELECT materia FROM rb_materie WHERE id_materia = ".$_REQUEST['materia'];
$sel_materie = "SELECT rb_materie.* FROM rb_materie, rb_scrutini WHERE alunno = {$student_id} AND id_materia > 2 AND tipologia_scuola = $ordine_scuola AND pagella = 1 AND anno = {$_SESSION['__current_year__']->get_ID()} AND quadrimestre = 2 AND classe = {$_SESSION['__classe__']->get_ID()} AND rb_scrutini.materia = rb_materie.id_materia ORDER BY posizione_pagella";
$sel_tipi = "SELECT * FROM rb_tipi_note_didattiche ORDER BY id_tiponota";
$sel_note = "SELECT rb_note_didattiche.*, rb_tipi_note_didattiche.descrizione AS tipo_nota, id_tiponota FROM rb_note_didattiche, rb_tipi_note_didattiche WHERE id_tiponota = tipo AND alunno = $student_id AND materia = ".$_REQUEST['materia']." AND anno = {$_SESSION['__current_year__']->get_ID()} $int_time $q_type ORDER BY $order DESC";
//print $sel_voti;
try{
	$res_alunno = $db->executeQuery($sel_alunno);
	$res_materia = $db->executeQuery($sel_materia);
	$res_note = $db->executeQuery($sel_note);
	$res_tipi = $db->executeQuery($sel_tipi);
	$res_materie = $db->executeQuery($sel_materie);
} catch (MySQLException $ex){
	$ex->redirect();
}
$alunno = $res_alunno->fetch_assoc();

$mt = $res_materia->fetch_assoc();
$desc_materia = $mt['materia'];

$link = "elenco_note_didattiche.php?materia=".$_REQUEST['materia'];

$drawer_label = "Elenco note didattiche - ".$desc_materia.$label;

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var _show = function(e) {
			if ($('#tipinota').is(":visible")) {
				$('#tipinota').hide("slide", 300);
				return;
			}
			var offset = $('#drawer').offset();
			var top = offset.top;
			//top += 36;
			var left = offset.left + $('#drawer').width();
			$('#tipinota').css({top: top+"px", left: left+"px", zIndex: 1000});
			$('#tipinota').show('slide', 300);
			return true;
		};

		var show_subjects = function(e) {
			if ($('#subjects').is(":visible")) {
				$('#subjects').hide("slide", 300);
				return;
			}
			var offset = $('#drawer').offset();
			var top = offset.top;
			top += 36;
			var left = offset.left + $('#drawer').width();
			$('#subjects').css({top: top+"px", left: left+"px", zIndex: 1000});
			$('#subjects').show('slide', 300);
			return true;
		};

		var show_submenu = function(e, off) {
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
			$('#tipinota').mouseleave(function(event){
				event.preventDefault();
				$('#tipinota').hide();
			});
			$('#overlay').click(function(event) {
				if ($('#overlay').is(':visible')) {
					show_drawer(event);
				}
				$('#tipinota').hide();
				$('#other_drawer').hide();
			});
			$('#showsub').click(function(event){
				var off = $(this).parent().offset();
				show_submenu(event, off);
			});
		});
	</script>
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
setlocale(LC_TIME, "it_IT.utf8");
$giorno_str = strftime("%A", strtotime(date("Y-m-d")));
?>
	<div class="outline_line_wrapper" style="margin-top: 20px">
		<div style="width: 20%; float: left; position: relative; top: 30%; text-align: center">Data</div>
		<div style="width: 30%; float: left; position: relative; top: 30%; text-align: center">Tipo nota</div>
		<div style="width: 50%; float: left; position: relative; top: 30%; text-align: center">Commento</div>
	</div>
	<table style="width: 95%; border-collapse: collapse; margin: auto">
<?php
if($res_note->num_rows < 1){
?>
		<tr>
	    	<td colspan="3" style="height: 150px; font-weight: bold; text-transform: uppercase; text-align: center">Nessuna nota presente</td> 
	    </tr>		
<?php 	
}
$background = "";
$index = 1;
$array_voti = array();
while($row = $res_note->fetch_assoc()){
?>
		<tr class="bottom_decoration <?php if ($row['id_tiponota'] == 3) echo 'main_700' ?>">
			<td style="width: 20%; text-align: center; "><?php print format_date($row['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></td> 
			<td style="width: 30%; text-align: center; "><?php print $row['tipo_nota'] ?></td> 
			<td style="width: 50%; text-align: center; "><?php print $row['note'] ?></td>   
		</tr>
<?php 
	$index++;
}
?>
		<tr>
	    	<td colspan="3" style="height: 25px"></td> 
	    </tr>
	</table>
</div>
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<?php if ($area == "genitori" && count($_SESSION['__sons__']) > 1): ?>
		<div class="drawer_link separator">
			<a href="#" id="showsub"><img src="../../images/69.png" style="margin-right: 10px; position: relative; top: 5%"/>Seleziona alunno</a>
		</div>
		<?php endif; ?>
		<div class="drawer_link separator">
			<a href="#" onclick="_show(event)">
				<img src="../../images/1.png" style="margin-right: 10px; position: relative; top: 5%"/>
				Filtra per tipo nota
			</a>
		</div>
		<div class="drawer_link separator">
			<a href="#" onclick="show_subjects(event)">
				<i class="fa fa-forward" style="margin-right: 10px; color: #222222; font-size: 1.1em"></i>
				Cambia materia
			</a>
		</div>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=<?php echo $_SESSION['__mod_area__'] ?>"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<div class="drawer_link"><a href="../../modules/communication/load_module.php?module=com&area=<?php echo $area ?>"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<?php if ($area == "genitori" && count($_SESSION['__sons__']) > 1){ ?>
<div id="other_drawer" class="drawer" style="height: 72px; display: none; position: absolute">
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
<!-- tipi nota -->
<div id="tipinota" style="position: absolute; width: 200px; height: 240px; display: none; background-color: white; box-shadow: none">
    <p style="line-height: 16px" class="bottom_decoration"><a style="font-weight: normal; font-size: 11px" href="<?php echo $link ?>&q=<?php echo $q ?>&order=data">Tutte le note</a></p>
<?php
while($t = $res_tipi->fetch_assoc()){
?>
	<p style="line-height: 16px" class="accent_decoration"><a style="font-weight: normal; font-size: 11px" href="<?php echo $link ?>&q=<?php echo $q ?>&order=data&tipo=<?php echo $t['id_tiponota'] ?>"><?php echo $t['descrizione'] ?></a></p>
<?php } ?>
</div>
<!-- tipi nota -->
<!-- materie -->
<div id="subjects" style="width: 200px; position: absolute; padding: 0px 0px 10px; border: 1px solid rgb(170, 170, 170); border-radius: 2px; box-shadow: rgb(136, 136, 136) 0px 0px 8px; top: 169px; left: 577px; display: none; background-color: rgb(255, 255, 255);">
	<?php
	while($subj = $res_materie->fetch_assoc()){
		?>
		<p style="line-height: 16px; border-bottom: 1px solid #EEEEEE">
			<a class="normal standard_link" style="font-weight: normal; font-size: 11px; padding-left: 10px" href="<?php echo $link ?>&q=<?php echo $q ?>&order=data&materia=<?php echo $subj['id_materia'] ?>"><?php echo $subj['materia'] ?></a>
		</p>
	<?php } ?>
</div>
<!-- materie -->
</body>
</html>
