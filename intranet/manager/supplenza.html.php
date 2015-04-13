<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Supplenza</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var register = function(){
			var bool = true;
			var msg = "Sono presenti degli errori nel form.\n";
			var ind = 0;
			if($('#doc').val() == ""){
				ind++;
				msg += "\n"+ind+". Docente non inserito";
				$("#lab1").css({color: "#ff0000"});
				bool = false;
			}
			else {
				$("#lab1").css({color: "inherit"});
			}
			if($('#sup').val() == ""){
				ind++;
				msg += "\n"+ind+". Supplente non inserito";
				$("#lab2").css({color: "#ff0000"});
				bool = false;
			}
			else {
				$("#lab2").css({color: "inherit"});
			}
			if($('#inizio').val() == ""){
				ind++;
				msg += "\n"+ind+". Data di inizio non inserita";
				$("#lab3").css({color: "#ff0000"});
				bool = false;
			}
			else {
				$("#lab3").css({color: "inherit"});
			}
			if($('#fine').val() == ""){
				ind++;
				msg += "\n"+ind+". Data di termine non inserita";
				$("#lab4").css({color: "#ff0000"});
				bool = false;
			}
			else {
				$("#lab4").css({color: "inherit"});
			}
			if (!bool) {
				alert(msg);
				return false;
			}

			var url = "substitution_manager.php";
			var act = $('#action').val();
			$.ajax({
				type: "POST",
				url: url,
				data: $('#my_form').serialize(true),
				dataType: 'json',
				error: function() {
					show_error("Errore di trasmissione dei dati");
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
						return false;
					}
					else {
						alert(json.message);
						if (act == "new") {
							document.location.href = "elenco_supplenze.php?status=open";
						}
					}
				}
			});

		};

		var del = function(){
			if (!confirm("Sei sicuro di voler eliminare la supplenza?")){
				return false;
			}
			var url = "substitution_manager.php";
			$('#action').val("delete");
			$.ajax({
				type: "POST",
				url: url,
				data: $('#my_form').serialize(true),
				dataType: 'json',
				error: function() {
					show_error("Errore di trasmissione dei dati");
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
						return false;
					}
					else {
						alert(json.message);
						document.location.href = "elenco_supplenze.php?status=open";
					}
				}
			});
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$("#doc").autocomplete({
				source: "../../shared/get_users.php?group=teachers&ord=<?php echo $_SESSION['__school_order__'] ?>&supp=s",
				minLength: 2,
				select: function(event, ui){
					uid = ui.item.uid;
					$('#docID').val(uid);
				}
			});
			$("#sup").autocomplete({
				source: "../../shared/get_users.php?group=teachers&ord=<?php echo $_SESSION['__school_order__'] ?>&supp=n",
				minLength: 2,
				select: function(event, ui){
					uid = ui.item.uid;
					$('#supID').val(uid);
				}
			});
			$("#inizio").datepicker({
				dateFormat: "dd/mm/yy",
				altFormat: "dd/mm/yy"
			});
			$("#fine").datepicker({
				dateFormat: "dd/mm/yy",
				altFormat: "dd/mm/yy"
			});
		});
	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include $_SESSION['__administration_group__']."/navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include $_SESSION['__administration_group__']."/menu_supplenze.php" ?>
	</div>
	<div id="left_col">
		<form id="my_form" method="post" action="" style="margin-top: 30px; text-align: left; width: 90%; margin-left: auto; margin-right: auto">
			<table style="width: 90%; margin: 30px auto 10px auto">
				<tr>
					<td style="width: 25%" id="lab1">Docente</td>
					<td style="width: 75%">
						<input type="text" name="doc" id="doc" style="width: 95%; font-size: 11px; border: 1px solid #AAAAAA" value="<?php if (isset($subs)) echo $subs->getLecturer()->getFullName() ?>" />
					</td>
				</tr>
				<tr>
					<td style="width: 25%" id="lab2">Supplente</td>
					<td style="width: 75%">
						<input type="text" name="sup" id="sup" style="width: 95%; font-size: 11px; border: 1px solid #AAAAAA" value="<?php if (isset($subs)) echo $subs->getSubstitute()->getFullName() ?>" />
					</td>
				</tr>
				<tr>
					<td style="width: 25%" id="lab3">Data inizio</td>
					<td style="width: 75%">
						<input type="text" name="inizio" id="inizio" style="width: 95%; font-size: 11px; border: 1px solid #AAAAAA" value="<?php if (isset($subs)) echo format_date($subs->getStartDate(), SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>" />
					</td>
				</tr>
				<tr>
					<td style="width: 25%" id="lab4">Data fine</td>
					<td style="width: 75%">
						<input type="text" name="fine" id="fine" style="width: 95%; font-size: 11px; border: 1px solid #AAAAAA" value="<?php if (isset($subs)) echo format_date($subs->getEndDate(), SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>" />
					</td>
				</tr>
				<tr>
					<td style="width: 25%" id="lab5">Classi</td>
					<td style="width: 75%">
						<?php
						while ($cls = $res_classi->fetch_assoc()) {
							$selected = "";
							if (isset($subs) && in_array($cls['id_classe'], array_keys($subs->getClasses()))) {
								$selected = "checked";
							}
						?>
						<span style="margin-right: 10px">
							<?php echo $cls['anno_corso'].$cls['sezione'] ?>
							<input type="checkbox" name="classi[]" id="cl_<?php echo $cls['id_classe'] ?>" value="<?php echo $cls['id_classe'] ?>" <?php echo $selected ?> />
						</span>
						<?php
						}
						?>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
					<input type="hidden" name="docID" id="docID" value="<?php if(isset($subs)) echo $subs->getLecturer()->getUid() ?>" />
					<input type="hidden" name="supID" id="supID" value="<?php if(isset($subs)) echo $subs->getSubstitute()->getUid() ?>" />
					<input type="hidden" name="action" id="action" value="<?php echo $action ?>" />
					<input type="hidden" name="id" id="id" value="<?php echo $_REQUEST['id'] ?>" />
				</tr>
				<tr>
					<td colspan="2" style="text-align: right; margin-right: 50px">

					</td>
				</tr>
			</table>
			<div style="margin: 20px auto 0 auto; text-align: right; width: 85%">
				<?php
				if ($_REQUEST['id'] != 0):
					?>
					<a href="#" onclick="del()" class="material_link nav_link_first">Elimina</a>|
				<?php
				endif;
				?>
				<a href="#" onclick="register()" class="material_link nav_link_last">Registra</a>
			</div>
		</form>
	</div>
	<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
		<?php if ($_SESSION['__role__'] == "Dirigente scolastico"): ?>
			<div class="drawer_link"><a href="utility.php"><img src="../../images/59.png" style="margin-right: 10px; position: relative; top: 5%" />Utility</a></div>
		<?php endif; ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
