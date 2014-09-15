<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Alunni</title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script>
var del = function(id){
	var url = "elimina_segnalazione.php";
	$.ajax({
		type: "POST",
		url: url,
		data: {id: id},
		error: function() {
			j_alert("error", "Errore di trasmissione dei dati");
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
				sqlalert();
				console.log(json.dbg_message);
				return;
			}
			else if(json.status == "ko") {
				j_alert("error", "Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore al webmaster");
				return;
			}
			else {
				$('#row'+id).hide();
			}
		}
	});
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
		Elenco alunni con sostegno
	</div>
	<div class="outline_line_wrapper">
		<div style="width: 40%; float: left; position: relative; top: 30%">Alunno</div>
		<div style="width: 15%; float: left; position: relative; top: 30%; text-align: center">Classe</div>
		<div style="width: 30%; float: left; position: relative; top: 30%; text-align: center">Docente</div>
		<div style="width: 10%; float: left; position: relative; top: 30%; text-align: center">Ore</div>
		<div style="width:  5%; float: left; position: relative; top: 30%; text-align: center"></div>
	</div>
   		<table style="width: 95%; margin: 20px auto 0 auto">
	 	    <?php
	 	    if ($res_sos->num_rows < 1){
			?>
			<tr>
				<td colspan="5" style="height: 55px; font-weight: bold; text-align: center; font-size: 1.1em">Nessun alunno trovato</td>
			</tr> 	
			<?php
			}
			else {
	 	    	while($alunno = $res_sos->fetch_assoc()){
					$teachs = array();
					$teacher = "Non assegnato";
					$sel_teach = "SELECT cognome, nome FROM rb_utenti, rb_assegnazione_sostegno WHERE uid = docente AND anno = {$anno} AND alunno = {$alunno['alunno']} ORDER BY cognome, nome";
					$res_teach = $db->execute($sel_teach);
					if ($res_teach->num_rows > 0){
						while ($r = $res_teach->fetch_assoc()){
							$teachs[] = $r['cognome']." ".$r['nome'];
						}
						$teacher = implode(", ", $teachs);
					}
	 	    ?>
 	    	<tr id="row<?php echo $alunno['alunno'] ?>" class="docs_row">
 	    		<td style="width: 40%; text-align: left"><?php echo $alunno['stud'] ?></td>
 	    		<td style="width: 15%; text-align: center"><?php echo $alunno['classe'] ?></td>
 	    		<td style="width: 30%; text-align: center"><?php echo $teacher ?></td>
 	    		<td style="width: 10%; text-align: center">
 	    		<p id="c<?php echo $alunno['alunno'] ?>" style="height: 14px; margin: 1px"><?php echo $alunno['ore'] ?></p>
 	    		<script type="text/javascript"> 
					new Ajax.InPlaceEditor('c<?php print $alunno['alunno'] ?>', 'ore_sostegno.php', { 
						callback: function(form, value) { return 'f=<?php echo $alunno['alunno'] ?>&val='+encodeURIComponent(value); }
					});
				</script>
				</td>
				<td style="width: 5%; text-align: center" class="attention"><a href="#" onclick="del(<?php echo $alunno['alunno'] ?>)" style="font-weight: bold; font-size: 1.1em; text-decoration: none; color: red">x</a></td>
 	    	</tr>
	 	    
	 	    <?php
	 	    	}
	 	    }
            ?>
            <tr>
	    		<td colspan="5" style="height: 25px"></td> 
		    </tr>
		    <tr>
	    		<td colspan="5" style="height: 25px; text-align: right">
	    			<a href="segnala_alunno.php" class="standard_link">Nuova segnalazione</a>
	    		</td> 
		    </tr>
		</table>		
	</div>
<p class="spacer"></p>		
</div>
<?php include "footer.php" ?>	
</body>
</html>
