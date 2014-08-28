<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../../css/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../modules/communication/theme/style.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../js/jquery_themes/custom-theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
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
		<div class="group_head">
			<?php echo $label ?> supplenza
		</div>
		<form id="my_form" method="post" action="" style="border: 1px solid #666666; border-radius: 10px; margin-top: 30px; text-align: left; width: 90%; margin-left: auto; margin-right: auto">
			<table style="width: 90%; margin: 30px auto 20px auto">
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
		</form>
		<div style="margin: 20px auto 0 auto; text-align: right; width: 90%">
			<?php
			if ($_REQUEST['id'] != 0):
			?>
				<a href="#" onclick="del()" class="standard_link nav_link_first">Elimina</a>|
			<?php
			endif;
			?>
			<a href="#" onclick="register()" class="standard_link nav_link_last">Registra</a>
		</div>
	</div>
	<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
