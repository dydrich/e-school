<!DOCTYPE html>
<html>
<head>
<title>Registro di classe</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../registro_classe/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../registro_classe/reg_print.css" type="text/css" media="print" />
<link rel="stylesheet" href="../../../modules/documents/theme/style.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../modules/documents/theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript">
$(function(){
	$('#accordion').accordion({
		heightStyle: "content"
	});
});

var upd = function(elem){
	if(elem.value == -1){
		return false;
	}
	dati = elem.id.split("_");

	$.ajax({
			type: "POST",
			url: "upd_param.php",
			data: {alunno: dati[1], param: dati[2], val: elem.value, q: <?php echo $_REQUEST['q'] ?>},
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

				}
			}
	    });
};
</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
	<div class="page_title">Giudizi sul livelo globale di maturazione - <?php echo $_REQUEST['q'] ?> quadrimestre</div>
	<div id="accordion">
<?php 
while ($row = $res_alunni->fetch_assoc()){
	$sel_vals = "SELECT * FROM rb_valutazione_parametri_pagella WHERE studente = {$row['id_alunno']} AND anno = {$anno} AND quadrimestre = {$_REQUEST['q']}";
	$res_vals = $db->executeQuery($sel_vals);
	$vals = array();
	while ($r = $res_vals->fetch_assoc()){
		$vals[$r['parametro']] = $r['giudizio'];
	}	
	$res_param->data_seek(0);
?>
		<h3><?php echo  $row['cognome']." ".$row['nome'] ?></h3>
		<div style="padding-bottom: 40px">
<?php
	while ($param = $res_param->fetch_assoc()){
		reset($giudizi);
?>		
		<div style="height: 25px">
			<p style="width: 200px; display: inline-block"><?php echo $param['nome'] ?></p>
			<p style="width: 400px; display: inline-block">
				<select id="param_<?php echo $row['id_alunno'] ?>_<?php echo $param['id'] ?>" onchange="upd(this)" class="sel" style="width: 350px; border: 1px solid #CCC" name="param_<?php echo $row['id_alunno'] ?>_<?php echo $param['id'] ?>">
					<option value="-1">.</option>
		<?php
		foreach ($giudizi[$param['id']] as $k => $g){
		?>
					<option value="<?php echo $k ?>" <?php if (isset($vals[$param['id']]) && $vals[$param['id']] == $k) echo "selected" ?>><?php echo utf8_encode($g) ?></option>
		<?php 
		}
		?>
				</select>
			</p>
		</div>
<?php 
	}
?>
	</div>
<?php 
}
?>
	
	</div>
</div>
<?php include "../footer.php" ?>
</body>
</html>
