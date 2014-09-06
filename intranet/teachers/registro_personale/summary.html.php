<!DOCTYPE html>
<html>
<head>
<title>Registro di classe</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
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

<?php
$max = $res_alunni->num_rows;
for($i = 0; $i < $max; $i++){
?>
var str<?php print $i ?> = "";
<?php 
}
?>
str = str0;

function fai(event){
	if (IE) { // grab the x-y pos.s if browser is IE
        asse_x = event.clientX + document.body.scrollLeft;
        asse_y = event.clientY + document.body.scrollTop;
    } else {  // grab the x-y pos.s if browser is NS
        asse_x = event.pageX;
        asse_y = event.pageY;
    }  
	//setTimeout('get_caption('+asse_x+', '+asse_y+')', 1000);
	get_caption(asse_x, asse_y);
}

function get_caption(x, y){
	if($('pop').style.display == "inline"){
		$('pop').style.display = "none";
		return;
	}
   
	$('pop').innerHTML = str;
	$('pop').style.left = parseInt(asse_x)+"px";
	$('pop').style.top = parseInt(asse_y)+"px";
	$('pop').style.display = "inline";
}

</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<?php 
setlocale(LC_TIME, "it_IT");
$giorno_str = strftime("%A", strtotime(date("Y-m-d")));
?>
<table class="registro">
<thead>
<tr class="head_tr">
	<td colspan="<?php print ($num_subject + 1) ?>" style="text-align: center; font-weight: bold">
		Riepilogo medie per materia<?php print $label ?>
		<?php if(($_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) || ($_SESSION['__user__']->isAdministrator()) || ($_SESSION['__user__']->getUsername() == "rbachis") ){ ?>
		<a href="dettaglio_medie.php" style="float: right; margin-right: 15px; font-weight: normal">Dettaglio classe</a>
		<?php } ?>
	</td>
</tr>
<tr class="head_tr_no_bg">
	<td style="text-align: center; "><span id="ingresso" style="font-weight: bold; "><?php print $_SESSION['__classe__']->to_string() ?></span></td>
	<td colspan="<?php print ($num_subject + 1) ?>" style="font-weight: bold; text-align: center;">Quadro riassuntivo</td>
</tr>
<tr class="title_tr">
	<td style="width: <?php print $first_column ?>%; font-weight: bold; padding-left: 8px">Alunno</td>
	<?php 
	foreach ($_SESSION['__subjects__'] as $materia) {
	?>
	<td style="width: <?php print $other_column ?>%; text-align: center; font-weight: bold"><?php print $materia['mat'] ?></td>
	<?php 
	}
	?>
</tr>
</thead>
<tbody>
<?php 
$idx = 0;
$stringhe_voto = array();
$sum = array();
$num_alunni = $res_alunni->num_rows;
while($al = $res_alunni->fetch_assoc()){
?>
<tr>
	<td style="width: <?php print $first_column ?>%; font-weight: bold; padding-left: 8px;"><?php if($idx < 9) print "&nbsp;&nbsp;"; ?><?php echo ($idx+1).". " ?>
		<a href="grades_list.php?stid=<?php echo $al['id_alunno'] ?>&q=<?php echo $q ?>" style="font-weight: normal; color: inherit; padding-left: 5px"><?php print $al['cognome']." ".$al['nome']?></a>
	</td>
<?php 
	reset($_SESSION['__subjects__']);
	foreach ($_SESSION['__subjects__'] as $materia) {
		if(!isset($sum[$materia['id']])){
			$sum[$materia['id']]['sum'] = 0;
			$sum[$materia['id']]['num_al'] = $num_alunni;
		}
		$sel_voti = "SELECT rb_voti.* FROM rb_voti WHERE rb_voti.alunno = ".$al['id_alunno']." AND materia = ".$materia['id']." AND anno = ".$_SESSION['__current_year__']->get_ID()." $int_time ORDER BY data_voto DESC";
		try{
			$res_voti = $db->executeQuery($sel_voti);
		} catch (MySQLException $ex){
			$ex->redirect();
		}
		$totale = 0;
		if($res_voti->num_rows < 1){
			$media = "--";
			--$sum[$materia['id']]['num_al'];
		}
		else{
			$num_voti = $res_voti->num_rows;
			
			while($row = $res_voti->fetch_assoc()){
				$totale += $row['voto'];
			}
			$media = round(($totale / $num_voti), 2);
			$sum[$materia['id']]['sum'] += $media;		
		}
?>
	<td style="width: <?php print $other_column ?>%; text-align: center; font-weight: bold">
		<span class="<?php if($media < 6 && $media > 0) print("attention") ?>"><?php print $media ?></span></td>
<?php 
	}
?>	
</tr>
<?php
	$idx++; 
}
?>
</tbody>
<tfoot>
<tr style="height: 30px; background-color: #e8eaec">
	<td style="width: <?php print $first_column ?>%; padding-left: 8px; font-weight: bold">Media classe</td>
<?php 
reset($_SESSION['__subjects__']);
foreach ($_SESSION['__subjects__'] as $materia) {
	$avg = "--";
	if($sum[$materia['id']]['sum'] > 0){ 
		$avg = round(($sum[$materia['id']]['sum'] / $sum[$materia['id']]['num_al']), 2);
	}
?>
	<td style="width: <?php print $other_column ?>%; text-align: center; font-weight: bold;"><span class="<?php if($avg < 6) print("attention") ?>"><?php echo $avg ?></span></td>
<?php 
}
?>	
</tr>
<tr>
	<td colspan="<?php print ($num_subject + 1) ?>" style="height: 15px"></td>
</tr>
<tr class="nav_tr">
	<td colspan="<?php print ($num_subject + 1) ?>" style="text-align: center">
		<a href="summary.php?q=1" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />1 Quadrimestre
		</a>
		<a href="summary.php?q=2" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
			<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />2 Quadrimestre
		</a>
		<a href="summary.php?q=0" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />Totale
		</a>
		<!-- <a href="summary.php?q=1">1 Quadrimestre</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="summary.php?q=2">2 Quadrimestre</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="summary.php?q=0">Totale</a> -->
	</td>
</tr>
</tfoot>
</table>
</div>
<?php include "../footer.php" ?>
</body>
</html>
