<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Registro di classe</title>
<link rel="stylesheet" href="../registro_classe/reg_classe.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../registro_classe/reg_print.css" type="text/css" media="print" />
<link href="../../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../../js/prototype.js"></script>
<script type="text/javascript" src="../../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript" src="../../../js/window.js"></script>
<script type="text/javascript" src="../../../js/window_effects.js"></script>
<script type="text/javascript">
var stid = 0;

<?php echo $change_subject->getJavascript() ?>

function change_subject(id){
	document.location.href="index.php?subject="+id+"&q=<?php print $q ?>&__goals__=1";
}

function student(sid, quad){
	document.forms[0].action = "student.php?stid="+sid+"&q="+quad;
	document.forms[0].submit();
}

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
	$('pop').show();
}

function show_menu(e, _stid){
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
    stid = _stid;
    return false;
}

function new_grade(quad){
	var win = new Window({className: "mac_os_x",  url: "mark.php?q="+quad+"&alunno="+stid+"&action=new&referer=list", width:400, height:250, zIndex: 100, resizable: true, title: "Dettaglio voto", showEffect:Effect.BlindDown, hideEffect: Effect.SwitchOff, draggable:true, wiredDrag: true});
	win.showCenter(true);
	$('context_menu').hide();
}

function grades(q){
	student(stid, q);
}

function add_note(q){
	win = new Window({className: "mac_os_x", url: "new_note.php?stid="+stid,  width:400, height:250, zIndex: 100, resizable: true, title: "Note didattica", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});	
	win.showCenter(true);
	$('context_menu').hide();
}

function notes(q){
	document.forms[0].action = "student_notes.php?stid="+stid+"&q="+q;
	document.forms[0].submit();
}

