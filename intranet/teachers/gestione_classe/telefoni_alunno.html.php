<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Recapiti telefonici alunno</title>
	<link rel="stylesheet" href="../../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		var main = 0;
		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#show_new').click(function(event){
				$('#new_phone').toggle("blind", 400);
				$(this).parent().toggleClass("accent_decoration");
			});
			$('#add_phone').click(function(event){
				$('#action').val("new");
				save();
			});
			$('.del').click(function(event){
				$('#action').val("del");
				$('#idp').val($(this).attr("data-id"));
				save();
			});
			$('.upd').click(function(event){
				$('#action').val("upd");
				$('#idp').val($(this).attr("data-id"));
				save();
			});
			$('.main_check').change(function(event) {
				$id = $(this).data("id");
				if ($(this).prop("checked") == true) {
					$('#main_p').val($id);
					$('label').css({fontWeight: "normal"});
					$('label[for="main_'+$id+'"]').css({fontWeight: "bold"});
					$('.main_check').each(function(event){
						if ($(this).data("id") != $id) {
							$(this).prop("checked", false);
						}
					});
				}
			});
		});

		var numeri = new Array();
		<?php
		if (count($tel) > 0){
			reset($tel);
			foreach ($tel as $row){
		?>
		numeri[<?php echo $row['id'] ?>] = new Object();
		numeri[<?php echo $row['id'] ?>].numero = '<?php echo $row['telefono'] ?>';
		numeri[<?php echo $row['id'] ?>].desc = '<?php echo $row['descrizione'] ?>';
		<?php
			}
		}
		?>

		var save = function(){
			$.ajax({
				type: "POST",
				url: "phone_manager.php",
				data: $('#testform').serialize(true),
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
					else {
						j_alert("alert", json.message);
						switch ($('#action').val()) {
							case "new":
								setTimeout(function() {
									document.location.href = "telefoni_alunno.php?stid=<?php echo $_REQUEST['stid'] ?>";
								}, 2000);
								break;
							case "del":
								id = $('#idp').val();
								$('#row_'+id).hide();
								break;
						}

					}
				}
			});
		};
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
		<div style="top: -10px; margin-left: 35px; margin-bottom: -10px" class="rb_button">
			<a href="elenco_alunni.php">
				<img src="../../../images/47bis.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<form id="testform" method="post" class="no_border">
			<input type="hidden" id="action" name="action" />
			<input type="hidden" id="idp" name="idp" />
			<input type="hidden" id="main_p" name="main_p" />
			<input type="hidden" id="stid" name="stid" value="<?php echo $_REQUEST['stid'] ?>" />
			<fieldset class="wd_90 _elem_center">
				<legend>Telefono</legend>
				<div class="wd_95 _elem_center">
					<div class="wd_100" id="container">
						<?php
						if (count($tel) == 0){
							?>
							Non &egrave; presente alcun recapito telefonico
						<?php
						}
						else{
							foreach ($tel as $numbr){
								$class = "";
								if ($numbr['principale'] == 1) {
									$class = "_bold";
								}
								?>
								<div id="row_<?php echo $numbr['id'] ?>" class="wd_95 <?php echo $class ?> accent_decoration" style="padding-bottom: 10px; margin-bottom: 40px">
									<div class="wd_20 fleft">Numero</div>
									<div class="wd_45 fleft"><input type="text" id="number_<?php echo $numbr['id'] ?>" name="number_<?php echo $numbr['id'] ?>" class="wd_95 android" value="<?php echo $numbr['telefono'] ?>" /></div>
									<div class="wd_5 fleft"> </div>
									<div class="wd_25 fleft">
										<label for="main_<?php echo $numbr['id'] ?>">Principale</label>
										<input type="checkbox" class="main_check" data-id="<?php echo $numbr['id'] ?>" name="main_<?php echo $numbr['id'] ?>" id="main_<?php echo $numbr['id'] ?>" value="<?php echo $numbr['id'] ?>" <?php if ($numbr['principale'] == 1) echo "checked='checked'" ?> />
									</div>
									<div class="wd_20 fleft">Descrizione</div>
									<div class="wd_70 fleft"><input type="text" id="desc_<?php echo $numbr['id'] ?>" name="desc_<?php echo $numbr['id'] ?>" class="wd_95 android" value="<?php echo $numbr['descrizione'] ?>" /></div>
									<div style="position: relative; top: 10px" class="main_700">
										<a href="#" class="upd" data-id="<?php echo $numbr['id'] ?>" style="margin: 0 10px 0 10px" title="Salva"><i class="fa fa-upload"></i></a>
										<a href="#" class="del" data-id="<?php echo $numbr['id'] ?>" title="Cancella"><i class="fa fa-trash"></i></a>
									</div>
									<p class="bclear"></p>
								</div>
								<?php
							}
						}
						?>
						<p class="bclear">
							<a href="#" id="show_new" class="material_link" style="">Nuovo numero</a>
						</p>
						<div id="new_phone" class="wd_95" style="display: none">
							<div class="wd_20 fleft">Numero</div>
							<div class="wd_45 fleft"><input type="text" id="number" name="number" class="wd_95" style="" /></div>
							<div class="wd_5 fleft"> </div>
							<div class="wd_25 fleft">
								<label for="main_phone">Principale</label>
								<input type="checkbox" name="main_phone" id="main_phone" value="1" />
							</div>
							<div class="wd_20 fleft">Descrizione</div>
							<div class="wd_75 fleft"><input type="text" id="desc" name="desc" class="wd_95" /></div>
							<p class="bclear"></p>
							<div style="margin-top: 15px">
								<a href="#" id="add_phone" class="material_link" style="">Aggiungi</a>
							</div>

						</div>
					</div>
				</div>
			</fieldset>
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
