<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti - obiettivi didattici</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var _checked = <?php if (isset($goal)) echo count($goal['classi']); else echo "0" ?>;
		var registra = function(){
			msg = "Ci sono degli errori. Ricontrolla il form\n";
			index = 0;
			ok = true;
			if($('#obj').val() == ""){
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
				j_alert("error", msg);
				return false;
			}
			$.ajax({
				type: "POST",
				url: 'goal_manager.php',
				data: $('#my_form').serialize(true),
				dataType: 'json',
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
						j_alert("error", json.message);
						console.log(json.dbg_message);
					}
					else if(json.status == "ko") {
						j_alert("error", "Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore al webmaster");
						return;
					}
					else {
						j_alert("alert", "Operazione eseguita");
					}
				}
			});
		};

		var cancella = function(){
			if(!confirm("Sei sicuro di voler cancellare questo obiettivo?")){
				return false;
			}
			$('#action').val(2);
			$.ajax({
				type: "POST",
				url: 'goal_manager.php',
				data: $('#my_form').serialize(true),
				dataType: 'json',
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
						j_alert("error", json.message);
						console.log(json.dbg_message);
					}
					else if(json.status == "ko") {
						j_alert("error", "Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore al webmaster");
						return;
					}
					else {
						j_alert("alert", "Operazione eseguita");
					}
				}
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

		$(function(){
			load_jalert();
			setOverlayEvent();
		});
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
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=teachers"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=teachers"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
