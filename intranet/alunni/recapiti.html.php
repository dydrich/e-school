<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area studenti</title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
	$(function(){
		load_jalert();
		setOverlayEvent();
	});

var registra = function(){
	if($('#indirizzo').val() == "" && $('#telefono').val() == "" && $('#cellulare').val() == ""){
		alert("Non hai inserito nessun recapito");
		return false;
	}
	$.ajax({
		type: "POST",
		url: 'save_profile.php',
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
				alert(json.message);
				console.log(json.dbg_message);
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
</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "profile_working.php" ?>
</div>
<div id="left_col">
	<form id="my_form" method="post" action="dati.php" style="border: 1px solid #666666; border-radius: 10px; margin-top: 20px; text-align: left; width: 460px; margin-left: auto; margin-right: auto">
	<table style="width: 400px; margin-left: auto; margin-right: auto; margin-top: 30px; margin-bottom: 30px">
		<tr>
			<td style="width: 60%">Indirizzo</td>
			<td style="width: 40%"><input type="text" name="indirizzo" id="indirizzo" style="width: 250px; font-size: 11px" value="<?php if(isset($rec)) print $rec['indirizzo']; ?>" /></td>
		</tr>
		<tr>
			<td style="width: 60%">Telefono</td>
			<td style="width: 40%"><input type="text" name="telefono" id="telefono" style="width: 250px; font-size: 11px" value="<?php if(isset($rec)) print $rec['telefono1']; ?>" /></td>
		</tr>
		<tr>
			<td style="width: 60%">Cellulare</td>
			<td style="width: 40%"><input type="text" name="cellulare" id="cellulare" style="width: 250px; font-size: 11px" value="<?php if(isset($rec)) print $rec['telefono2']; ?>" /></td>
		</tr>
		<tr>
			<td style="width: 60%">Altro telefono</td>
			<td style="width: 40%"><input type="text" name="telefono2" id="telefono2" style="width: 250px; font-size: 11px" value="<?php if(isset($rec)) print $rec['telefono3']; ?>" /></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td> 
		</tr>
		<tr>
			<td colspan="2" style="text-align: right; margin-right: 50px">
				<a href="#" onclick="registra()" style="text-decoration: none; text-transform: uppercase">Registra</a>
				<input type="hidden" name="action" id="action" value="address" />
			</td> 
		</tr>
	</table>
	</form>
</div>
<p class="spacer"></p>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=alunni"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=alunni"><img src="<?php echo $_SESSION['__path_to_root__'] ?>images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
