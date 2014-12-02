<?php

// numero di giorni da visualizzare
$limit = 12;

$ordine_scuola = $_SESSION['__classe__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$inizio_lezioni = format_date($school_year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_lezioni = format_date($school_year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

$today = date("Y-m-d");
if (isset($_GET['month'])){
	$month = $_GET['month'];
}
else {
	$month = intval(date("m"));
}
if($today > $fine_lezioni){
	$today = $fine_lezioni;
	if (!isset($_GET['month'])){
		$month = 6;
	}
}
$previous = $month - 1;
$next = $month + 1;
if ($next > 12) $next = 1;
if ($previous < 1) $previous = 12;
if ($month == 6) $next = null;
if ($month == 9) $previous = null;

$sel_orario_alunno = "SELECT data, rb_reg_alunni.ingresso, rb_reg_alunni.uscita, note, id_alunno, giustificata FROM rb_reg_alunni, rb_reg_classi WHERE id_alunno = {$student_id} AND DATE_FORMAT(data, '%c') = {$month} AND data <= NOW() AND id_anno = {$_SESSION['__current_year__']->get_ID()} AND id_registro = id_reg AND rb_reg_classi.id_classe = ".$_SESSION['__classe__']->get_ID()." ORDER BY data DESC";
//print $sel_orario_alunno;
$res_orario_alunno = $db->execute($sel_orario_alunno);
setlocale(LC_TIME, "it_IT.utf8");
$mesi_scuola = array("Settembre", "Ottobre", "Novembre", "Dicembre", "Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno");
$mesi = array("Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre", );
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		$(function() {
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
		function show_note(element){
			element.getElementsByTagName("span")[0].style.display = "block";
		}

		function hide_note(element){
			element.getElementsByTagName("span")[0].style.display = "none";
		}

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
	</script>
	<style type="text/css">
	TD{height: 20px}
	.tooltip{
	    position: absolute;
	    top: 1em;
	    left: 1em;
	    width: 15em;
	    padding: 10px;
	    font-size: 1em;
	    text-align: center;
	    text-decoration: none
	}
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
	<table style="width: 95%; margin: auto; border-collapse: collapse">
		<tr>
			<td colspan="4" style="font-weight: bold; text-align: center">
				<div class="card_row">Mese di <?php echo $mesi[$month - 1] ?></div>
			</td>
		</tr>
<?php 
while($orario_alunno = $res_orario_alunno->fetch_assoc()){
	$assente = false;
	$giorno_str = strftime("%a", strtotime($orario_alunno['data']));
	if($orario_alunno['ingresso'] == ""){
		$entrata = "A";
		$assente = true;
	}
	else
		$entrata = substr($orario_alunno['ingresso'], 0, 5);
	if($orario_alunno['uscita'] == "")
		$uscita = "A";
	else
		$uscita = substr($orario_alunno['uscita'], 0, 5);
	$background = "";
		
	$add_spaces = false;
	if($entrata == "A" && ($orario_alunno['giustificata'] == 0 || $orario_alunno['giustificata'] == ""))
		$add_spaces = true;
		
	$sel_alunni = "SELECT rb_alunni.* FROM rb_alunni WHERE id_alunno = ".$orario_alunno['id_alunno'];
	$res_alunni = $db->execute($sel_alunni);
	$al = $res_alunni->fetch_assoc();
	
	// ricerca di note
	$sel_note = "SELECT * FROM rb_note_disciplinari WHERE alunno = ".$orario_alunno['id_alunno']." AND data = '".$orario_alunno['data']."' ORDER BY id_nota ASC";
	$res_note = $db->executeQuery($sel_note);
	$num_note = $res_note->num_rows;
	$tooltip = "";
	if($num_note == 1){
		$nt = $res_note->fetch_assoc();
		$tooltip = $nt['descrizione'];
	}
	else if($num_note > 1){
		while($nt = $res_note->fetch_assoc()){
			$tooltip .= $nt['descrizione']."|";
		}
		$tooltip = substr($tooltip, 0, -1);
	}
?>
	<tr class="bottom_decoration <?php if($assente) echo " attention _bold" ?>">
		<td style="width: 30%; padding-left: 8px" class="normal"><?php print ucfirst($giorno_str)." ". format_date($orario_alunno['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></td>
		<td style="width: 15%; text-align: center" <?php if($assente) print("colspan='2'") ?>><?php if($assente) print "Assente"; else print $entrata ?></td>
<?php 
	if(!$assente){	
?>
		<td style="width: 15%; text-align: center; font-weight: normal;  "><?php print $uscita ?></td>
<?php 
	}
?>
		<td style="width: 40%; text-align: center;  font-weight: normal;">
			<span id="disc<?= $orario_alunno['id_alunno'] ?>"><?php if($entrata == "A" && ($orario_alunno['giustificata'] == 0 || $orario_alunno['giustificata'] == "")) print ("Assenza da giustificare"); ?><?php if($num_note > 0){ if($add_spaces) print ("&nbsp;&nbsp;|&nbsp;&nbsp;"); ?><a style='text-decoration: underline; color: #161414; font-weight: normal' href="#" title="<?php echo $tooltip ?>" onmouseover="show_note(this, <?php echo $num_note ?>)" onmouseout="hide_note(this, <?php echo $num_note ?>)"><?php echo $num_note ?> note disciplinari</a><?php } ?></span>
		</td>
	</tr>
<?php
}
?>
		<tr>
			<td colspan="4" style="height: 30px;"></td>
		</tr>
	</table>
	<div class="navigate">
		<?php if ($previous): ?>
		<a href="registro.php?month=<?php echo $previous ?>" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 28px;">
			<?php echo $mesi[$previous - 1] ?>
		</a>
		<?php else: ?>
		<span style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 28px">&lt; &lt;</span>
		<?php endif; ?>
		<span style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 28px; margin-left: 28px"><?php echo $mesi[$month - 1] ?></span>
		<?php if ($next): ?>
		<a href="registro.php?month=<?php echo $next ?>" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-left: 28px;">
			<?php echo $mesi[$next - 1] ?>
		</a>
		<?php else: ?>
		<span style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-left: 28px;">&gt; &gt;</span>
		<?php endif; ?>
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
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>riepilogo_registro.php?q=0"><img src="../../images/10.png" style="margin-right: 10px; position: relative; top: 5%" />Riepilogo assenze</a></div>
		<div class="drawer_link separator"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>riepilogo_note.php?q=0"><img src="../../images/12.png" style="margin-right: 10px; position: relative; top: 5%" />Riepilogo note</a></div>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<?php if ($area == "alunni"): ?>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=alunni"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php endif; ?>
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
				<a href="<?php print $page ?>?son=<?php print $key ?>" clas="<?php echo $cl ?>"><?php print $val[0] ?></a>
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
