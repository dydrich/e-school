<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Obiettivi didattici per voto</title>
<link rel="stylesheet" href="../../../css/site_themes/blue_red/reg_classe.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../modules/communication/theme/style.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../modules/communication/theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">

var save_grade = function(obj, grade){
	gradeID = <?php echo $_REQUEST['idv'] ?>;
	$.ajax({
		type: "POST",
		url: "grade_manager.php",
		data:  {goal: obj, grade: grade, gradeID: gradeID, action: "update_los"},
		dataType: 'json',
		error: function(data, status, errore) {
			alert("Si e' verificato un errore");
			return false;
		},
		succes: function(result) {
			alert("ok");
		},
		complete: function(data, status){
			r = data.responseText;
			var json = $.parseJSON(r);
			if(json.status == "kosql"){
				alert("Errore SQL. \nQuery: "+json.query+"\nErrore: "+json.message);
				return;
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
	$('.obj_grade').change(function(event){
		//alert(this.id);
		event.preventDefault();
		var strs = this.id.split("_");
		save_grade(strs[1], this.value);
	});
});
</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<form>
<table class="registro">
<thead>
<tr class="head_tr">
	<td colspan="2" style="text-align: center; font-weight: bold"><?php print $_SESSION['__current_year__']->to_string() ?>::classe <?php print $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?> 
		<span style="float: right; padding-right: 10px" ></span>
	</td>
</tr>
<tr class="head_tr_no_bg">
	<td style="width: 35%; text-align: center; border-right: 0"><span id="ingresso" style="font-weight: bold; "><?php print $alunno['cognome']." ".$alunno['nome'] ?></span></td> 
	<td style="width: 65%; text-align: center; border-left: 0"><span id="media" style="font-weight: bold; "><?php echo strtoupper($desc_materia) ?>, <?php echo $q ?> quadrimestre</span>
	</td>
</tr>
<tr style="">
	<td style="width: 35%; text-align: left">
	<fieldset style="width: 90%; margin: 20px auto 20px auto; border-radius: 8px">
		<legend style="font-weight: bold">Dettaglio voto</legend>
		<table style="width: 90%; margin: auto">
			<tr>
				<td style="width: 50%; font-weight: bold; text-align: left; border: 0">Voto</td>
				<td style="width: 50%; text-align: center; border: 0"><?php echo $grade->getGrade() ?></td>
			</tr>
			<tr>
				<td style="width: 50%; font-weight: bold; text-align: left; border: 0">Data</td>
				<td style="width: 50%; text-align: center; border: 0"><?php echo format_date($grade->getGradeDate(), SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></td>
			</tr>
			<tr>
				<td style="width: 50%; font-weight: bold; text-align: left; border: 0">Tipo</td>
				<td style="width: 50%; text-align: center; border: 0"><?php echo $prove[$grade->getType()] ?></td>
			</tr>
			<tr>
				<td style="width: 50%; font-weight: bold; text-align: left; border: 0">Prova</td>
				<td style="width: 50%; text-align: center; border: 0"><?php echo utf8_decode($grade->getDescription()) ?></td>
			</tr>
			<tr>
				<td style="width: 50%; font-weight: bold; text-align: left; border: 0">Argomento</td>
				<td style="width: 50%; text-align: center; border: 0"><?php echo utf8_decode($grade->getTopic()) ?></td>
			</tr>
			<tr>
				<td style="width: 50%; font-weight: bold; text-align: left; border: 0">Note</td>
				<td style="width: 50%; text-align: center; border: 0"><?php echo utf8_decode($grade->getNote()) ?></td>
			</tr>				
		</table>
	</fieldset>
	</td> 
	<td style="width: 65%; text-align: left">
		<table style="width: 90%; margin: auto; border: 0; border-collapse: collapse">
			<tr style="border: 0; height: 35px">
				<td style="width: 70%; font-weight: bold; border-width: 0 0 1px 0">Obiettivo</td>
				<td style="width: 30%; font-weight: bold; border-width: 0 0 1px 0; text-align: right">Valutazione</td>
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
						<select name='voto_<?php echo $row['id'] ?>' id='voto_<?php echo $row['id'] ?>' class="obj_grade" style='font-size: 11px; width: 97px'>			
							<option value='0'>Seleziona</option>	
							<?php
							if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
								foreach ($voti_religione as $k => $g){
							?>
							<option value='<?php echo $k ?>' <?php if ($row['grade'] == $k) echo "selected" ?>><?php echo $g ?></option>
							<?php
								}
							}
							else {
								$i = 100;
								echo $row['grade'];
								while($i > 9){		
							?>			
							<option value='<?php print ($i / 10) ?>' <?php if ($row['grade'] == ($i / 10)) echo "selected" ?>><?php print ($i / 10) ?></option>		
							<?php 			
									$i -= 5;
								}								
							}		
							?>		
						</select>
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
						<select name='voto_<?php echo $child['id'] ?>' id='voto_<?php echo $child['id'] ?>' class="obj_grade" style='font-size: 11px; width: 97px'>			
							<option value='0'>Seleziona</option>	
							<?php
							if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
								foreach ($voti_religione as $k => $g){
							?>
							<option value='<?php echo $k ?>' <?php if ($row['grade'] == $k) echo "selected" ?>><?php echo $g ?></option>
							<?php
								}
							}
							else {
								$i = 100;		
								while($i > 9){		
							?>			
							<option value='<?php print ($i / 10) ?>' <?php if ($child['grade'] == ($i / 10)) echo "selected" ?>><?php print ($i / 10) ?></option>		
							<?php 			
									$i -= 5;
								}								
							}		
							?>		
						</select>
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
		<td colspan="2" style="text-align: right"><a href="student.php?stid=<?php echo $student_id ?>&q=<?php echo $q ?>" class="standard_link" style="margin-right: 20px">Torna ai voti</a></td>
	</tr>
</tfoot>
</table>
</form>
</div>
<?php include "../footer.php" ?>
<div id="not1"></div>
</body>
</html>
