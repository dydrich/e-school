<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var save_homework = function(){
			//alert($('myform').serialize());
			if(document.forms[0].del.value == 1){
				if(!confirm("Sei sicuro di voler cancellare questo compito?"))
					return false;
			}
			$.ajax({
				type: "POST",
				url: "save_homework.php",
				data: $('#myform').serialize(),
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
						j_alert('error', json.message);
						console.log(json.dbg_message);
					}
					else {
						j_alert('alert', json.message);
						window.setTimeout(function(){
							document.location.href = '<?php echo $ref ?>';
						}, 2000);
					}
				}
			});
		};

		var oldLink = null;

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#sel3').datepicker({
				dateFormat: "dd/mm/yy"
			});
			$('#save_button').click(function(event){
				event.preventDefault();
				save_homework();
			});
			$('#del_button').click(function(event){
				event.preventDefault();
				document.forms[0].del.value = 1;
				save_homework();
			});
		});
	</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
	<?php include "class_working.php" ?>
	</div>
	<div id="left_col">
	<form id="myform" class="reg_form">
	<table style="width: 75%; margin: 20px auto 20px auto">
		<tr>
			<td style="width: 20%" class="label_form">Per il</td>
			<td style="width: 80%">
				<input id="sel3" type="text" value="<?php if($upd == 1) print format_date($di, SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>" style="width: 100%; padding-top: 3px" name="data_inizio" />&nbsp;
			</td>
		</tr>
		<tr>
			<td style="width: 20%" class="label_form">Materia</td>
			<td style="width: 80%">
				<select name="materia" id="tipo" style="width: 100%">
					<option value="0">Nessuna</option>
	<?php
	while($mat = $res_materie->fetch_assoc()){
	?>
				<option <?php if((isset($att)) && $mat['id_materia'] == $att['materia']) print("selected='selected'") ?> value="<?php print $mat['id_materia'] ?>"><?php print $mat['materia'] ?></option>
	<?php
	}
	?>
				</select>
			</td>
		</tr>
		<tr>
			<td style="width: 20%" class="label_form">Dettagli</td>
			<td style="width: 80%">
				<textarea style="width: 100%; height: 40px" name="descrizione"><?php if($upd == 1) print $att['descrizione'] ?></textarea>
			</td>
		</tr>
		<tr>
			<td style="width: 20%" class="label_form">Note</td>
			<td>
				<textarea style="width: 100%; height: 40px" name="note"><?php if($upd == 1) print $att['note'] ?></textarea>
				<input type="hidden" name="id_act" value="<?php echo $t ?>" />
				<input type="hidden" name="del" />
			</td>
		</tr>
		</table>
		<div style="width: 92%; margin: 20px 0 10px 0; text-align: right">
			<?php if($t != 0){ ?>
				<a href="../../../shared/no_js.php" id="del_button" class="material_link nav_link_first">Elimina</a>
			<?php } ?>
			<a href="../../../shared/no_js.php" id="save_button" class="material_link nav_link_last">Registra</a>
		</div>
	</form>
</div> 
<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_label"><span>Classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></span></div>
		<div class="drawer_link submenu"><a href="../registro_classe/registro_classe.php?data=<?php echo date("Y-m-d") ?>"><img src="../../../images/28.png" style="margin-right: 10px; position: relative; top: 5%" />Registro di classe</a></div>
		<div class="drawer_link submenu separator"><a href="../registro_personale/index.php"><img src="../../../images/4.png" style="margin-right: 10px; position: relative; top: 5%" />Registro personale</a></div>
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
