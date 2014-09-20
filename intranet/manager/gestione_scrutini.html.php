<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
var timestamp;
var tm = 0;
var complete = false;
var timer;

var change_status = function(q){
	var url = "modifica_stato_scrutinio.php";
	var stato = $('#status'+q+'q').val();
	alert(q+"  "+stato);
	$.ajax({
		type: "POST",
		url: url,
		data: {q: q, stato: stato},
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
				j_alert("alert", json.message);
			}
		}
	});

};

$(function(){
	load_jalert();
	$('#status1q').change(function(event){
		event.preventDefault();
		change_status(1);
	});
	$('#status2q').change(function(event){
		event.preventDefault();
		change_status(2);
	});
});	

</script>
<style>

</style>
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
		Gestione scrutini <?php echo $school ?>
	</div>
	<div class="welcome">
		<p id="w_head"><?php echo $_SESSION['__current_year__']->to_string() ?></p>
		<table style="width: 350px">
			<tr id="upd_q1">
				<td style="width: 70%">Scrutinio primo quadrimestre</td>
				<td style="width: 30%" id="f_text">
				<select name="status1q" id="status1q" style="width: 100px">
				<?php 
				foreach ($stati as $s){
					if ($st_1q == "closed" && $s['id_stato'] == 3){
						continue;
					}
					else {
				?>
					<option value="<?php echo $s['id_stato'] ?>" <?php if ($s['id_stato'] == $scrutini[1][$field]) echo "selected" ?>><?php echo $s['stato'] ?></option>
				<?php 
					} 
				}	
				?>
					
				</select>
				</td>
			</tr>
			<tr id="upd_q2">
				<td style="width: 70%">Scrutinio secondo quadrimestre</td>
				<td style="width: 30%" id="f_text">
				<select name="status2q" id="status2q" style="width: 100px">
				<?php 
				foreach ($stati as $s){
					if ($st_2q == "closed" && $s['id_stato'] == 2){
						continue;
					}
					else {
				?>
					<option value="<?php echo $s['id_stato'] ?>" <?php if ($s['id_stato'] == $scrutini[2][$field]) echo "selected" ?>><?php echo $s['stato'] ?></option>
				<?php 
					} 
				}	
				?>
					
				</select>
				</td>
			</tr>
		</table>
	</div>
</div>
<p class="spacer"></p>
</div>
<div class="overlay" id="over1" style="display: none">
    <div id="wait_label" style="position: absolute; display: none; padding-top: 25px">Operazione in corso</div>
</div>
<?php include "footer.php" ?>
</body>
</html>
