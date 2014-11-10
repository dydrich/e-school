<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
	var downloadLog = function(cls, cls_desc){
		file = "registro_<?php echo $_SESSION['__current_year__']->get_ID() ?>_"+cls_desc;
		//document.location.href = "../../lib/download_manager.php?dw_type=classbook&f="+file;
		document.location.href = "../../modules/documents/download_manager.php?doc=classbook&area=manager&f="+file;
	};

	var createLog = function(cls){
		$.ajax({
			type: "POST",
			url: '../teachers/registro_classe/print_classbook.php',
			data: {cls: cls},
			dataType: 'json',
			error: function() {
				alert("Errore di trasmissione dei dati");
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
				else {
					j_alert ("alert", "Registro creato correttamente");
				}
			}
		});
	};

	$(function(){
		load_jalert();
		setOverlayEvent();
		$('a.clog').click(function(event){
			//alert(this.id);
			event.preventDefault();
			var strs = this.id.split("_");
			createLog(strs[1]);
		});
		$('a.dlog').click(function(event){
			//alert(this.id);
			event.preventDefault();
			var strs = this.id.split("_");
			downloadLog(strs[1], strs[2]);
		});
	});
	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include $_SESSION['__administration_group__']."/navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include $_SESSION['__administration_group__']."/menu.php" ?>
</div>
<div id="left_col">
	<div class="welcome" style="margin-top: 0; padding-top: 10px">
		<p id="w_head">Gestione registri di classe </p>
		<table style="width: 350px">
		<?php 
		while($cls = $res_classi->fetch_assoc()){
		?>
			<tr style="height: 25px" class="bottom_decoration">
				<td style="width: 50px"><?php echo $cls['anno_corso'].$cls['sezione'] ?></td>
				<td style="width: 150px"><a href="../../shared/no_js.php" class="clog" id="createLog_<?php echo $cls['id_classe'] ?>_<?php echo $cls['anno_corso'].$cls['sezione'] ?>">Crea il registro</a></td>
				<td style="width: 150px"><a href="../../shared/no_js.php" class="dlog" id="downloadLog_<?php echo $cls['id_classe'] ?>_<?php echo $cls['anno_corso'].$cls['sezione'] ?>">Scarica il registro</a></td>
		<?php  
		}
		?>
		</table>	
	</div>
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
