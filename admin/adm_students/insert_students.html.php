<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Inserimento alunni</title>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var save = function(_continue){

			$.ajax({
				type: "POST",
				url: 'manage_student.php',
				data: $('#_form').serialize(true),
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
						$('#fname').val("");
						$('#lname').val("");
						$('#sex').val(0);
						$('#cls').val(0);
						$('#fname').focus();
						if(!_continue){
							document.location.href = "../../shared/get_file.php?f=<?php echo $log_file ?>&dir=tmp&delete=1";
							//document.location.href = "alunni.php?school_order=<?php echo $school_order ?>";
						}
					}
				}
			});
		};

		$(function(){
			load_jalert();
			$('#data_nascita').datepicker({
				dateFormat: "dd/mm/yy",
				changeYear: true,
				changeMonth: true
			});
		});
	</script>

	<style>
		.ui-datepicker-month {
			color: white
		}
		.ui-datepicker-year {
			color: white
		}
	</style>
</head>
<body onload="$('fname').focus()">
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "../new_year_menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Inserimento nuovi alunni</div>
	<form method="post" id="_form" action="update_student.php" class="popup_form" style="width: 90%">
	<fieldset style="width: 95%; border: 1px solid #BBBBBB; padding-top: 10px; margin: 20px auto 20px auto;">
	<legend style="border: 1px solid #CCCCCC; border-radius: 5px; padding: 0 10px 0 10px">Dati alunno</legend>
	<table style="width: 80%; margin: auto">
        <thead>
            
            </thead>
	<tbody>
		<tr class="popup_row">
			<td class="popup_title" style="width: 30%">Nome</td>
			<td style="width: 70%">
				<input type="text" name="fname" id="fname" style="width: 320px" class="form_input" />
			</td>
		</tr>
		<tr class="popup_row">
			<td class="popup_title" style="width: 30%">Cognome</td>
			<td style="width: 70%">
				<input type="text" name="lname" id="lname" style="width: 320px;" class="form_input" />
			</td>
		</tr>
		<tr class="popup_row">
			<td class="popup_title" style="width: 30%">Sesso</td>
			<td style="width: 70%">
				<select name="sex" id="sex" style="width: 320px" class="form_input">
					<option value="all">.</option>
					<option value="F">Femmina</option>
					<option value="M">Maschio</option>
				</select>
			</td>
		</tr>
		<tr class="popup_row">
			<td class="popup_title" style="width: 30%">Data di nascita</td>
			<td style="width: 70%">
				<input type="text" name="data_nascita" id="data_nascita" style="width: 320px;" class="form_input" />
			</td>
		</tr>
		<tr class="popup_row">
			<td class="popup_title" style="width: 30%">Luogo di nascita</td>
			<td style="width: 70%">
				<input type="text" name="luogo_nascita" id="luogo_nascita" style="width: 320px;" class="form_input" />
			</td>
		</tr>
		<tr class="popup_row">
			<td class="popup_title" style="width: 30%">Classe</td>
			<td style="width: 70%">
				<select name="cls" id="cls" style="width: 320px" class="form_input">
					<option value="all">.</option>
					<?php while($row = $res_classes->fetch_assoc()){ ?>
					<option value="<?php echo $row['id_classe'] ?>"><?php echo $row['anno_corso'],$row['sezione']," ",$row['nome'] ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: right; padding-top: 10px; padding-bottom: 20px">
				<input type="hidden" id="school_order" name="school_order" value="<?php echo $school_order ?>" />
			</td>
		</tr>
	</tbody>
	</table>
	</fieldset>
	<div style="width: 95%; text-align: right; margin: 15px auto 25px auto">
		<a href="#" onclick="save(false)" class="standard_link nav_link_first">Salva e concludi</a>|
    	<a href="#" onclick="save(true)" class="standard_link nav_link_last">Salva e continua</a>
    </div>
	</form>	             
    </div>
    <p class="spacer"></p>
	</div>
<?php include "../footer.php" ?>
</body>
</html>
