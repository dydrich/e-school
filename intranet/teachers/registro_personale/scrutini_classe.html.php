<!DOCTYPE html>
<html>
<head>
<title>Registro di classe</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_classe.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg_print.css" type="text/css" media="print" />
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
var upd_grade = function(sel, alunno, subj){
	var url = "upd_grade.php";
	//alert(subj);
	var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {grade: sel.value, alunno: alunno, q: <?php echo $q ?>, subj: subj},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split(";");
			      		if(dati[0] == "kosql"){
							sqlalert();
							console.log(dati[1]+"\n"+dati[2]);
							return false;
			     		}
			     		else{
					        <?php if ($ordine_scuola == 1): ?>
			     			$('avg'+alunno).update(dati[1]+"/"+dati[2]);
			     			if(parseInt(dati[1]) < <?php echo $_SESSION['__config__']['limite_sufficienza'] ?>)
			     				$('avg'+alunno).addClassName("attention");
			     			else
			     				$('avg'+alunno).removeClassName("attention");
					        <?php endif; ?>
			     		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};

function show_menu(el) {
	if($('menu_div').style.display == "none") {
	    position = getElementPosition(el);
	    dimensions = $(el).getDimensions();
	    ftop = position['top'] + dimensions.height;
	    fleft = position['left'] - 140 + dimensions.width;
	    console.log("top: "+ftop+"\nleft: "+fleft);
	    $('menu_div').setStyle({top: ftop+"px", left: fleft+"px", position: "absolute", zIndex: 100});
	    $('menu_div').blindDown({duration: 0.5});
	}
	else {
		$('menu_div').hide();
	}
}

function show_context_menu(el) {
	if($('context_menu').style.display == "none") {
	    position = getElementPosition(el);
	    dimensions = $(el).getDimensions();
	    ftop = position['top'] + dimensions.height;
	    fleft = position['left'] - 50 + dimensions.width;
	    console.log("top: "+ftop+"\nleft: "+fleft);
	    $('context_menu').setStyle({top: ftop+"px", left: fleft+"px", position: "absolute", zIndex: 100});
	    $('context_menu').blindDown({duration: 0.5});
	}
	else {
		$('context_menu').hide();
	}
}

var set_f = function(id_es){
	var url = "set_outcome.php";
	//alert(url);
	var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {outcome: id_es, alunno: stid},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split(";");
			      		if(dati[0] == "kosql"){
							sqlalert();
							console.log(dati[1]+"\n"+dati[2]);
							return false;
			     		}
			     		else{
			     			if (dati[1] == 0){
								$('st_'+stid).parentNode.parentNode.setStyle({backgroundColor: 'rgba(225,11,52,.2)'});
			     			}
			     			else{
			     				$('st_'+stid).parentNode.parentNode.setStyle({backgroundColor: 'rgba(30, 67, 137, .2)'});
			     			}	
			     		}
			     		show_context_menu('');
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};

document.observe("dom:loaded", function(){
	$('imglink').observe("click", function(event){
		event.preventDefault();
		show_menu('imglink');
	});
	$('menu_div').observe("mouseleave", function(event){
		event.preventDefault();
        $('menu_div').hide();
    });
	$('context_menu').observe("mouseleave", function(event){
		event.preventDefault();
		$('context_menu').hide();
	});
<?php if ($q == 2): ?>
	$$('.setstid').invoke("observe", "click", function(event){
		//alert(this.id);
		event.preventDefault();
		var strs = this.id.split("_");
		stid = strs[1];
		show_context_menu(this.id);
	});
<?php else: ?>
	$$('.setstid').invoke("observe", "click", function(event){
		//alert(this.id);
		event.preventDefault();
		// do_nothing();
	});
<?php endif; ?>
});
</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<table class="registro">
<thead>
<tr class="head_tr">
	<td colspan="<?php echo $num_colonne - 2 ?>" style="text-align: center; font-weight: bold; border-right: 0">
		Scrutini - <?php echo $label ?>
	</td>
	<td colspan="2" style="border-left: 0">
		<a href="../shared/no_js.php" id="imglink" style="">
            <img src="../../../images/19.png" id="ctx_img" style="margin: 0 0 4px 0; opacity: 0.5; vertical-align: bottom" />
       	</a>
	</td>
</tr>
<tr class="head_tr_no_bg">
	<td style="text-align: center"><span id="ingresso" style="font-weight: bold; "><?php echo $_SESSION['__classe__']->to_string() ?></span></td>
	<td colspan="<?php echo ($num_colonne - 1) ?>" style="font-weight: bold; text-align: center">Quadro riassuntivo della classe</td>
</tr>
<tr class="title_tr">
	<td rowspan="2" style="width: <?php echo $first_column_width ?>%; font-weight: bold; padding-left: 2px">Alunno</td>
	<?php 
	foreach($materie as $materia){
	?>
	<td colspan="2" <?php if($materia['id_materia'] == 2 || $materia['id_materia'] == 40 || true === $grades_only) echo ("rowspan='2'") ?> style="width: <?php echo $column_width * 2 ?>%; text-align: center; font-weight: bold"><?php echo strtoupper(substr($materia['materia'], 0, 3)) ?></td>
	<?php 
	}
	?>
</tr>
<tr class="title_tr">
	<?php 
	for($i = 0; $i < $res_materie->num_rows - 1; $i++){
		if(!$grades_only){
	?>
	<td style="width: <?php echo $column_width ?>%; text-align: center; font-weght: bold">V</td>
	<td style="width: <?php echo $column_width ?>%; text-align: center; font-weght: bold">A</td>
	<?php 
		}
	}
	?>
</tr>
</thead>
<tbody>
<?php
$idx = 0;
while($al = $res_alunni->fetch_assoc()){
	$student_sum = 0;
	$student_corrected_sum = 0;
	$num_materie = count($materie);
	$st_bckg = "";
	if ($q == 2){
		$sel_idpubblicazione = "SELECT MAX(id_pagella) AS id_pagella FROM rb_pubblicazione_pagelle WHERE anno = {$_SESSION['__current_year__']->get_ID()}";
		try{
			$res_idp = $db->executeQuery($sel_idpubblicazione);
		} catch (MySQLException $ex){
			$ex->redirect();
		}
		$row = $res_idp->fetch_assoc();
		$idp = $row['id_pagella'];
		$sel_outcome = "SELECT esito FROM rb_pagelle WHERE id_pubblicazione = {$idp} AND id_alunno = {$al['id_alunno']}";
		$res_outcome = $db->executeQuery($sel_outcome);
		$_outcome = $res_outcome->fetch_assoc();
		$outcome = $_outcome['esito'];
		if ($outcome != "" && $outcome != null){
			$sel_es = "SELECT * FROM rb_esiti WHERE id_esito = {$outcome}";
			$res_es = $db->executeQuery($sel_es);
			$row_es = $res_es->fetch_assoc();
			$es = $row_es['positivo'];
			if ($es == 0){
				$st_bckg = "background-color: rgba(225,11,52,.2);";
			}
			else {
				$st_bckg = "background-color: rgba(30, 67, 137, .2);";
			}
		}
	}
	$background = "background-color: #e8eaec";
?>
<tr>
	<td style="<?php echo $st_bckg ?>width: <?php echo $first_column ?>%; padding-left: 8px; font-weight: bold"><?php if($idx < 9) echo "&nbsp;&nbsp;"; ?><?php echo ($idx+1).". " ?>
		<span style="font-weight: normal"><a href="../../shared/no_js.php" class="setstid" id="st_<?php echo $al['id_alunno'] ?>"><?php echo $al['cognome']." ".substr($al['nome'], 0, 1) ?>.</a></span>
		<span style="float: right; margin-right: 18px; font-weight: bold" id="avg<?php echo $al['id_alunno'] ?>"></span>	
	</td>
<?php 
	reset($materie);
	foreach($materie as $materia){
		$sel_voti = "SELECT voto, assenze FROM rb_scrutini WHERE alunno = ".$al['id_alunno']." AND materia = ".$materia['id_materia']." AND anno = ".$_SESSION['__current_year__']->get_ID()." AND quadrimestre = $q";
		//print $sel_voti;
		try{
			$res_voti = $db->executeQuery($sel_voti);
		} catch (MySQLException $ex){
			$ex->redirect();
		}
		$dt = $res_voti->fetch_assoc();
		if($dt['voto'] != "" && ($materia['id_materia'] != 26 && $materia['id_materia'] != 30)){
			$student_sum += $dt['voto'];
			if ($dt['voto'] < 6){
				$student_corrected_sum += 6;
			}
			else {
				$student_corrected_sum += $dt['voto'];
			}
		}
		else{
			$num_materie--;
		}
		if($materia['id_materia'] == 2 || $materia['id_materia'] == 40){
?>
	<td style="width: <?php echo $column_width*2 ?>%; text-align: center">
		<?php 
		if (!$readonly){ 
		?>
		<select name="sel_<?php echo $al['id_alunno'] ?>" style="width: 45px; height: 15px; font-size: 11px" onchange="upd_grade(this, <?php echo $al['id_alunno'] ?>, <?php echo $materia['id_materia'] ?>)">
			<option value="0">NC</option>
			<?php 
			if ($ordine_scuola == 1){
				for($i = 10; $i > 0; $i--){ 
			?>
			<option value="<?php echo $i ?>" <?php if($dt['voto'] == $i) echo "selected" ?>><?php echo $i ?></option>
			<?php 
				} 
			}else {
				foreach ($voti_comportamento_primaria as $k => $val){		
			?>
			<option value="<?php echo $k ?>" <?php if($dt['voto'] == $k) echo "selected" ?>><?php echo $val['nome'] ?></option>
			<?php
				}
			}
			?>
		</select>
		<?php 
		} 
		else{
			if ($ordine_scuola == 1){
		?>
		<span style="font-weight: bold"><?php echo $dt['voto'] ?></span>
		<?php
			}
			else {
		?>
			<span style="font-weight: bold"><?php echo $voti_comportamento_primaria[$dt['voto']]['codice'] ?></span>
		<?php
			}
		} 
		?>
	</td>
<?php 
		}
		else{
			if(!$modification){
				if(!$grades_only){
?>
	<td style="width: <?php echo $column_width ?>%; text-align: center; font-weight: bold;<?php echo $background ?>"><span class="<?php if($dt['voto'] < 6 && $dt['voto'] > 0) echo("attention") ?>"><?php echo $dt['voto'] ?></span></td>
	<td style="width: <?php echo $column_width ?>%; text-align: center; font-weight: normal;"><?php echo $dt['assenze'] ?></td>
<?php 
				}
				else{
?>
	<td style="text-align: center; font-weight: bold;" colspan="2"><span class="<?php if($dt['voto'] < 6 && $dt['voto'] > 0) echo("attention") ?>"><?php echo $dt['voto'] ?></span></td>
<?php					
				}
			}
			else{
?>
	<td colspan="2">
	<select name="sel_<?php echo $al['id_alunno'] ?>_<?php echo $materia['id_materia'] ?>" style="width: 45px; height: 15px; font-size: 11px" onchange="upd_grade(this, <?php echo $al['id_alunno'] ?>, <?php echo $materia['id_materia'] ?>)">
			<option value="0">NC</option>
			<?php for($i = 10; $i > 0; $i--){ ?>
			<option value="<?php echo $i ?>" <?php if($dt['voto'] == $i) echo "selected" ?>><?php echo $i ?></option>
			<?php } ?>
		</select>
	</td>
<?php				
			}
		}
	}
	$student_avg = "0";
	$student_corrected_avg = "0";
	if($num_materie > 0){
		$student_avg = round(($student_sum / $num_materie), 2);
		$student_corrected_avg = round(($student_corrected_sum / $num_materie), 2);
	}
	if ($ordine_scuola == 1):
?>	
	<script>
		$('avg<?php echo $al['id_alunno'] ?>').innerHTML = "<?php echo $student_avg; ?> / <?php echo $student_corrected_avg ?>";
		if(<?php echo $student_avg; ?> < <?php echo $_SESSION['__config__']['limite_sufficienza'] ?>)
			$('avg<?php echo $al['id_alunno'] ?>').addClassName("attention");
	</script>
<?php
	endif;
?>
</tr>
<?php
	$idx++;
}
?>
</tbody>
<tfoot>
<tr>
	<td colspan="<?php echo $num_colonne ?>" style="height: 15px"></td>
</tr>
<tr class="nav_tr">
	<td colspan="<?php echo $num_colonne ?>" style="text-align: center; height: 40px">
		<a href="scrutini_classe.php?q=1" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />1 Quadrimestre
		</a>
		<a href="scrutini_classe.php?q=2" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />2 Quadrimestre
		</a>
	</td>
</tr>
</tfoot>
</table>
</div>
<?php include "../footer.php" ?>
<div id="menu_div" class="page_menu" style="width: 190px; height: 120px; position: absolute; padding: 10px 0 10px 0px; display: none">
	<?php if ($ordine_scuola != 2): ?>
    <a href="scrutini_classe.php?q=<?php echo $q ?>&view=grade_only" style="padding-left: 10px; line-height: 16px">Vedi solo i voti</a><br />
    <a href="scrutini_classe.php?q=<?php echo $q ?>" style="padding-left: 10px; line-height: 16px">Vedi voti e assenze</a><br />
	<a href="stampa_scrutini_classe.php?q=<?php echo $q ?>&abs=1" style="padding-left: 10px; line-height: 16px">Stampa riepilogo completo</a><br />
    <?php endif; ?>
	<a href="stampa_scrutini_classe.php?q=<?php echo $q ?>&abs=0" style="padding-left: 10px; line-height: 16px">Stampa riepilogo voti</a><br />
    <?php if (!$readonly): ?>
    <a href="scrutini_classe.php?q=<?php echo $q ?><?php echo $modification_params ?>" style="padding-left: 10px; line-height: 16px">Modifica i voti</a><br />
    <?php endif; ?>
    <?php if ($q == 2 && ($_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID()) || $_SESSION['__user__']->getUsername() == "rbachis")): ?>
	    <?php if ($ordine_scuola == 1): ?>
	<a href="certificazione_competenze.php" style="padding-left: 10px; line-height: 16px">Certificazione delle competenze</a><br />
	    <?php endif; ?>
    <a href="crea_tabellone.php" style="padding-left: 10px; line-height: 16px">Crea il tabellone esiti</a><br />
    <?php endif; ?>
    <?php if ($ordine_scuola == 2): ?>
    <a href="parametri_pagella.php?q=<?php echo $q ?>" style="padding-left: 10px; line-height: 16px">Livello di maturazione</a><br />
    <?php endif; ?>
</div>
<?php if ($q == 2): ?>
<!-- menu contestuale -->
    <div id="context_menu" style="position: absolute; width: 240px; height: 120px; display: none">
	    <a style="font-weight: normal" href="#" onclick="set_f(17)">Anno non validato</a><br />
    <?php
    while ($row = $res_out->fetch_assoc()){
    ?>
    	<a style="font-weight: normal" href="#" onclick="set_f(<?php echo $row['id_esito'] ?>)"><?php echo $row['esito'] ?></a><br />
	<?php
	}
	?>
    </div>
<!-- fine menu contestuale -->
<?php endif; ?>
</body>
</html>
