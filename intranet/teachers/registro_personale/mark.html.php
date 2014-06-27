<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Registro di classe: gestione valutazioni</title>
<link rel="stylesheet" href="../registro_classe/reg_classe_popup.css" type="text/css" media="screen,projection" />
<link href="../../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="../../../css/skins/aqua/theme.css" type="text/css" />
<script type="text/javascript" src="../../../js/prototype.js"></script>
<script type="text/javascript" src="../../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript" src="../../../js/window.js"></script>
<script type="text/javascript" src="../../../js/window_effects.js"></script>
<script type="text/javascript" src="../../../js/calendar.js"></script>
<script type="text/javascript" src="../../../js/lang/calendar-it.js"></script>
<script type="text/javascript" src="../../../js/calendar-setup.js"></script>
<script type="text/javascript">
var voto = <?php print (isset($voto) ? $voto['voto'] : 0) ?>;
voto = voto * 10;
idx = 0;
mat = <?php echo $_SESSION['__materia__'] ?>;

function set_index(){
<?php
if ($_SESSION['__materia__'] != 26 && $_SESSION['__materia__'] != 30){
?>
	switch(voto){
		case 100:
			idx = 1;
			break;
		case 95:
			idx = 2;
			break;
		case 90:
			idx = 3;
			break;
		case 85:
			idx = 4;
			break;
		case 80:
			idx = 5;
			break;
		case 75:
			idx = 6;
			break;
		case 70: 
			idx = 7;
			break;
		case 65:
			idx = 8;
			break;
		case 60:
			idx = 9;
			break;
		case 55:
			idx = 10;
			break;
		case 50:
			idx = 11;
			break;
		case 45:
			idx = 12;
			break;
		case 40:
			idx = 13;
			break;
		case 35:
			idx = 14;
			break;
		case 30:
			idx = 15;
			break;
		case 25:
			idx = 16;
			break;
		case 20:
			idx = 17;
			break;
		case 15:
			idx = 18;
			break;
		case 10:
			idx = 19;
			break;
	}
<?php 
}
else{
?>
switch(voto){
case 100:
	idx = 5;
	break;
case 90:
	idx = 4;
	break;
case 80:
	idx = 3;
	break;
case 60:
	idx = 2;
	break;
case 40:
	idx = 1;
	break;
}
<?php 
}
?>
	$('myform').voto.selectedIndex = idx;
	<?php if(isset($voto)){ ?>
	$('del_button').style.display = "";
	<?php } ?>
}

function check_form(frm){
	var ind = 0;
	var msg = "Il modulo non e' stato compilato correttamente. Sono stati riscontrati i seguenti errori:\n";
	var bool = true;
	if(frm.voto.value == "0"){
		ind++;
		msg += "\n"+ind+". Voto non inserito";
		document.getElementById("lab1").style.color = "#ff0000";
		bool = false;
	}
	else
		document.getElementById("lab1").style.color = "inherit";
	if(frm.data_voto.value == ""){
		ind++;
		msg += "\n"+ind+". Data non inserita";
		document.getElementById("lab3").style.color = "#ff0000";
		bool = false;
	}
	else if(!valida_data(frm.data_voto.value)){
		ind++;
		msg += "\n"+ind+". Data non corretta";
		document.getElementById("lab3").style.color = "#ff0000";
		frm.data_voto.value = "";
		bool = false;
	}
	else
		document.getElementById("lab3").style.color = "inherit";
	if(frm.tipo.value == "0"){
		ind++;
		msg += "\n"+ind+". Tipologia di voto non inserita";
		document.getElementById("lab4").style.color = "#ff0000";
		bool = false;
	}
	else
		document.getElementById("lab4").style.color = "inherit";
	if(trim(frm.descrizione.value) == ""){
		ind++;
		msg += "\n"+ind+". Descrizione della prova non inserita";
		document.getElementById("lab5").style.color = "#ff0000";
		bool = false;
	}
	else
		document.getElementById("lab5").style.color = "inherit";
	if(trim(frm.argomento.value) == ""){
		ind++;
		msg += "\n"+ind+". Argomento della prova non inserito";
		document.getElementById("lab6").style.color = "#ff0000";
		bool = false;
	}
	else
		document.getElementById("lab6").style.color = "inherit";
	if(!bool)
		alert(msg);
	return bool;
}

function del_mark(){
	if(confirm("Sei sicuro di voler cancellare questo voto?")){
		//alert($('myform').alunno.value);
		//alert("mark_management.php?do=delete&id_voto="+$('myform').myid.value+"&ia="+$('myform').ia.value);
		document.location.href = "mark_management.php?do=delete&id_voto="+$('myform').myid.value+"&ia="+$('myform').alunno.value+"&q=<?php print $q ?>";
	}
}