document.observe("dom:loaded", function(){
	$('context_menu').observe("mouseleave", function(event){
		this.hide();
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
	<td colspan="<?php echo $tot_col ?>"><?php print $_SESSION['__current_year__']->to_string() ?><?php print $label ?></td>
</tr>
<tr style="height: 25px">
	<td colspan="2" style="text-align: center; "><span id="ingresso" style="font-weight: bold; "><?php print $_SESSION['__classe__']->to_string() ?></span></td>
	<td colspan="<?php echo $right_cols ?>" style="text-align: center; ">Materia: <span id="uscita" style="font-weight: bold; text-transform: uppercase"><?php $change_subject->printLink(); ?></span></td>
</tr>
<tr class="title_tr">
	<td rowspan="2" style="width: 30%; font-weight: bold; padding-left: 8px">Alunno</td>
	<td rowspan="2" style="width: 10%; text-align: center; font-weight: bold">Media</td>
	<td colspan="<?php echo $_goals ?>" style="width: 20%; text-align: center; font-weight: bold">Obiettivi</td>
</tr>
<tr class="title_tr">
	<?php
	foreach ($goals as $j => $x) {
	?>
	<td style="width: <?php echo $len ?>%; text-align: center; font-weight: bold"><span id="tip_<?php echo $j ?>" class="showtip" style="font-size: 0.9em"><?php echo $x['nome'] ?></span></td>
	<?php
	} 
	?>
</tr>
</thead>
<tbody>
<?php
$idx = 0;
$tot_classe = 0;
$t_voti = 0;
$studenti = array();
while($al = $res_alunni->fetch_assoc()){
	$st = array();
	$st['id'] = $al['id_alunno'];
	$st['value'] = $al['cognome']." ".substr($al['nome'], 0, 1).".";
	$sel_avg = "SELECT ROUND(AVG(voto), 2) FROM rb_voti WHERE alunno = {$al['id_alunno']} AND materia = ".$_SESSION['__materia__']." AND anno = ".$_SESSION['__current_year__']->get_ID()." $int_time ";
	$avg = $db->executeCount($sel_avg);
	if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
		$_media = round($avg);
		if($_media < 5.5){
			$_media = 4;
		}
		else if ($_media > 6.49 && $_media < 8){
			$_media = 8;
		}
		$avg = $voti_religione[$_media];
	}
	$st['avg'] = $avg;
	array_push($studenti, $st);
	
	foreach ($vars as $k => $vv){
		$vars[$k]["num_prove"] = 0;
		$vars[$k]["media"] = 0;
		$vars[$k]["somma"] = 0;
	}
	
	$sel_voti = "SELECT rb_voti_obiettivo.* FROM rb_voti_obiettivo, rb_voti WHERE rb_voti.id_voto = rb_voti_obiettivo.id_voto AND rb_voti.alunno = ".$al['id_alunno']." AND materia = ".$_SESSION['__materia__']." AND anno = ".$_SESSION['__current_year__']->get_ID()." $int_time ORDER BY data_voto DESC";
	try{
		$res_voti = $db->executeQuery($sel_voti);
	} catch (MySQLException $ex){
		$ex->redirect();
	}
	
	$totale = 0;
	if($res_voti->num_rows < 1){
		$media = "--";
		--$numero_alunni;
		reset($totali_classe);
		while(list($k, $v) = each($totali_classe)){
			$totali_classe[$k]['num_alunni']--;
		}
		$num_voti = 0;
	}
	else{
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
			$vars[$row['obiettivo']]['num_prove']++;
			$vars[$row['obiettivo']]['somma'] += $row['voto'];
			$totali_classe[$row['obiettivo']]['num_prove']++;
			//$totali_classe[$row['obiettivo']]['somma'] += $row['voto'];
			$t_voti++;
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
		if($num_orali > 0 ) $media_orali = round(($tot_orali / $num_orali), 2);
		if($num_scritti > 0) $media_scritti = round(($tot_scritti / $num_scritti), 2);
		$medie_classe['scr'] += $media_scritti;
		$medie_classe['ora'] += $media_orali;
		$medie_classe['tot'] += $media;
		$stringhe_voto[$idx] .= "</p>";
	}
	$sel_note = "SELECT COUNT(id_nota) FROM rb_note_didattiche WHERE alunno = ".$al['id_alunno']." AND materia = ".$_SESSION['__materia__']." $note_time ORDER BY data DESC";
	$num_note = $db->executeCount($sel_note);
	$_media = round($media);
	if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
		//$_media = round($avg);
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
	<td style="width: 30%; padding-left: 8px; font-weight:bold; "><?php if($idx < 9) print "&nbsp;&nbsp;"; ?><?php echo ($idx+1).". " ?>
		<a href="#" onclick="show_menu(event, <?php echo $al['id_alunno'] ?>)" style="font-weight: normal; color: inherit; padding-left: 8px"><?php print $al['cognome']." ".substr($al['nome'], 0, 1)."." ?></a>
		<?php if($num_note > 0){?><!-- &nbsp;(<?php echo $num_note ?> note didattiche) --><?php } ?>
	</td>
	<td style="width: 10%; text-align: center; font-weight: bold"><?php echo $avg ?></td>
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
	<td style="width: <?php echo $len ?>%; text-align: center; font-weight: bold;"><span class="<?php if($media < $_SESSION['__config__']['limite_sufficienza'] && $media > 0) print("attention") ?>"><?php if($vs['num_prove'] < 1) echo "--"; else echo $_voto." (".$vs['num_prove'].")" ?></span></td>
	<?php } ?>	
</tr>
<?php
	$idx++; 
}
$_SESSION['students'] = $studenti;
?>
</tbody>
<tfoot>
<tr style="height: 30px; background-color: #e8eaec">
	<td style="width: 30%; font-weight: bold; padding-left: 8px">
		Totale classe
	</td>
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
			<a href="index.php?q=1&subject=<?php echo $_SESSION['__materia__'] ?>&__goals__=1" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />1 Quadrimestre
			</a>
			<a href="index.php?q=2&subject=<?php echo $_SESSION['__materia__'] ?>&__goals__=1" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
				<img style="margin-right: 5px; position: relative; top: 5px" src="../../../images/quad.png" />2 Quadrimestre
			</a>
			<a href="index.php?q=0&subject=<?php echo $_SESSION['__materia__'] ?>&__goals__=1" style="vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
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
<!-- tootip -->
<div id="mytip" style="position: absolute; width: 200px; height: 100px; display: none; background-color: rgba(239, 244, 234, 1); border: 1px solid #AAA; border-radius: 5px">
	<div style="width: 12px; position: relative; height: 10px; text-align: center; margin-right: 0px; margin-left: 187px; margin-top: 0; padding-bottom: 4px"><a href="#" onclick="$('mytip').hide()" style="text-decoration: none; font-weight: bold; color: #444">x</a></div>
	<p id="tiptext" style="position: relative; width: 190px; height: 100px; margin: auto; text-align: center"></p>
</div>
<?php include "../footer.php" ?>
</body>
</html>