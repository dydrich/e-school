<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Dettaglio alunno</title>
	<link rel="stylesheet" href="../../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#birth').datepicker({
				dateFormat: "dd/mm/yy",
				changeYear: true,
				yearRange: "1999:<?php echo date("Y") ?>"
			});
			$('.profile_print').click(function(event){
				event.preventDefault();
				print_profile();
			});
			$('.phone_buttons').css({"display": "none"});
			//$('#pers_button').button();
			$('#pers_button').click(function(event){
				event.preventDefault();
				save('pers');
			});
			//$('#addr_button').button();
			$('#addr_button').click(function(event){
				event.preventDefault();
				save('addr');
			});
			$('.phone_number').mouseover(function(event){
				var i = this.id.split("_")[1];
				$('#phb'+i).show();
			});
			$('.phone_number').mouseout(function(event){
				var i = this.id.split("_")[1];
				$('#phb'+i).hide();
			});
		});

		var indirizzi = new Array();
		<?php
		$i = 0;
		if (isset($tel) && count($tel) > 0){
			reset($t);
			foreach ($t as $row){
		?>
		indirizzi[<?php echo $i ?>] = '<?php echo $row ?>';
		<?php
				$i++;
			}
		}
		?>

		var save = function(area){
			$('#area').val(area);
			$.ajax({
				type: "POST",
				url: "aggiorna_alunno.php",
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
					}
				}
		    });
		};

		var print_profile = function(){
			document.location.href = "pdf_dettaglio_alunno.php?stid="+stid;
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
			<input type="hidden" id="area" name="area" />
			<input type="hidden" id="stid" name="stid" value="<?php echo $_REQUEST['stid'] ?>" />
			<fieldset class="wd_80 _elem_center">
				<legend>Dati anagrafici</legend>
				<div class="wd_90 notification" id="notpers"></div>
				<div class="wd_90 _elem_center">
					<div class="wd_35 fleft">Cognome</div>
					<div class="wd_65 fleft row"><input type="text" id="lname" name="lname" class="wd_95" value="<?php echo $alunno['cognome'] ?>" /></div>
					<div class="wd_35 fleft">Nome</div>
					<div class="wd_65 fleft row"><input type="text" id="fname" name="fname" class="wd_95" value="<?php echo $alunno['nome'] ?>" /></div>
					<div class="wd_35 fleft">Data di nascita</div>
					<div class="wd_65 fleft row"><input type="text" id="birth" name="birth" class="wd_95" value="<?php echo format_date($alunno['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>" /></div>
					<div class="wd_35 fleft">Luogo di nascita</div>
					<div class="wd_65 fleft"><input type="text" id="city" name="city" class="wd_95" value="<?php echo $alunno['luogo_nascita'] ?>" /></div>
					<p class="bclear"></p>
					<div class="_right" id="reg_button" style="width: 96%">
						<a href="#" id="pers_button" class="material_link">Registra</a>
					</div>
				</div>
			</fieldset>
			<fieldset class="wd_80 _elem_center">
				<legend>Domicilio</legend>
				<div class="wd_90 notification" id="notaddr"></div>
				<div class="wd_90 _elem_center">
					<div class="wd_35 fleft">Indirizzo</div>
					<div class="wd_65 fleft row"><input type="text" id="address" name="address" class="wd_95" value="<?php echo $alunno['indirizzo'] ?>" /></div>
					<div class="wd_35 fleft">Citt&agrave;</div>
					<div class="wd_65 fleft row"><input type="text" id="residence" name="residence" class="wd_95" value="<?php echo $alunno['citta'] ?>" /></div>
					<p class="bclear"></p>
					<div class="_right" id="reg_button" style="width: 96%">
						<a href="#" id="addr_button" class="material_link">Registra</a>
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
