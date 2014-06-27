<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aggiungi docente di sostegno</title>
<link href="../../css/reg.css" rel="stylesheet" type="text/css" />
	<link href="../../css/general.css" rel="stylesheet" type="text/css" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<link href="../../css/skins/aqua/theme.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript" src="../../js/calendar.js"></script>
<script type="text/javascript" src="../../js/lang/calendar-it.js"></script>
<script type="text/javascript" src="../../js/calendar-setup.js"></script>
<script type="text/javascript">

var messages = new Array('', 'Docente aggiunto', 'Docente modificato');

var save_teacher = function(cls){
	var url = "update_cdc.php";
	var req = new Ajax.Request(url,
		  {
		    	method:'post',
		    	parameters: {action: "add", cls: cls, mat: 27, doc: $F('doc'), ore: $F('ore')},
		    	onSuccess: function(transport){
		      		var response = transport.responseText || "no response text";
		      		//alert(response);
		      		//return;
		      		var dati = response.split("|");
		      		if(dati[0] == "ko"){
						_alert("Si e` verificato un errore. Si prega di riprovare tra qualche minuto");
						console.log(dati[1]+"\n"+dati[2]);
						return;
		      		}
		      		else if (dati[0] == "kosql"){
						sqlalert();
						return;
		      		}
		      		
		      		parent.document.location.href = "cdc.php?id="+cls;
		      		//parent.win.close();
		    	},
		    	onFailure: function(){ alert("Si e' verificato un errore..."); }
		  });
};

var _close = function(){
	parent.win.close();
};

</script>
</head>
<body style="background-color: white; margin: 0; background-image: none">
    <form action="update_cdc.php" method="post" id="doc_form">
    <div style="margin: 10px auto 0 auto; width: 95%">
    	<fieldset style="width: 100%; border: 1px solid #BBBBBB; padding: 10px 0 ; margin: 0 auto 0 auto; position: relative">
	    <legend style="font-weight: bold; margin-left: 10px">Docenti di sostegno</legend>
	    <table style="margin: 0 auto 0 auto; width: 280px">
	    	<tr class="popup_row header_row">
	            <td class="popup_title" style="width: 80px;padding-left: 10px">Docente *</td>
	            <td style="width: 200px; " colspan="3">
	            	<select class="form_input" name="doc" id="doc" autofocus style="width: 200px">
	            		<option value="0">.</option>
<?php 
while ($row = $res_teac->fetch_assoc()){
?>	            	
						<option value="<?php echo $row['uid'] ?>"><?php echo $row['cognome']." ".$row['nome'] ?>
<?php } ?>
	            	</select>
	            </td>
	        </tr>
	        <tr class="popup_row header_row">
	            <td class="popup_title" style="width: 80px;padding-left: 10px">Ore</td>
	            <td style="width: 200px; " colspan="3">
	            	<select class="form_input" name="ore" id="ore" style="width: 200px">
<?php 
for ($i = 0; $i < 23; $i++) {
?>	            	
						<option value="<?php echo $i ?>"><?php echo $i ?></option>
<?php } ?>						
	            	</select>
	            </td>
	        </tr>
	    </table>
	    </fieldset>
	    <div style="width: 100%; text-align: right; margin-top: 30px">
	    	<a href="#" onclick="save_teacher(<?php echo $cls ?>)" class="standard_link nav_link" style="color: #373946;">Registra</a>|
	        <a href="#" onclick="_close()" class="standard_link nav_link_last" style="color: #373946;">Chiudi</a>
	        <input type="hidden" name="action" id="action" />
		    <input type="hidden" name="_i" id="_i" />
		    <input type="hidden" name="tipo" id="tipo" value="<?php print $_REQUEST['tipo'] ?>" />
		    <input type="hidden" name="server_file" value="" id="server_file" />
		    <input type="hidden" name="oldfile" value="<?php if(isset($doc)) print $doc['file'] ?>"/>
		    <input type="hidden" name="progressivo_atto" id="progressivo_atto" value="<?php if(isset($doc)) print $doc['progressivo_atto'] ?>"/>
	    </div>
    </div>
    </form>
</body>
</html>