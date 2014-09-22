<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../js/page.js"></script>
	<script type="text/javascript">
	var year = <?php echo $y ?>;
	var go = function(){
		//alert($('vacanze').value);
		var url = "school_year_manager.php?action=<?php echo $action ?>";

		$.ajax({
			type: "POST",
			url: url,
			data: $('#myform').serialize(true),
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
					j_alert("alert", "Anno scolastico creato o modificato con successo");
				}
			}
		});
	};

	var load_default = function(load_on_update){
		if(load_on_update){
			<?php if ($action != "new"): ?>
			$('#data_inizio').val('<?php print format_date($year->get_data_apertura(), SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>');
			$('#data_fine').val('<?php print format_date($year->get_data_chiusura(), SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>');
			<?php endif; ?>
		}
		else{
			$('#data_inizio').val('01/09/'+year);
			$('#data_fine').val('31/08/'+(year+1));
		}
	};

	$(function(){
		load_jalert();
		load_default(true);
		$('#data_inizio').datepicker({
			dateFormat: "dd/mm/yy"
		});
		$('#data_fine').datepicker({
			dateFormat: "dd/mm/yy"
		});
	})


	</script>
<title>Gestione anno scolastico</title>
<style>
input {
	font-size: 11px
}
</style>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "adm_school/menu.php" ?>
	</div>
	<div id="left_col">
	<div class="group_head">Gestione anni scolastici</div>
	<form method="post" id="myform" class="popup_form">
		<table style="width: 90%; margin-right: auto; margin-left: auto; margin-bottom: 20px;">
            <tr>
            	<td colspan="2">&nbsp;</td>
            </tr>
           	<tr>
                <td style="width: 40%; font-weight: normal" class="">Data inizio anno scolastico</td>
                <td style="width: 60%; color: #003366"><input type="text" name="data_inizio" id="data_inizio" style="width: 100%" readonly="readonly" /></td>
	        </tr>
			<tr>
                <td style="width: 40%; padding-left: 10px; font-weight: normal" class="">Data fine anno scolastico</td>
                <td style="width: 60%; color: #003366"><input type="text" name="data_fine" id="data_fine" style="width: 100%" readonly="readonly" /></td>
            </tr>
            <tr>
            	<td colspan="2">
            	</td>
            </tr>
			<tr>
                <td style="padding-top: 20px; text-align: right" colspan="2">
                	<a href="#" onclick="go()" class="standard_link nav_link">Registra</a>
                </td>
			</tr>
		</table>
		</form>
		<p class="spacer"></p>
	</div>
</div>
<?php include "footer.php" ?>
</body>
</html>
