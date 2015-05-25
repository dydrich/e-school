<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Attivit&agrave;</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
	$(function(){
		$('#day').datepicker({
			dateFormat: "dd/mm/yy",
			currentText: "Oggi",
			closeText: "Chiudi"
		});
		load_jalert();
		setOverlayEvent();
	});

	function registra(){
		if ($('#day').val() == "" || $('#att').val() == ""){
			alert("Data e lavoro svolto sono campi obbligatori");
			return false;
		}
		$.ajax({
			type: "POST",
			url: "aggiorna_dati_studente.php",
			data: $('#my_form').serialize(),
			dataType: 'json',
			error: function(data, status, errore) {
				j_alert("error", "Si e' verificato un errore");
				return false;
			},
			succes: function(result) {

			},
			complete: function(data, status){
				r = data.responseText;
				var json = $.parseJSON(r);
				if(json.status == "kosql"){
					j_alert("error", "Errore SQL. \nQuery: "+json.query+"\nErrore: "+json.message);
					return;
	            }
				else {
					j_alert("alert", json.message);
					setTimeout("document.location.href='attivita.php'", 2000);
				}
			}
		});
	}
	</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
	<?php include "menu_sostegno.php" ?>
	</div>
	<div id="left_col">
		<form id="my_form" method="post" action="aggiorna_dati_nucleo.php" style="border: 1px solid rgba(30, 67, 137, .8); border-radius: 10px; margin-top: 30px; text-align: left; width: 90%; margin-left: auto; margin-right: auto">
		<table style="width: 90%; margin-left: auto; margin-right: auto; margin-top: 30px; margin-bottom: 20px">
			<tr>
				<td style="width: 40%">Data</td>
				<td style="width: 60%"><input type="text" name="day" id="day" style="width: 95%; font-size: 11px; border: 1px solid #AAAAAA" value="<?php if (isset($att)) echo format_date($att['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"); else if (isset($_REQUEST['data'])) echo format_date($_REQUEST['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>" /></td>
			</tr>
			<tr>
				<td style="width: 40%">Attivit&agrave; svolta</td>
				<td style="width: 60%"><textarea name="att" id="att" style="width: 95%; font-size: 11px; border: 1px solid #AAAAAA; height: 80px"><?php if (isset($att)) echo $att['attivita'] ?></textarea></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;
					<input type="hidden" id="area" name="area" value="attivita" />
					<input type="hidden" id="id" name="id" value="<?php echo $_GET['id'] ?>" />
				</td>				
			</tr>
			<tr>
				<td colspan="2" style="text-align: right; margin-right: 50px">
					<a href="#" onclick="registra()" class="standard_link">Registra</a>
					<?php if ($_GET['id'] != 0): ?>
					<span style="margin: 5px">|</span>
					<a href="#" onclick="$('#area').val('delatt'); registra()" class="standard_link">Elimina</a>
					<?php endif; ?>
				</td> 
			</tr>
		</table>
		</form>
	</div>
	<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_label"><span>Classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></span></div>
		<div class="drawer_link submenu"><a href="index.php?id_st=<?php echo $_SESSION['__sp_student__']['alunno'] ?>"><img src="../../../images/4.png" style="margin-right: 10px; position: relative; top: 5%" />Registro personale</a></div>
		<div class="drawer_link submenu"><a href="../registro_classe/registro_classe.php?data=<?php echo date("Y-m-d") ?>"><img src="../../../images/28.png" style="margin-right: 10px; position: relative; top: 5%" />Registro di classe</a></div>
		<div class="drawer_link submenu separator"><a href="../gestione_classe/classe.php"><img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />Gestione classe</a></div>
		<div class="drawer_link"><a href="../index.php"><img src="../../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="../profile.php"><img src="../../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../../modules/documents/load_module.php?module=docs&area=teachers"><img src="../../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=teachers"><img src="<?php echo $_SESSION['__path_to_root__'] ?>images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../../shared/do_logout.php"><img src="../../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
