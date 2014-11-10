<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var toggle_div = function(div){
			if(div == 'new_sig'){
				other_div = 'old_sig';
			}
			else{
				other_div = 'new_sig';
			}

			if($('#'+div).is(":hidden")){
				if ($('#'+other_div).is(":visible")) {
					$('#' + other_div).fadeOut(100);
				}
				$('#'+div).slideDown(1000);
			}
			else {
				$('#'+div).slideUp(1000);
			}
		};

		var firma = function(id_registro, ora, id_ora, action){
			var url = "firma.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {id_reg: id_registro, ora: ora, id_ora: id_ora, action: action, mat: 33},
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
						alert(json.message);
						console.log(json.dbg_message);
					}
					else if(json.status == "ko") {
						j_alert("error", "Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore al webmaster");
						return;
					}
					else {
						if (action == 'sign'){
							$("#p_"+ora).html("");
							$('<span style="margin-right: 20px">'+ora+' ora</span>').appendTo($("#p_"+ora));
							var span = document.createElement('span');
							$('<span>Sostituzione</span>').appendTo($("#p_"+ora));
						}
						else {
							$('#tr_'+id_ora).hide();
						}
					}
				}
			});
		};

		var load_signatures = function(){
			if($('#classe').val() == 0 || $('#data').val() == ""){
				alert("Scegli una classe ed una data per firmare");
				return false;
			}
			var url = "get_signatures.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {data: $('#data').val(), classe: $('#classe').val()},
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
						alert(json.message);
						console.log(json.dbg_message);
					}
					else if(json.status == "ko") {
						j_alert("error", "Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore al webmaster");
						return;
					}
					else {
						$('#signatures').html("");
						for(data in json.firme){
							var t = json.firme[data];
							//alert(t.id_registro);
							$('<p id="p_'+ t.ora+'" style="border-bottom: 1px solid #CCC; line-height: 10px"><span style="margin-right: 20px">'+ t.ora+' ora</span></p>').appendTo($('#signatures'));
							if(t.dmat != 0){
								$('<span>'+ t.dmat+'</span>')
							}
							else{
								var update = 0;
								if(t.id != 0){
									update = 1;
								}
								$('<a href="#" onclick="firma('+t.id_registro+', '+t.ora+', '+t.id+', \'sign\')" style="text-decoration: none">Firma</a>').appendTo($('#p_'+ t.ora));
							}
							$('#signatures').css({
								backgroundColor: 'rgba(211, 222, 199, 0.2)',
								padding: '10px',
								border: '1px solid rgba(211, 222, 199, 1)',
								borderRadius: '6px'
							});
						}
					}
				}
			});
		};

		var set_data = function(){
			if($('data').val() != ""){
				$('#tr_classe').show(500);
			}
			else{
				$('#tr_classe').hide();
			}
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#data').datepicker({
				dateFormat: "dd/mm/yy"
			});
			$('#show_new_sig').click(function(event){
				toggle_div('new_sig');
			});
			$('#show_sig').click(function(event){
				toggle_div('old_sig');
			});
			$('#classe').change(function(event){
				load_signatures();
			});
			$('tr.show_del').mouseover(function(event){
				var strs = this.id.split("_");
				$('#unsign_'+strs[1]).show();
			});
			$('tr.show_del').mouseout(function(event){
				var strs = this.id.split("_");
				$('#unsign_'+strs[1]).hide();
			});
			$('a.del_sign').click(function(event){
				var strs = this.id.split("_");
				var idreg = this.dataset.idreg;
				var ora = this.dataset.ora;
				firma(idreg, ora, strs[1], 'unsign');
			});
		});
	</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "../working.php" ?>
