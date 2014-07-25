<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
<link rel="stylesheet" href="../reg.css" type="text/css" media="screen,projection" />
<link href="../../../css/general.css" rel="stylesheet" type="text/css"/>
<link href="../../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<link href="../../../css/skins/aqua/theme.css" type="text/css" rel="stylesheet"  />
<script type="text/javascript" src="../../../js/prototype.js"></script>
<script type="text/javascript" src="../../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript" src="../../../js/window.js"></script>
<script type="text/javascript" src="../../../js/window_effects.js"></script>
<script type="text/javascript" src="../../../js/calendar.js"></script>
<script type="text/javascript" src="../../../js/lang/calendar-it.js"></script>
<script type="text/javascript" src="../../../js/calendar-setup.js"></script>
<script type="text/javascript">
function save_homework(){
	//alert($('myform').serialize());
	if(document.forms[0].del.value == 1){
		if(!confirm("Sei sicuro di voler cancellare questo compito?"))
			return false;
	}
	var req = new Ajax.Request('save_homework.php',
			  {
			    	method:'post',
			    	parameters: $('myform').serialize(),
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split("#");
			     		alert(dati[1]);
			     		parent.location.href = '<?php echo $ref ?>';
			     		//window.close();
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore...") }
			  });
}

var oldLink = null;

document.observe("dom:loaded", function(){
	$('save_button').observe("click", function(event){
		event.preventDefault();
		save_homework();
	});
	$('del_button').observe("click", function(event){
		event.preventDefault();
		document.forms[0].del.value = 1;
		save_homework();
	});
});
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
	<p style="text-align: center; padding-top: 10px; font-weight: bold"><?php print $title ?></p>
	<form id="myform" class="reg_form">
	<table style="width: 75%; margin: 20px auto 20px auto">
	<tr>
		<td style="width: 20%" class="label_form">Per il</td>
		<td style="width: 80%">
			<input id="sel3" type="text" value="<?php if($upd == 1) print format_date($di, SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>" style="width: 100%; border: 1px solid #c0c0c0; font-size: 11px; padding-top: 3px" name="data_inizio" />&nbsp;
			<script type="text/javascript">
	            Calendar.setup({
	                date		: new Date(),
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
		</td>
	</tr>
	<tr>
		<td style="width: 20%" class="label_form">Materia</td>
		<td style="width: 80%">
			<select name="materia" id="tipo" style="border: 1px solid #c0c0c0; font-size: 11px; width: 100%">
				<option value="0">Nessuna</option>
<?php 
while($mat = $res_materie->fetch_assoc()){
?>
			<option <?php if((isset($att)) && $mat['id_materia'] == $att['materia']) print("selected='selected'") ?> value="<?php print $mat['id_materia'] ?>"><?php print $mat['materia'] ?></option>
<?php 
}
?>
			</select>
		</td>
	</tr>
	<tr>
		<td style="width: 20%" class="label_form">Dettagli</td>
		<td style="width: 80%">
			<textarea style="width: 100%; height: 40px; border: 1px solid #c0c0c0; font-size: 11px" name="descrizione"><?php if($upd == 1) print $att['descrizione'] ?></textarea>
		</td>
	</tr>
	<tr>
		<td style="width: 20%" class="label_form">Note</td>
		<td>
			<textarea style="width: 100%; height: 40px; border: 1px solid #c0c0c0; font-size: 11px" name="note"><?php if($upd == 1) print $att['note'] ?></textarea>
			<input type="hidden" name="id_act" value="<?php echo $t ?>" />
			<input type="hidden" name="del" />
		</td>
	</tr>
	</table>
	</form>
	<div style="width: 92%; margin: 20px 0 0 0; text-align: right">
		<?php if($t != 0){ ?>
		<a href="../../../shared/no_js.php" id="del_button" class="standard_link nav_link_first">Elimina</a>|
		<?php } ?>
		<a href="../../../shared/no_js.php" id="save_button" class="standard_link nav_link_last">Salva</a>
	</div> 
</div> 
<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
</body>
</html>
