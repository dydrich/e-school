<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Obiettivi didattici verifica</title>
<link rel="stylesheet" href="../registro_classe/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../modules/communication/theme/style.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../modules/communication/theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">
var save_data = function(){
	$.ajax({
		type: "POST",
		url: 'test_manager.php',
		data: $('#myform').serialize(true),
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
				show_error(json.message);
				console.log(json.dbg_message);
			}
			else {
				$('#not1').text(json.message);
				$('#not1').dialog({
					autoOpen: false,
					show: {
						effect: "appear",
						duration: 500
					},
					hide: {
						effect: "slide",
						duration: 300
					},
					buttons: [{
						text: "Chiudi",
						click: function() { 
							$( '#not1' ).dialog( "close" ); 
						}
					}],
					modal: true,
					width: 450,
					open: function(event, ui){
						
					}
				});
				$('#not1').dialog('open');
			}
		}
    });
};

$(function(){
	$('#save_button').click(function(event){
		event.preventDefault();
		save_data();
	});
});
</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<form id="myform">
<table class="registro">
<thead>
<tr class="head_tr">
	<td colspan="2" style="text-align: center; font-weight: bold"><?php print $_SESSION['__current_year__']->to_string() ?>::classe <?php print $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?> 
		<span style="float: right; padding-right: 10px" ></span>
	</td>
</tr>
<tr class="head_tr_no_bg">
	<td style="width: 45%; text-align: center; border-right: 0"><span id="ingresso" style="font-weight: bold; ">Obiettivi verifica</span></td> 
	<td style="width: 55%; text-align: center; border-left: 0"><span id="media" style="font-weight: bold; "><?php echo strtoupper($test->getSubject()->getDescription()) ?>, <?php echo $q ?> quadrimestre</span>
	<div id="not1"></div>
	</td>
</tr>
<tr style="">
	<td style="width: 45%; text-align: left">
	<fieldset style="width: 90%; margin: 20px auto 20px auto; border-radius: 8px">
		<legend style="font-weight: bold">Dettaglio verifica</legend>
		<table style="width: 90%; margin: auto">
			<tr>
				<td style="width: 50%; font-weight: bold; text-align: left; border: 0">Data</td>
				<td style="width: 50%; text-align: center; border: 0"><?php echo format_date(substr($test->getTestDate(), 0, 10), SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></td>
			</tr>
			<tr>
				<td style="width: 50%; font-weight: bold; text-align: left; border: 0">Tipo</td>
				<td style="width: 50%; text-align: center; border: 0"><?php echo $prove[$test->getType()] ?></td>
			</tr>
			<tr>
				<td style="width: 50%; font-weight: bold; text-align: left; border: 0">Argomento</td>
				<td style="width: 50%; text-align: center; border: 0"><?php echo $test->getTopic() ?></td>
			</tr>
			<tr>
				<td style="width: 50%; font-weight: bold; text-align: left; border: 0">Note</td>
				<td style="width: 50%; text-align: center; border: 0"><?php echo $test->getAnnotation() ?></td>
			</tr>				
		</table>
	</fieldset>
	</td> 
	<td style="width: 55%; text-align: left">
		<table style="width: 90%; margin: auto; border: 0; border-collapse: collapse">
			<tr style="border: 0; height: 35px">
				<td style="width: 80%; font-weight: bold; border-width: 0 0 1px 0">Obiettivo</td>
				<td style="width: 80%; font-weight: bold; border-width: 0 0 1px 0; text-align: right"></td>
			</tr>
			<?php 
			foreach ($goals as $row){
				$color = "";
				if (isset($row['idpadre']) && $row['idpadre'] == ""){
					$color = "font-weight: bold";
				}
				?>
					<tr style="border: 0">
						<td style="width: 70%; border-width: 0 0 1px 0; <?php echo $color ?>"><?php echo $row['nome'] ?></td>
						<td style="width: 30%; border-width: 0 0 1px 0; text-align: right">
						<input type="checkbox" id="goal_<?php echo $row['id'] ?>" name="goals[]" value="<?php echo $row['id'] ?>" <?php if (in_array($row['id'], $test->getLearningObjectives())) echo "checked" ?> />
						</td>
					</tr>
			<?php
				if (isset($row['children'])){
					foreach ($row['children'] as $child){
						$color = "";
			?>
					<tr style="border: 0">
						<td style="width: 70%; border-width: 0 0 1px 0; <?php echo $color ?>"><?php echo $child['nome'] ?></td>
						<td style="width: 30%; border-width: 0 0 1px 0; text-align: right">
						<input type="checkbox" id="goal_<?php echo $child['id'] ?>" name="goals[]" value="<?php echo $child['id'] ?>" <?php if (in_array($row['id'], $test->getLearningObjectives())) echo "checked" ?> />
						</td>
					</tr>
			<?php 
					}
				}
			}
			?>
		</table>
	</td>
</tr>
</thead>
<tfoot>
	<tr style="height: 30px">
		<td colspan="2" style="text-align: right">
			<a href="test.php?idt=<?php echo $_REQUEST['idv'] ?>" style="text-transform: uppercase; text-decoration: none; margin-right: 10px">Torna alla verifica</a>|
			<a href="#" id="save_button" style="text-transform: uppercase; text-decoration: none; margin-left: 10px; margin-right: 20px">Salva</a>
		</td>
	</tr>
</tfoot>
</table>
<input type="hidden" id="id_verifica" name="id_verifica" value="<?php echo $_REQUEST['idv'] ?>" />
<input type="hidden" id="do" name="do" value="save_los" />
</form>
</div>
<?php include "../footer.php" ?>
</body>
</html>
