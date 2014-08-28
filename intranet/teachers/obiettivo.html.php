<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti - obiettivi didattici</title>
<link rel="stylesheet" href="../../css/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/md5-min.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript">
var _checked = <?php if (isset($goal)) echo count($goal['classi']); else echo "0" ?>;
var registra = function(){
	msg = "Ci sono degli errori. Ricontrolla il form\n";
	index = 0;
	ok = true;
	if(trim($F('obj')).empty()){
		index++;
		msg += index+". Non hai inserito alcun obiettivo.\n";
		ok = false;
	}
	if (_checked < 1){
		index++;
		msg += index+". Non hai selezionato una classe.";
		ok = false;
	}
	if (!ok){
		alert(msg);
		return false;
	}
	
	req = new Ajax.Request('goal_manager.php',
			  {
			    	method:'post',
			    	parameters: $('my_form').serialize(true),
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		_alert("Operazione eseguita con successo");
		            	if(dati[1] == "redirect"){
							window.location = "index.php";
		            	}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};

var cancella = function(){
	if(!confirm("Sei sicuro di voler cancellare questo obiettivo?")){
		return false;
	}
	$('action').value = 2;
	alert($('action').value);
	req = new Ajax.Request('goal_manager.php',
			  {
			    	method:'post',
			    	parameters: $('my_form').serialize(true),
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		_alert("Operazione eseguita con successo");
		            	window.location = "obiettivi.php";
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};

var upd_check = function(elem){
	if(elem.checked){
		_checked++;
	}
	else{
		_checked--;
	}
};
</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
	<?php include "profile_menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head"><?php echo $label ?></div>
		<form id="my_form" method="post" action="goal_manager.php?oid=<?php echo $_GET['oid'] ?>" style="border: 1px solid rgba(30, 67, 137, .8); border-radius: 10px; margin-top: 30px; text-align: left; width: 460px; margin-left: auto; margin-right: auto">
		<table style="width: 400px; margin-left: auto; margin-right: auto; margin-top: 30px; margin-bottom: 20px">
			<tr>
				<td style="width: 40%">Materia</td>
				<td style="width: 60%">
					<select id="subj" name="subj" style="width: 250px">
					<?php 
					while ($row = $res_materie->fetch_assoc()){
					?>
						<option value="<?php echo $row['id_materia'] ?>" <?php if (isset($goal) && $row['id_materia'] == $goal['materia']) echo "selected" ?>><?php echo $row['materia'] ?></option>
					<?php 
					}
					?>
					</select>
				</td> 
			</tr>
			<tr>
				<td style="width: 40%">Obiettivo</td>
				<td style="width: 60%"><textarea name="obj" id="obj" style="width: 250px; height: 50px; font-size: 11px; border: 1px solid #AAAAAA"><?php if(isset($goal)) echo $goal['nome'] ?></textarea></td>
			</tr>
			<tr>
				<td style="width: 40%">Classi</td>
				<td style="width: 60%">
				<?php 
				foreach ($classes as $k => $cls){
					if ($cls['teacher'] == 1){
						$checked = "";
						if (isset($goal)){
							reset($goal['classi']);
							foreach ($goal['classi'] as $j => $r){
								if ($j == $cls['id_classe']){
									$checked = "checked";
								}
							}
						}
				?>
					<input type="checkbox" <?php echo $checked ?> onclick="upd_check(this)" id="<?php echo $cls['classe'] ?>" value="<?php echo $cls['id_classe'] ?>" name="classi[]" /><label for="<?php echo $cls['classe'] ?>" style="margin-right: 10px; position: relative; top: -5px"><?php echo $cls['classe'] ?></label>
				<?php
					}
				}
				?>
				</td> 
			</tr>
			<tr>
				<td style="width: 40%">Sottobiettivo di:</td>
				<td style="width: 60%">
					<select id="idp" name="idp" style="width: 250px">
						<option value="0">Nessuno</option>
					<?php 
					while ($row = $res_obj->fetch_assoc()){
					?>
						<option value="<?php echo $row['id'] ?>" <?php if (isset($goal) && $goal['id_padre'] == $row['id']) echo "selected" ?>><?php echo $row['nome'] ?></option>
					<?php 
					}
					?>
					</select>
				</td> 
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td> 
			</tr>
			<tr>
				<td colspan="2" style="text-align: right; margin-right: 50px">
					<a href="#" onclick="registra()" style="text-decoration: none; text-transform: uppercase; margin-right: 10px">Registra</a>
					<?php if (isset($goal)): ?>
					|<a href="#" onclick="cancella()" style="text-decoration: none; text-transform: uppercase; margin-left: 10px">Cancella</a>
					<?php endif; ?>
				</td> 
			</tr>
		</table>
		<input type="hidden" id="oid" name="oid" value="<?php echo $_GET['oid'] ?>" />
		<input type="hidden" id="action" name="action" value="<?php echo $action ?>" />
		<input type="hidden" id="subject" name="subject" value="<?php echo $subject ?>" />
		<input type="hidden" id="teacher" name="teacher" value="<?php echo $uid ?>" />
		<input type="hidden" id="year" name="year" value="<?php echo $anno ?>" />
		<input type="hidden" id="ordine_scuola" name="ordine_scuola" value="<?php echo $ordine_di_scuola ?>" />
		</form>
	</div>
	<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
