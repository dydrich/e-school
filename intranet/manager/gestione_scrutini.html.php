<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
<link rel="stylesheet" href="../../css/reg.css" type="text/css" media="screen,projection" />
<link href="../../css/skins/aqua/theme.css" type="text/css" rel="stylesheet"  />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript" src="../../js/calendar.js"></script>
<script type="text/javascript" src="../../js/lang/calendar-it.js"></script>
<script type="text/javascript" src="../../js/calendar-setup.js"></script>
<script type="text/javascript">
var timestamp;
var tm = 0;
var complete = false;
var timer;

var change_status = function(q){
	var url = "modifica_stato_scrutinio.php";
	var stato = $F('status'+q+'q');
	//alert(q+"  "+stato);
	var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {q: q, stato: stato},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split("#");
			      		if(dati[0] == "kosql"){
			      			sqlalert();
							//console.log(dati[1]+"\n"+dati[2]);
							return false;
			     		}
			     		else{
				     		_alert("Stato scrutinio modificato");	
			     		}
			     		
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};




document.observe("dom:loaded", function(){
	$('status1q').observe("change", function(event){
		event.preventDefault();
		change_status(1);
	});
	$('status2q').observe("change", function(event){
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
