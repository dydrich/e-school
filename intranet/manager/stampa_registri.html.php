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
	<div class="group_head">
		Area amministrazione e segreteria::<?php echo $_SESSION['__school_level__'][$_SESSION['__school_order__']] ?>
	</div>
	<div class="welcome">
		<p id="w_head">Gestione registri di classe </p>
		<table style="width: 350px">
		<?php 
		while($cls = $res_classi->fetch_assoc()){
		?>
			<tr style="height: 25px">
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
</body>
</html>
