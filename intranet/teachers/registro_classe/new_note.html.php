<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Nota disciplinare</title>
<link rel="stylesheet" href="reg_classe_popup.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/skins/aqua/theme.css" type="text/css" />
<script type="text/javascript" src="../../../js/prototype.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
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
			     		parent.win.close();
			     		var param_char = "<?php if(strpos($referer, "?") === false) echo "?"; else echo "&" ?>";
			     		parent.document.location.href = "<?php echo $referer ?>"+param_char+"msg=Nota_inserita";
			    	},
			    	onFailure: function(){ _alert("Si e' verificato un errore..."); }
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
			     		parent.document.location.href = "<?php echo $referer ?>";
			    	},
			    	onFailure: function(){ _alert("Si e' verificato un errore..."); }
			  });
};

var _submit = function(){
	msg = "Ci sono degli errori nella compilazione del modulo.";
	go = true;
	index = 1;
	if(!$('desc').present()){
		msg += "\n"+index+". Descrizione della nota non presente";
		$('desc').style.borderColor = "red";
		go = false;
	}
	if(!go){
		alert(msg);
		return false;
	}
	note_manager();
};

</script>
</head>
<body>
<div id="main">
	<p style='text-align: center; padding-top: 5px; font-weight: bold' id='titolo'>Note disciplinari</p>
	<form id='testform' action='manage_test.php' method='post' onsubmit="_submit()">
		<table style='text-align: left; width: 95%; margin: auto' id='att'>
		<tr>
			<td style="width: 25%; font-weight: bold">Tipo nota *</td>
			<td style="width: 75%; " colspan="3">
				<select id="type" name="type" style="font-size: 11px; border: 1px solid #AAAAAA; background-color: rgba(211, 222, 199, 0.7); width: 100%">
				<?php 
				while($t = $res_types->fetch_assoc()){
				?>
					<option <?php if(isset($nota) && $nota['tipo'] == $t['id_tiponota']) print("selected='selected'") ?> value="<?= $t['id_tiponota'] ?>"><?= utf8_decode($t['descrizione']) ?></option>
				<?php } ?>
				</select>
			</td>
			</tr>
			<tr>
			<td style="width: 25%; font-weight: bold">Descrizione *</td>
			<td style="width: 75%; " colspan="3">
				<textarea style="width: 100%; height: 40px; font-size: 11px; border: 1px solid gray" id="desc" name="desc"><?php if(isset($nota)) print(utf8_decode($nota['descrizione'])) ?></textarea>
			</td>
			</tr>
			<tr>
			<td style="width: 25%; font-weight: bold">Data *</td>
			<td style="width: 75%; font-weight: normal" colspan="3">
				<input type="hidden" name="action" id="action" value="<?php if(isset($nota)) print "update"; else print "insert" ?>" />
				<input type="hidden" name="stid" id="stid" value="<?= $stid ?>" />
				<input type="hidden" name="referer" id="referer" value="<?= $referer ?>" />
				<input type="hidden" name="id_nota" id="id_nota" value="<?php if(isset($nota)) print $nota['id_nota'] ?>" />
				<input type="text" style="font-size: 11px; border: 1px solid gray; width: 99%" id="_date" name="_date" readonly="readonly" value="<?php if(isset($_REQUEST['data'])) print(format_date($_REQUEST['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/")); else if(isset($nota)) print (format_date($nota['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"));  else print date("d/m/Y") ?>" />
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
			<td colspan="4" style="padding-top: 20px; text-align: right;">
			<?php if($nota['docente'] == $_SESSION['__user__']->getUid() || !isset($nota)){ ?>
				<a id="manage_link" href="#" onclick="_submit()">Registra</a>
				<?php if(isset($nota)){ ?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" onclick="del_note(<?php print $nota['id_nota'] ?>)">Cancella nota</a><?php } ?>
			<?php } ?>
			</td>
		</tr>
		</table>
	</form>
</div>
</body>
</html>