</div>
<div id="left_col">
	<div style="width: 70%; margin: 30px auto">
		<div class="rowcard">
			<a href="#" id="show_new_sig">Firma per una sostituzione</a>
		</div>
		<div style="width: 99%; border: 1px solid #DDDDDD; margin: 5px 0 0 0; display: none; padding: 10px; clear: left; position: relative; top: -10px" id="new_sig">
			<p style="margin: 0 0 10px 0; width: 70%; text-align: left">Seleziona una classe ed una data per firmare</p>
			<form method="post" id="myform" name="myform">
			<table style="width: 70%; margin: auto">
				<tr>
					<td style="width: 30%">Data</td>
					<td style="width: 70%">
						<input type="text" id="data" name="data" style="width: 95%" onchange="set_data()" />
					</td>
				</tr>
				<tr id="tr_classe" style="display: none">
					<td style="width: 30%">Classe</td>
					<td style="width: 70%">
						<select id="classe" name="classe" style="width: 95%">
							<option value="0">.</option>
							<?php 
							while($row = $res_classes->fetch_assoc()){
							?>
							<option value="<?php echo $row['id_classe'] ?>"><?php echo $row['anno_corso'],$row['sezione'] ?></option>
							<?php 
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div style="width: 90%" id="signatures">
						
						</div>
					</td>
				</tr>
			</table>
			</form>
		</div>
		<br /><br />
		<div class="rowcard">
			<a href="#" id="show_sig">Visualizza le sostituzioni effettuate</a>
		</div>
		<div style="width: 99%; border: 1px solid #DDDDDD; margin: 5px 0 0 0; display: none; padding: 10px; background-color: rgba(211, 222, 199, 0.2)" id="old_sig">
		<?php 
		if($res_subs->num_rows < 1){
		?>
		Nessuna sostituzione effettuata finora
		<?php 
		}
		else{
		?>
			<table style="width: 85%">
			<tr style="width: 30px; border-bottom: 1px solid rgba(211, 222, 199, 1)">
				<td style="width: 40%; padding: 10px 0">Data</td>
				<td style="width: 15%; text-align: center">Classe</td>
				<td style="width: 15%; text-align: center">Ora</td>
				<td style="width: 30%; text-align: center"></td>
			</tr>
		<?php
			while($sub = $res_subs->fetch_assoc()){	
		?>
			<tr id="tr_<?php echo $sub['id'] ?>" class="show_del" style="border-bottom: 1px solid rgba(211, 222, 199, 1)">
				<td style="width: 40%"><?php echo format_date($sub['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></td>
				<td style="width: 15%; text-align: center"><?php echo $sub['anno_corso'],$sub['sezione'] ?></td>
				<td style="width: 15%; text-align: center"><?php echo $sub['ora'] ?></td>
				<td style="width: 30%; text-align: center"><a href="#" id="unsign_<?php echo $sub['id'] ?>" data-idreg="<?php echo $sub['id_registro'] ?>" data-ora="<?php echo $sub['ora'] ?>" class="del_sign" style="display: none; text-decoration: none">Elimina</a></td>
			</tr>
		<?php
			}
		?>
		</table>
		<?php
		}
		?>
		</div>
	</div>
</div>
<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link">
			<a href="registro_classe.php?data=<?php echo date("Y-m-d") ?>"><img src="../../../images/28.png" style="margin-right: 10px; position: relative; top: 5%"/>Registro di classe</a>
		</div>
		<div class="drawer_link">
			<a href="stats.php"><img src="../../../images/18.png" style="margin-right: 10px; position: relative; top: 5%"/>Statistiche</a>
		</div>
		<div class="drawer_link separator">
			<a href="notes.php"><img src="../../../images/26.png" style="margin-right: 10px; position: relative; top: 5%"/>Note</a>
		</div>
		<div class="drawer_link"><a href="../registro_personale/index.php"><img src="../../../images/4.png" style="margin-right: 10px; position: relative; top: 5%" />Registro personale</a></div>
		<div class="drawer_link separator"><a href="../gestione_classe/classe.php"><img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />Gestione classe</a></div>
		<div class="drawer_link"><a href="../index.php"><img src="../../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="../profile.php"><img src="../../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../../modules/documents/load_module.php?module=docs&area=teachers"><img src="../../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../../shared/do_logout.php"><img src="../../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
