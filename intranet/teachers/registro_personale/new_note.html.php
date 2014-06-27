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
function note_manager(){
	var url = "note_manager.php";
	//alert(url);
	var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: $('testform').serialize(true),
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split(";");
			      		if(dati[0] == "ko"){
							alert("Nota non inserita. Dettaglio: "+dati[1]+"---"+dati[2]);
							return false;
			     		}
			     		else{
			     			alert("Nota inserita");	     			
			     		}
			     		parent.win.close();
			     		parent.document.location.href = "<?= $referer ?>";
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

var del_note = function(id_nota){
	if(!confirm("Sei sicuro di voler cancellare questa nota?"))
		return false;
	var url = "note_manager.php";
	//alert(url);
	var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {action: "delete", id_nota: id_nota},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split(";");
			      		if(dati[0] == "ko"){
							alert("Nota non eliminata. Dettaglio: "+dati[1]+"---"+dati[2]);
							return false;
			     		}
			     		else{
			     			alert("Nota eliminata");	     			
			     		}
			     		parent.win.close();
			     		parent.document.location.href = "<?= $referer ?>";
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};

var _submit = function(){
	msg = "Ci sono degli errori nella compilazione del modulo.";
	go = true;
	index = 1;

	if(!go){
		alert(msg);
		return false;
	}
	note_manager();
};

</script>
<style>
* { font-size: 11px }
</style>
</head>
<body>
<div id="main">
	<p style='text-align: center; padding-top: 25px; font-weight: bold; padding-bottom: 10px' id='titolo'>Note didattiche</p>
	<form id='testform' action='manage_test.php' method='post' onsubmit="_submit()">
		<table style='text-align: left; width: 95%; margin: auto' id='att'>
		<tr>
			<td style="width: 25%; font-weight: bold">Tipo nota *</td>
			<td style="width: 75%; " colspan="3">
				<select id="type" name="type" style="font-size: 11px; border: 1px solid gray; width: 100%">
				<?php 
				while($t = $res_types->fetch_assoc()){
				?>
					<option <?php if(isset($nota) && ($t['id_tiponota'] == $nota['tipo'])) print "selected='selected'"  ?> value="<?= $t['id_tiponota'] ?>"><?= utf8_decode($t['descrizione']) ?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td style="width: 25%; font-weight: bold">Data *</td>
			<td style="width: 75%; font-weight: normal" colspan="3">
				<input type="hidden" name="action" id="action" value="<?php if(isset($nota)) print "update"; else print "insert" ?>" />
				<input type="hidden" name="stid" id="stid" value="<?php echo $stid ?>" />
				<input type="hidden" name="referer" id="referer" value="<?php echo $referer ?>" />
				<input type="hidden" name="id_nota" id="id_nota" value="<?php if(isset($nota)) print $nota['id_nota'] ?>" />
				<input type="text" style="font-size: 11px; border: 1px solid gray; width: 99%" id="_date" name="_date" readonly="readonly" value="<?php if(isset($nota)) print format_date($nota['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"); else print date("d/m/Y") ?>" />
				<script type="text/javascript">
				Calendar.setup({
	                date		: new Date(),
					inputField	: "_date",
					displayArea	: "dt",
					ifFormat	: "%d/%m/%Y",
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
			<td style="width: 25%; font-weight: bold">Note </td>
			<td style="width: 75%; " colspan="3">
				<textarea style="width: 100%; height: 40px; font-size: 11px; border: 1px solid gray" id="desc" name="desc"><?php if(isset($nota)) print utf8_decode($nota['note']) ?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="4" style="padding-top: 20px; text-align: right;">
				<a id="manage_link" href="#" onclick="_submit()">Registra</a>
				<?php if(isset($nota)){ ?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" onclick="del_note(<?php print $nota['id_nota'] ?>)">Cancella nota</a><?php } ?>	
			</td>
		</tr>
		<tr>
			<td colspan="4" style="height: 40px"></td>
		</tr>
		</table>
	</form>
</div>
</body>
</html>