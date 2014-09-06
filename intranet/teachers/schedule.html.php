<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
classi = {};
materie = {};
<?php 
while(list($k, $v) = each($classi)){
?>
classi[<?php print $v[0] ?>] = '<?php print $v[1] ?>';
<?php 
}
while(list($k, $v) = each($materie)){
?>
materie[<?php print $v[0] ?>] = '<?php print $v[1] ?>';
<?php 
}
?>
subject = "";
clas = old_clas = "";
hour = "";
day = "";
desc = "";
var giorni = ['', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'];
// readonly
var readonly = <?php if ($readonly) echo "true"; else echo "false" ?>;

function upd_class(classe, id_classe){
	$('#classe').text(classe);
	clas = id_classe;
	//alert(clas);
}

function upd_subject(sub, id_sub){
	$('#materia').text(sub);
	subject = id_sub;
}

function mod_ora(giorno, ora, classe, materia, descrizione){
	if (readonly) {
		alert("Non hai i permessi per modificare l'orario");
		return false;
	}

	subject = materia;
	clas = old_clas = classe;
	hour = ora;
	day = giorno;
	desc = descrizione;

	$('#r1').text(giorni[giorno]+", "+ora+" ora");
	$('#classe').text(classi[classe]);
	$('#materia').text(materie[materia]);

	$('#dialog').dialog({
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
			width: 450,
			title: 'Modifica orario',
			open: function(event, ui){
				
			}
		});
	
	$('#desc').value = desc;	
}

function upd_ora(act){
	// act == 1 => delete
	del = 0;
	if(act == 1){
		del = 1;
	}
	if(old_clas != clas && old_clas != 0){
		act = 1;
	}
	desc = $('#desc').val();

	$.ajax({
		type: "POST",
		url: '../../shared/upd_ora.php',
		data: {act: act, getID: '1', del: del, giorno: day, ora: hour, materia: subject, classe: clas, old_class: old_clas, desc: desc, teacher: <?php echo $_SESSION['__user__']->getUid() ?>},
		dataType: 'json',
		error: function() {
			alert("Errore di trasmissione dei dati");
		},
		succes: function() {
			
		},
		complete: function(data){
			r = data.responseText;
			if(r == "null"){
				return false;
			}
			var json = $.parseJSON(r);
			if (json.status == "kosql"){
				alert(json.message);
				console.log(json.dbg_message);
			}
			else {
				old_clas = 0;
				var parent_row = "ora_"+day+"_"+hour;
				if(del == 0){
				  	$('#'+parent_row).html($('#classe').html()+" -- "+$('#materia').html().substring(0, 19));
				  	if(desc != ""){
					  	$html = $('#'+parent_row).html();
				  		$('#'+parent_row).html($html + " ("+desc+")");
				  	}
				}
				else {
					$('#'+parent_row).text(" -- ");
				}
				//win.hide();
			}
		}
    });
	  
}

</script>
<style>
table a {
	text-decoration: none
}
</style>
</head> 
 <body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "profile_menu.php" ?>
</div>
<div id="left_col">
	<div class="group_head">
		Orario personale del docente  [ <a href="pdf_personal_schedule.php">PDF</a> ] 
	</div>
	<div class="outline_line_wrapper">
		<div style="width: 7%; float: left; position: relative; top: 25%">Ora</div>
		<div style="width: 31%; float: left; position: relative; top: 25%">Luned&igrave;</div>
		<div style="width: 31%; float: left; position: relative; top: 25%">Marted&igrave;</div>
		<div style="width: 31%; float: left; position: relative; top: 25%">Mercoled&igrave;</div>
	</div>
	<form method="post">
        <table style="margin: 10px auto 0 auto; text-align: center; font-size: 1em; width: 90%">
        <?php 
        for($i = 0; $i < $ore; $i++){
        	reset($orario_doc);
        ?>
	        <tr>
	        	<td style="width: 7%; border-bottom: 1px solid; border-right: 1px solid; border-left: 1px solid; border-color: #c0c0c0"><?php print $i+1 ?></td>
	        	<td style="width: 31%; border-bottom: 1px solid; border-right: 1px solid;; border-color: #c0c0c0"><a style="font-weight: normal" href="#" onclick="mod_ora(1, <?php print ($i + 1) ?>, <?php if(isset($orario_doc[1][$i + 1])) print ($orario_doc[1][$i + 1]['classe']); else print "0"; ?>, <?php if(isset($orario_doc[1][$i + 1])) print ($orario_doc[1][$i + 1]['materia']); else print "0"; ?>, '<?php if(isset($orario_doc[1][$i + 1])) print ($orario_doc[1][$i + 1]['descrizione']); else print ""; ?>')" id="ora_1_<?php print ($i + 1) ?>"><?php if(isset($orario_doc[1][$i + 1])) {print ($orario_doc[1][$i + 1]['cl'].$orario_doc[1][$i + 1]['sezione']." -- ".substr($orario_doc[1][$i + 1]['mat'], 0, 19)); if($orario_doc[1][$i + 1]['descrizione'] != "") print(" (".$orario_doc[1][$i + 1]['descrizione'].")");} else print "--"; ?></a></td>
	        	<td style="width: 31%; border-bottom: 1px solid; border-right: 1px solid;; border-color: #c0c0c0"><a style="font-weight: normal" href="#" onclick="mod_ora(2, <?php print ($i + 1) ?>, <?php if(isset($orario_doc[2][$i + 1])) print ($orario_doc[2][$i + 1]['classe']); else print "0"; ?>, <?php if(isset($orario_doc[2][$i + 1])) print ($orario_doc[2][$i + 1]['materia']); else print "0"; ?>, '<?php if(isset($orario_doc[2][$i + 1])) print ($orario_doc[2][$i + 1]['descrizione']); else print ""; ?>')" id="ora_2_<?php print ($i + 1) ?>"><?php if(isset($orario_doc[2][$i + 1])) {print ($orario_doc[2][$i + 1]['cl'].$orario_doc[2][$i + 1]['sezione']." -- ".substr($orario_doc[2][$i + 1]['mat'], 0, 19)); if($orario_doc[2][$i + 1]['descrizione'] != "") print(" (".$orario_doc[2][$i + 1]['descrizione'].")");}else print "--"; ?></a></td>
	        	<td style="width: 31%; border-bottom: 1px solid; border-right: 1px solid;; border-color: #c0c0c0"><a style="font-weight: normal" href="#" onclick="mod_ora(3, <?php print ($i + 1) ?>, <?php if(isset($orario_doc[3][$i + 1])) print ($orario_doc[3][$i + 1]['classe']); else print "0"; ?>, <?php if(isset($orario_doc[3][$i + 1])) print ($orario_doc[3][$i + 1]['materia']); else print "0"; ?>, '<?php if(isset($orario_doc[3][$i + 1])) print ($orario_doc[3][$i + 1]['descrizione']); else print ""; ?>')" id="ora_3_<?php print ($i + 1) ?>"><?php if(isset($orario_doc[3][$i + 1])) {print ($orario_doc[3][$i + 1]['cl'].$orario_doc[3][$i + 1]['sezione']." -- ".substr($orario_doc[3][$i + 1]['mat'], 0, 19)); if($orario_doc[3][$i + 1]['descrizione'] != "") print(" (".$orario_doc[3][$i + 1]['descrizione'].")");}else print "--"; ?></a></td>
	        </tr>
        <?php 
        }
        ?>
        <tr>
            <td colspan="4" style="height: 40px"></td>
        </tr>
        </table>
	<div class="outline_line_wrapper">
		<div style="width: 7%; float: left; position: relative; top: 25%">Ora</div>
		<div style="width: 31%; float: left; position: relative; top: 25%">Gioved&igrave;</div>
		<div style="width: 31%; float: left; position: relative; top: 25%">Venerd&igrave;</div>
		<div style="width: 31%; float: left; position: relative; top: 25%">Sabato</div>
	</div>     
		<table style="margin: 10px auto 0 auto; text-align: center; font-size: 1em; width: 90%">
	        <?php 
	        for($i = 0; $i < $ore; $i++){
	        	reset($orario_doc);
	        ?>
	        <tr>
	        	<td style="width: 7%; border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid; border-color: #c0c0c0"><?php print $i+1 ?></td>
	        	<td style="width: 31%; border-bottom: 1px solid; border-right: 1px solid; border-color: #c0c0c0"><a style="font-weight: normal" href="#" onclick="mod_ora(4, <?php print ($i + 1) ?>, <?php if(isset($orario_doc[4][$i + 1])) print ($orario_doc[4][$i + 1]['classe']); else print "0"; ?>, <?php if(isset($orario_doc[4][$i + 1])) print ($orario_doc[4][$i + 1]['materia']); else print "0"; ?>, '<?php if(isset($orario_doc[4][$i + 1])) print ($orario_doc[4][$i + 1]['descrizione']); else print ""; ?>')" id="ora_4_<?php print ($i + 1) ?>"><?php if(isset($orario_doc[4][$i + 1])) {print ($orario_doc[4][$i + 1]['cl'].$orario_doc[4][$i + 1]['sezione']." -- ".substr($orario_doc[4][$i + 1]['mat'], 0, 19)); if($orario_doc[4][$i + 1]['descrizione'] != "") print(" (".$orario_doc[4][$i + 1]['descrizione'].")");}else print "--"; ?></a></td>
	        	<td style="width: 31%; border-bottom: 1px solid; border-right: 1px solid; border-color: #c0c0c0"><a style="font-weight: normal" href="#" onclick="mod_ora(5, <?php print ($i + 1) ?>, <?php if(isset($orario_doc[5][$i + 1])) print ($orario_doc[5][$i + 1]['classe']); else print "0"; ?>, <?php if(isset($orario_doc[5][$i + 1])) print ($orario_doc[5][$i + 1]['materia']); else print "0"; ?>, '<?php if(isset($orario_doc[5][$i + 1])) print ($orario_doc[5][$i + 1]['descrizione']); else print ""; ?>')" id="ora_5_<?php print ($i + 1) ?>"><?php if(isset($orario_doc[5][$i + 1])) {print ($orario_doc[5][$i + 1]['cl'].$orario_doc[5][$i + 1]['sezione']." -- ".substr($orario_doc[5][$i + 1]['mat'], 0, 19)); if($orario_doc[5][$i + 1]['descrizione'] != "") print(" (".$orario_doc[5][$i + 1]['descrizione'].")");}else print "--"; ?></a></td>
	        	<td style="width: 31%; border-bottom: 1px solid; border-right: 1px solid; border-color: #c0c0c0"><a style="font-weight: normal" href="#" onclick="mod_ora(6, <?php print ($i + 1) ?>, <?php if(isset($orario_doc[6][$i + 1])) print ($orario_doc[6][$i + 1]['classe']); else print "0"; ?>, <?php if(isset($orario_doc[6][$i + 1])) print ($orario_doc[6][$i + 1]['materia']); else print "0"; ?>, '<?php if(isset($orario_doc[6][$i + 1])) print ($orario_doc[6][$i + 1]['descrizione']); else print ""; ?>')" id="ora_6_<?php print ($i + 1) ?>"><?php if(isset($orario_doc[6][$i + 1])) {print ($orario_doc[6][$i + 1]['cl'].$orario_doc[6][$i + 1]['sezione']." -- ".substr($orario_doc[6][$i + 1]['mat'], 0, 19)); if($orario_doc[6][$i + 1]['descrizione'] != "") print(" (".$orario_doc[6][$i + 1]['descrizione'].")");}else print "--"; ?></a></td>
	        </tr>
	        <?php 
	        }
	        ?>
	        <tr>
	            <td colspan="4">&nbsp;&nbsp;&nbsp;</td>
	        </tr>
	        <tr>
	            <td colspan="4">&nbsp;&nbsp;&nbsp;
	             	<input type="hidden" name="mat" />
	        		<input type="hidden" name="id_ora" />
	            </td>
	        </tr>
	    </table>
	    </form>
	</div>
	<p class="spacer"></p>
</div>
<div id="dialog" style="display: none">
<div id="r1" style='font-weight: bold; font-size: 0.95em; text-align: center; margin-top: 10px'></div>
<div style='text-align: center; font-weight: normal; font-size: 0.95em; padding: 5px; margin-top: 10px; padding-bottom: 20px'>	
	Classe: <span id='classe' class='attention' style='font-weight: bold'></span> - 
	Materia: <span id='materia' class='attention' style='font-weight: bold'></span>	
	<table style='width: 85%; margin: auto'>		
		<tr>
			<td colspan='2' style='text-align: center; font-weight: bold; height: 3em'>Modifica l'orario</td>
		</tr>	
		<?php	
		if(count($classi) > count($materie))		
			$max = count($classi);	
		else		
			$max = count($materie);	
		for($i = 0; $i < $max; $i++){	
		?>	
		<tr style=''>		
			<td style='width: 20%; border-bottom: 1px dotted gray'>
				<?php if (isset($classi[$i])): ?>
				<a href='#' onclick='upd_class("<?php print $classi[$i][1] ?>", <?php print $classi[$i][0] ?>)' style='font-weight: normal; color: #373946'><?php print $classi[$i][1] ?></a>
				<?php endif; ?>
			</td>		
			<td style='width: 80%; border-bottom: 1px dotted gray; text-align: left'>
			<?php if (isset($materie[$i])): ?>
				<a href='#' onclick='upd_subject("<?php print $materie[$i][1] ?>", <?php print $materie[$i][0] ?>)' style='font-weight: normal; color: #373946'><?php print $materie[$i][1] ?></a>
			<?php endif; ?>
			</td>	
		</tr>
		<?php	
		}	
		?>
		<tr>		
			<td colspan='2' style='text-align: left; font-weight: normal; height: 2em '>Personalizza&nbsp;&nbsp;&nbsp;&nbsp;
				<input type='text' style='width: 60%; border: 1px solid #467aa7; font-size: 11px; color: gray' maxlength='20' id='desc' value='' />
			</td>	
		</tr>	
	</table>	
	<div style='width: 90%; text-align: right; padding-right: 20px; margin-top: 15px'>	
		<a href='#' onclick='upd_ora(0)' class='standard_link' style='color: #373946' >Registra</a>&nbsp;|&nbsp;	
		<a href='#' onclick='upd_ora(1)' class='standard_link' style='color: #373946' >Cancella</a>	
	</div>
</div>
</div>
<?php include "footer.php" ?>
</body>
</html>
