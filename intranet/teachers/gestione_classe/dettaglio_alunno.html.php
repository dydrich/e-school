<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Dettaglio alunno</title>
<link rel="stylesheet" href="../../../css/site_themes/blue_red/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/jquery/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">
$(function(){
	$('#birth').datepicker({
		dateFormat: "dd/mm/yy",
		changeYear: true,
		yearRange: "1999:<?php echo date("Y") ?>"
	});
	$('.profile_print').click(function(event){
		event.preventDefault();
		print_profile();
	});
	$('.phone_buttons').css({"display": "none"});
	$('#pers_button').button();
	$('#pers_button').click(function(event){
		event.preventDefault();
		save('pers');
	});
	$('#addr_button').button();
	$('#addr_button').click(function(event){
		event.preventDefault();
		save('addr');
	});
	$('.phone_number').mouseover(function(event){
		var i = this.id.split("_")[1];
		$('#phb'+i).show();
	});
	$('.phone_number').mouseout(function(event){
		var i = this.id.split("_")[1];
		$('#phb'+i).hide();
	});
});

var indirizzi = new Array();
<?php 
$i = 0;
if (count($tel) > 0){
	reset($t);
	foreach ($t as $row){
?>
indirizzi[<?php echo $i ?>] = '<?php echo $row ?>';
<?php 
		$i++;
	}
}
?>

var save = function(area){
	$('#area').val(area);
	$.ajax({
		type: "POST",
		url: "aggiorna_alunno.php",
		data: $('#testform').serialize(true),
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
				$('#not'+area).text(json.message);
				$('#not'+area).show(1000);
				window.setTimeout(function(){$('#not'+area).hide(1000);}, 2000);
			}
		}
    });
};

var print_profile = function(){
	document.location.href = "pdf_dettaglio_alunno.php?stid="+stid;
};
</script>
</head> 
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "class_working.php" ?>
</div>
<div id="left_col">
<div class="group_head">
	Dettaglio alunno <?php echo $alunno['cognome']." ".$alunno['nome'] ?>
</div>
<form id="testform" method="post">
<input type="hidden" id="area" name="area" />
<input type="hidden" id="stid" name="stid" value="<?php echo $_REQUEST['stid'] ?>" />
<fieldset class="wd_90 _elem_center">
<legend>Dati anagrafici</legend>
<div class="wd_90 notification" id="notpers"></div>
<div class="wd_90 _elem_center">
	<div class="wd_35 fleft">Cognome</div>
	<div class="wd_60 fleft row"><input type="text" id="lname" name="lname" class="wd_95" value="<?php echo $alunno['cognome'] ?>" /></div>
	<div class="wd_35 fleft">Nome</div>
	<div class="wd_60 fleft row"><input type="text" id="fname" name="fname" class="wd_95" value="<?php echo $alunno['nome'] ?>" /></div>
	<div class="wd_35 fleft">Data di nascita</div>
	<div class="wd_60 fleft row"><input type="text" id="birth" name="birth" class="wd_95" value="<?php echo format_date($alunno['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>" /></div>
	<div class="wd_35 fleft">Luogo di nascita</div>
	<div class="wd_60 fleft"><input type="text" id="city" name="city" class="wd_95" value="<?php echo $alunno['luogo_nascita'] ?>" /></div>
	<p class="bclear"></p>
	<div class="wd_95 _right" id="reg_button">
		<button id="pers_button" class="standard_link">Registra</button>
	</div>
</div>
</fieldset>
<fieldset class="wd_90 _elem_center">
<legend>Domicilio</legend>
<div class="wd_90 notification" id="notaddr"></div>
<div class="wd_90 _elem_center">
	<div class="wd_35 fleft">Indirizzo</div>
	<div class="wd_60 fleft row"><input type="text" id="address" name="address" class="wd_95" value="<?php echo $alunno['indirizzo'] ?>" /></div>
	<div class="wd_35 fleft">Citt&agrave;</div>
	<div class="wd_60 fleft row"><input type="text" id="residence" name="residence" class="wd_95" value="<?php echo $alunno['citta'] ?>" /></div>
	<p class="bclear"></p>
	<div class="wd_95 _right" id="reg_button">
		<button id="addr_button" class="standard_link">Registra</button>
	</div>
</div>
</fieldset>
<fieldset class="wd_90 _elem_center" style="display: none">
<legend>Telefono</legend>
<div class="wd_90 _elem_center">
	<div class="wd_90" id="container">
	<?php 
	if (count($tel) == 0){
	?>
	Non &egrave; presente alcun recapito telefonico;
	<?php 
	}
	else{
		$i = 0;
		foreach ($tel as $numbr){
	?>
	<div id="row_<?php echo $i ?>">
	<div class="wd_10 fleft">Numero</div>
	<div class="wd_30 fleft"><input type="text" id="number_<?php echo $i ?>" name="number_<?php echo $i ?>" class="wd_95" value="<?php echo $numbr['number'] ?>" /></div>
	<div class="wd_10 fleft"> </div>
	<div class="wd_15 fleft _center">Descrizione</div>
	<div class="wd_30 fleft"><input type="text" id="desc_<?php echo $i ?>" name="desc_<?php echo $i ?>" class="wd_95" value="<?php echo $numbr['desc'] ?>" /></div>
	<a href="#" id="save_<?php echo $i ?>" class="del" style="margin: 0 10px 0 10px" title="Salva"><img src="../../../images/1.png" /></a>
	<a href="#" id="del_<?php echo $i ?>" class="del" title="Cancella"><img src="../../../images/51.png" /></a>
	</div>
	
	<p class="bclear"></p>
	<?php
			$i++;
		}
	}
	?>
	<p class="bclear">&nbsp;</p>
	<div>
	<div class="wd_10 fleft">Numero</div>
	<div class="wd_30 fleft"><input type="text" id="number" name="number" class="wd_95" /></div>
	<div class="wd_10 fleft"> </div>
	<div class="wd_15 fleft _center">Descrizione</div>
	<div class="wd_30 fleft"><input type="text" id="desc" name="desc" class="wd_95" /></div>
	<div>
		<a href="#" id="add_phone" class="standard_link">Aggiungi</a>
	</div>
	<p class="bclear"></p>
	</div>
	</div>
</div>
</fieldset>
</form>
</div> 
<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
</body>
</html>
