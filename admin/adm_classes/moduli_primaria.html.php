<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Classi</title>
	<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link href="../../css/general.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var dr_cls = 0;
		var mod_count = <?php echo count($moduli) ?>;

		var del_class = function(module){
			var url = "class_manager.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {action: "del_class_from_module", idm: module, idc: dr_cls},
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
				}
			});
		};

		var add_class = function(module){
			var url = "class_manager.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {action: "add_class_to_module", idm: module, idc: dr_cls},
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
				}
			});
		};

		var add_module = function() {
			var url = "class_manager.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {action: "insert_module"},
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
					else {
						mod_count++;
						mod = document.createElement("div");
						mod.setAttribute("id", "mod_"+json.id_modulo);
						$('#drop_container').append(mod);
						$('#mod_'+json.id_modulo).addClass("droppable_module");
						$('#mod_'+json.id_modulo).text("Modulo "+mod_count);
						$('#mod_'+json.id_modulo).droppable({
							tolerance: "fit",
							drop: function( event) {
								console.log("Received "+dr_cls);
							},
							out: function( event) {
								console.log("lost "+dr_cls);
							}
						});
					}
				}
			});
		};

		$(function(){
			load_jalert();
			$('#button').button();
			$('#button').click(function(){
				add_module();
			});
			$('.draggable_class').draggable({
				start: function( event) {
					var strs = this.id.split("_");
					dr_cls = strs[1];
				},
				stop: function( event) {
					console.log(dr_cls);
				}
			});
			$('.droppable_module').droppable({
				tolerance: "fit",
				drop: function( event) {
					var idmod = this.id.split("_")[1];
					add_class(idmod);
					console.log("Received "+dr_cls+ " for module "+idmod);
				},
				out: function( event) {
					var idmod = this.id.split("_")[1];
					del_class(idmod);
					console.log("Deleted "+dr_cls+ " from module "+idmod);
				}
			});

		});

	</script>
	<style>
		.draggable_class {
			width: 50px;
			height: 18px;
			background-color:#F69988;
			border: 1px solid #DB5355;
			border-radius: 10%;
			float: left;
			margin-right: 10px;
			margin-bottom: 10px;
			text-align: center;
			font-weight: bold;
		}

		.droppable_module {
			width: 70px;
			height: 100px;
			background-color: rgba(30, 67, 137, .1);
			border: 1px solid rgba(30, 67, 137, .8);
			border-radius: 10%;
			float: left;
			margin-right: 10px;
			margin-bottom: 10px;
			text-align: center;
			font-weight: bold;
		}
	</style>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Gestione moduli scuola primaria</div>
		<div id="start_container" style="width: 95%; margin: auto; ">
			<?php
			reset ($classi);
			foreach ($classi as $k => $classe) {
				if (!in_array($k, $classi_associate)) {
			?>
			<div id="cls_<?php echo $k ?>" class="draggable_class"><?php echo $classe ?></div>
			<?php
				}
			}
			?>
		</div>
		<div id="drop_container" style="clear: left; width: 95%; margin: 35px auto; padding-top: 40px">
			<?php
			if (count($moduli) == 0) {
			?>
			<p style="font-weight: bold; text-align: center; width: 100%">Nessun modulo presente</p>
			<?php
			}
			else {
				$x = 1;
				foreach ($moduli as $i => $mod) {
			?>
			<div id="mod_<?php echo $i ?>" class="droppable_module">Modulo <?php echo $x ?>
			<?php
					if (count($mod) > 0) {
						foreach ($mod as $cl) {
			?>
				<div id="cls_<?php echo $cl ?>" class="draggable_class" style="margin: auto; float: none"><?php echo $classi[$cl] ?></div>
			<?php
						}
					}
					$x++;
			?>
			</div>
			<?php
				}
			}
			?>
		</div>
		<div style="clear: left; width: 100%; text-align: right; margin: 0 0 5% 0">
			<button id="button" style="margin: 10px 20px 0 0">Aggiungi modulo</button>
		</div>
	</div>
</div>
<?php include "../footer.php" ?>
</body>
</html>
