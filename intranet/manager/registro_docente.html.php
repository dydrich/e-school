<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script>
var _class = <?php echo $start_cls ?>;
var mat = <?php if (count($id_materie) == 1) echo $id_materie[0]; else echo 0; ?>;
$(function() {
    $(".tab").click(function(){
		data = this.id.split("_");
		$(".table_tab").hide();
		$(".tab").removeClass("selected");
		$("#tab_"+data[1]).show(400);
		$(this).addClass("selected");
		_class = data[1];
    });
    $(".snotes").click(function(event){
		event.preventDefault();
		std = this.dataset.std;
		doc = this.dataset.doc;
		show_notes(std, doc, <?php echo $q ?>);
    });
    $(".sgrade").click(function(event){
		event.preventDefault();
		std = this.dataset.std;
		doc = this.dataset.doc;
		subj = this.dataset.subject;
		show_grades(std, doc, <?php echo $q ?>, subj);
    });
    $("#load_lss").click(function(event){
		event.preventDefault();
		load_lessons(<?php echo $doc ?>);
    });
});

var show_notes = function(std, doc, q){
	$('#dialog').load("show_didactic_notes.php?std="+std+"&doc="+doc+"&q="+q, function(){
		$(this).dialog({
			autoOpen: true,
			show: {
				effect: "appear",
				duration: 500
			},
			hide: {
				effect: "slide",
				duration: 300
			},
			buttons: [{
				text: "Chiudi",
				click: function() { 
					$( this ).dialog( "close" ); 
				}
			}],
			modal: true,
			width: 550,
			title: 'Elenco note didattiche',
			open: function(event, ui){
				
			}
		});
	});
};

var show_grades = function(std, doc, q, subj){
	$('#grades').load("show_grades.php?std="+std+"&doc="+doc+"&q="+q+"&subj="+subj, function(){
		$(this).dialog({
			autoOpen: true,
			show: {
				effect: "appear",
				duration: 500
			},
			hide: {
				effect: "slide",
				duration: 300
			},
			buttons: [{
				text: "Chiudi",
				click: function() { 
					$( this ).dialog( "close" ); 
				}
			}],
			modal: true,
			width: 550,
			title: 'Elenco voti',
			open: function(event, ui){
				
			}
		});
	});
};

var load_lessons = function(doc){
	url = "lezioni_docente.php?doc="+doc+"&cls="+_class;
	if (mat != 0) {
		url += "&mat="+mat;
	}
	document.location.href = url;
};

</script>
</head>
<body>
<?php include "header.php" ?>
<?php include $_SESSION['__administration_group__']."/navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include $_SESSION['__administration_group__']."/menu.php" ?>
</div>
<div id="left_col">
	<div class="group_head">
		Registro personale del docente <?php echo $docente['nome']." ".$docente['cognome'] ?>
	</div>
	<div id="tabs" style="">
<?php
foreach ($classi as $k => $cl){
?>
		<div id="stab_<?php echo $k ?>" class="tab <?php if ($cls == $k) echo "selected" ?>"><?php echo $cl['desc'] ?></div>
<?php 
}
?>
        <div id="lessons" class="fright" style="margin-top: 5px"><a href="#" id="load_lss" class="standard_link" style="margin-right: 10px">Lezioni docente</a></div>
	</div>
<?php
reset($classi);
foreach ($classi as $k => $classe){
	$first_column = $other_column = 0;
	$last_column = 15;
	$num_subject = count($classe['materie']);
	if ($num_subject == 1){
		$first_column = 60;
		$other_column = 25;
	}
	else if($num_subject == 2){
		$first_column = 45;
		$other_column = 20;
	}
	else if($num_subject == 3){
		$first_column = 40;
		$other_column = 15;
	}
	else if($num_subject == 4){
		$first_column = 40;
		$other_column = 15;
	}
?>
	<table id="tab_<?php echo $k ?>" class="wd_95 _elem_center table_tab" style="<?php if ($k != $cls) echo "display: none" ?>">
	<thead>
		<tr class="bottom_decoration">
			<td class="_center _bold" colspan="<?php echo $num_subject + 2 ?>">Riepilogo classe <?php echo $classe['desc'].$label ?></td>
		<tr>
		<tr style="border-bottom: 2px solid rgba(30, 67, 137, .2)">
			<td class="_bold" style="width: <?php print $first_column ?>%; padding-left: 8px">Alunno</td>
			<?php 
			$mat = array();
			foreach ($classe['materie'] as $j => $materia) {
			$mat[$j] = $materia;
			?>
			<td class="_bold _center" style="width: <?php print $other_column ?>%"><?php echo $materia ?></td>
			<?php 
			}
			?>
			<td class="_bold _center" style="width: <?php echo $last_column ?>%">Note</td>
		</tr>
	<?php
	$num_alunni = count($classe['alunni']);
	$sum = null;
	$sum = array();
	foreach ($classe['alunni'] as $x => $alunno){		
	?>
		<tr class="bottom_decoration">
			<td style="width: <?php print $first_column ?>%; padding-left: 8px"><?php echo $alunno['cognome']." ".$alunno['nome'] ?></td>
			<?php 
			foreach ($mat as $m => $materia) {
				if (!isset($sum[$m])){
					$sum[$m] = 0;
				}
				if (isset($alunno['materie'][$m]['media'])){
					$sum[$m] += $alunno['materie'][$m]['media'];
				}
			?>
			<td data-std="<?php echo $x ?>" data-doc="<?php echo $doc ?>" data-subject="<?php echo $m ?>" class="sgrade _center <?php if (isset($alunno['materie'][$m]) && $alunno['materie'][$m]['media'] < 6) echo "attention _bold" ?>" style="width: <?php print $other_column ?>%"><?php if (isset($alunno['materie'][$m])) echo $alunno['materie'][$m]['media'] ?></td>
			<?php 
			}
			?>
			<td class="_center" style="width: <?php echo $last_column ?>%"><a href="#" class="snotes no_dec" data-std="<?php echo $x ?>" data-doc="<?php echo $doc ?>"><?php if (isset($alunno['note']) && count($alunno['note']) > 0) echo $alunno['note']." note"; else echo "--" ?></a></td>
		</tr>
	<?php 
	}
	?>
		<tr class="list_row_menu">
			<td class="_bold" style="padding-left: 8px">Totale classe</td>
			<?php 
			$mat = array();
			foreach ($classe['materie'] as $j => $materia) {
				$mat[$j] = $materia;
				$md = round($sum[$j] / $num_alunni, 2);
			?>
			<td class="_bold _center" style="width: <?php print $other_column ?>%"><?php echo $md ?></td>
			<?php 
			}
			?>
			<td style="width: <?php echo $last_column ?>"></td>
		</tr>
	</table>
<?php 
}
?>
<div id="dialog"></div>
<div id="grades"></div>
</div>
<p class="spacer"></p>		
</div>
<?php include "footer.php" ?>	
</body>
</html>