</script>
<style>
* {font-size: 11px}
</style>
</head>
<body onload="set_index()">
<div id="main">
<p style='text-align: center; padding-top: 5px; font-weight: bold' id='titolo'>Gestione voto</p>
<form id='myform' action='mark_management.php?do=<?php print $_REQUEST['action'] ?>&q=<?php print $_REQUEST['q'] ?>' method='post' onsubmit='return check_form(this); '>
<table style='text-align: left; width: 95%; margin: auto' id='att'>	
	<tr>	
		<td style='width: 100px' id='lab1'>Voto *</td>	
		<td>		
			<select name='voto' id='voto' style='font-size: 11px; width: 97px'>			
				<option value='0'>Seleziona</option>	
				<?php
				if ($_SESSION['__materia__'] == 26 || $_SESSION['__materia__'] == 30){
					foreach ($voti_religione as $k => $g){
				?>
				<option value='<?php echo $k ?>'><?php echo $g ?></option>
				<?php
					}
				}
				else {
					$i = 100;		
					while($i > 9){		
				?>			
				<option value='<?php print ($i / 10) ?>' <?php if(isset($voto) && $voto['voto'] == ($i / 10)) echo "selected" ?>><?php print ($i / 10) ?></option>
				<?php 			
					$i -= 5;		
					}
				}		
				?>		
			</select>&nbsp;&nbsp;&nbsp;		
			<span style='margin-right: 5px' id='lab2'>Privato</span>
			<select id="private" style='width: 71px; font-size: 11px; padding-top: 3px; margin-left: 30px' name='private'>
				<option value="0" <?php if(isset($voto) && $voto['privato'] == 0) echo "selected" ?>>No</option>
				<option value="1" <?php if(isset($voto) && $voto['privato'] == 1) echo "selected" ?>>Si</option>
			</select>
		</td>
	</tr>
	<tr>	
		<td style='width: 100px' id='lab3'>Data *</td>	
		<td>		
			<input value="<?php if(isset($voto)) print format_date($voto['data_voto'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>" id='sel3' type='text' style='text-align: right; width: 95px; border: 1px solid #c0c0c0; font-size: 11px; padding-top: 3px' name='data_voto' />
			<script type="text/javascript">
            Calendar.setup({
                date		: new Date(<?php if(isset($voto)){ $dt = explode("-", $voto['data_voto']); print join(",", $dt); } ?>),
				inputField	: "sel3",
				ifFormat	: "%d/%m/%Y",
				showsTime	: false,
				firstDay	: 1,
				timeFormat	: "24",
				dateStatusFunc :    function (date) {
            	    return (date.getDay() == 0) ? true : false;
				}
			});
        	</script>
			<span style='margin-right: 40px; margin-left: 10px' id='lab4'>Tipo *</span>
			<select name='tipo' style='font-size: 11px; width: 73px'>			
				<option value='0'>Seleziona</option>			
				<?php 
				while($row = $res_prove->fetch_assoc()){
				?>
				<option value="<?php echo $row['id'] ?>" <?php if (isset($voto['tipologia']) && ($row['id'] == $voto['tipologia'])) echo "selected" ?>><?php echo $row['label'] ?></option>
				<?php } ?>
			</select>	
		</td>
	</tr>
	<tr>	
		<td style='width: 100px' id='lab5'>Prova *</td>	
		<td>		
			<input value="<?php if(isset($voto)) print $voto['descrizione'] ?>" name='descrizione' type='text' style='width: 259px; font-size: 11px' />	
		</td>
	</tr>
	<tr>	
		<td style='width: 100px' id='lab6'>Argomento *</td>	
		<td>		
			<textarea style='width: 259px; height: 40px; font-size: 11px' name='argomento'><?php if(isset($voto)) print $voto['argomento'] ?></textarea>	
		</td>
	</tr>
	<tr>	
		<td style='width: 100px'>Note</td>	
		<td>		
			<textarea style='width: 259px; height: 40px; font-size: 11px' name='note'><?php if(isset($voto)) print $voto['note'] ?></textarea>		
			<input type='hidden' name='id_materia' value='<?php print $_SESSION['__materia__'] ?>' />		
			<input type='hidden' name='alunno' value='<?php print $_REQUEST['alunno'] ?>' />	
		</td>
	</tr>
</table>
<div style='width: 94%; text-align: right; margin-top: 20px'>
<input type='submit' value='Invia' style='width: 50px; font-size: 11px; padding: 2px' id='invia' />
<input type='button' id='del_button' onclick='del_mark()' value='Elimina' style='border: 1px solid #c0c0c0; width: 50px; font-size: 11px; padding: 2px; margin-left: 10px; display: none' />
<input type='hidden' name='myid' value='<?php if(isset($voto)) print $voto['id_voto'] ?>' />
<input type='hidden' name='ia' />
<input type='hidden' name='referer' value="<?php echo $referer ?>" />
</div>
</form>
</div>
</body>
</html>