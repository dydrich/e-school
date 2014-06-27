<!DOCTYPE html>
<html>
<head>
<title>Registro di classe</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
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
function manage_test(){
	// check mandatory fields
	msg = "Ci sono degli errori nel modulo.";
	index = 1;
	_submit = true;
	if(!$('date_time').present()){
		msg += "\n"+index+". Data e ora della verifica non presenti.";
		_submit = false;
		$('date_time').style.borderColor = "red";
		index++;
	}
	else{
		$('date_time').style.borderColor = "gray";
	}
	if(!$('test').present()){
		msg += "\n"+index+". Inserire una descrizione della prova (ad. es. Verifica di italiano).";
		_submit = false;
		$('test').style.borderColor = "red";
		index++;
	}
	else{
		$('test').style.borderColor = "gray";
	}
	if(!$('subject').present()){
		msg += "\n"+index+". Argomento della prova non presente.";
		_submit = false;
		$('subject').style.borderColor = "red";
		index++;
	}
	else{
		$('subject').style.borderColor = "gray";
	}
	if($F('tipo')== "0"){
		msg += "\n"+index+". Tipologia della prova non presente.";
		_submit = false;
		$('tipo').style.borderColor = "red";
		index++;
	}
	else{
		$('tipo').style.borderColor = "gray";
	}
	if(!_submit){
		alert(msg);
		return false;
	}
	
	req = new Ajax.Request('test_manager.php',
			  {
			    	method:'post',
			    	parameters: $('testform').serialize(true),
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split("|");
			      		if(dati[0] == "ko"){
							alert("Errore: riprova tra un po'. Dettaglio: "+dati[1]+" ---- "+dati[2]);
							return;
			      		}
			      		if(dati[1] == "insert")
			      			alert("Verifica inserita");
			      		else
				      		alert("Verifica modificata");
			      		parent.window.location.href = "<?php if($_REQUEST['referer'] == test) print("test.php?idt=".$_REQUEST['test']); else print("tests.php") ?>";			      		
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
	  
}
</script>
<style>
* {font-size: 11px}
</style>
</head>
<body style="background-color: #FFFFFF" onload="<?php if(isset($_REQUEST['test'])){ ?>$('do').value = 'update'<?php } ?>">
<div id="main" style='width: 99%; margin: auto; text-align: center '>
	<p style='text-align: center; padding-top: 5px; font-weight: bold' id='titolo'><?php echo $label ?></p>
	<form id='testform' action='manage_test.php' method='post'>
		<table style='text-align: left; width: 95%; margin: auto' id='att'>
		<tr>
			<td style="width: 100px; font-weight: bold">Data e ora *</td>
			<td style="width: 290px;">
				<input type="hidden" id="do" name="do" value="insert" />
				<input type="hidden" id="id_verifica" name="id_verifica" value="<?php print $_REQUEST['test'] ?>" />
				<input type="text" id="date_time" name="date_time" style="width: 250px; border: 1px solid gray; font-size: 11px" readonly="readonly" value="<?php if(isset($_REQUEST['test'])) print format_date(substr($test['data_verifica'], 0, 10), SQL_DATE_STYLE, IT_DATE_STYLE, "/")." ".substr($test['data_verifica'], 11, 5) ?>" />
				<script type="text/javascript">
				Calendar.setup({
                date		: new Date(<?php if(isset($_REQUEST['test'])) print ("$y, $m, $d, $h, $mi") ?>),
				inputField	: "date_time",
				displayArea	: "dt",
				ifFormat	: "%d/%m/%Y %H:%M",
				showsTime	: true,
				firstDay	: 1,
				timeFormat	: "24",
				dateStatusFunc :    function (date) {
            	    return (date.getDay() == 0) ? true : false;
				}
			});
        </script>
			</td>
		</tr>
		<tr>
			<td style="width: 100px; font-weight: bold">Prova *</td>
			<td style=""><input type="text" id="test" name="test" style="width: 250px; border: 1px solid gray; font-size: 11px" value="<?php if(isset($_REQUEST['test'])) print $test['prova'] ?>" /></td>
		</tr>
		<tr>
			<td style="width: 100px; font-weight: bold">Tipologia *</td>
			<td style="">
			<select id="tipo" name="tipo" style="width: 250px; border: 1px solid gray; font-size: 11px">
			<option value="0">Scegli</option>
			<?php 
			while ($row = $res_prove->fetch_assoc()){
			?>
			<option value="<?php echo $row['id'] ?>" <?php if ($test && $test['tipologia'] == $row['id']) echo "selected" ?>><?php echo $row['tipologia'] ?></option>
			<?php } ?>
			</select>
			</td>
		</tr>
		<tr>
			<td style="width: 100px; font-weight: bold">Argomento *</td>
			<td style=""><textarea id="subject" name="subject" style="width: 250px; border: 1px solid gray; font-size: 11px; height: 30px"><?php if(isset($_REQUEST['test'])) print utf8_decode($test['argomento']) ?></textarea></td>
		</tr>
		<tr>
			<td style="width: 100px; font-weight: bold">Note</td>
			<td style=""><textarea id="notes" name="notes" style="width: 250px; border: 1px solid gray; font-size: 11px; height: 30px"><?php if(isset($_REQUEST['test'])) print utf8_decode($test['note']) ?></textarea></td>
		</tr>
		<tr>
			<td colspan="2" style="padding-top: 20px; text-align: right; padding-right: 30px"><a id="manage_link" href="#" onclick="manage_test()" style="text-decoration: none; text-transform: uppercase">Registra</a></td>
		</tr>
		</table>
	</form>
</div>
</body>
</html>