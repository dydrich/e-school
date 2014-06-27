<!DOCTYPE html>
<html>
<head>
<title>Giudizi parametro</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/main.css" rel="stylesheet" />
<link href="../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/scriptaculous.js"></script>
<script type="text/javascript" src="../js/controls.js"></script>
<script type="text/javascript" src="../js/page.js"></script>
<script type="text/javascript" src="../js/window.js"></script>
<script type="text/javascript" src="../js/window_effects.js"></script>
<script type="text/javaScript">
var messages = new Array('', 'Valore inserito con successo', 'Valore cancellato con successo', 'Valore modificato con successo');
function add(){
    $('_i').setValue(<?php echo $param['id'] ?>);
    $('action').setValue(4);
    var url = "params_manager.php";
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: $('site_form').serialize(true),
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		if(dati[0] == "ko"){
							alert("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
							return;
			      		}
			      		_alert(messages[1]);
			      		//noinspection SillyAssignmentJS
					    document.location.href = document.location.href;
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

function del(id){
    
    $('_i').setValue(id);
    $('action').setValue(5);
    var url = "params_manager.php";
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: $('site_form').serialize(true),
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		if(dati[0] == "ko"){
							alert("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
							return;
			      		}
			      		_alert(messages[2]);
			      		$('tr_'+id).hide();
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

document.observe("dom:loaded", function(){
	$$('.form_input').invoke("observe", "focus", function(event){
		this.setStyle({outline: '1px solid blue'});
	});
	$$('.form_input').invoke("observe", "blur", function(event){
		this.setStyle({outline: ''});
	});
	$('add_button').observe("click", function(event){
		event.preventDefault();
		add();
	});
	$$('.del').invoke("observe", "click", function(event){
		event.preventDefault();
		strs = this.id.split("_");
		del(strs[1]);
	});
});

</script>
</head>
<body>
<div id="header">
	<div class="wrap">
		<?php include "header.php" ?>
	</div>
</div>
<div class="wrap">
	<div id="main" style="background-color: #FFFFFF; padding-bottom: 30px; width: 100%">
	<p class="popup_label">Parametro: <?php echo $param['nome'] ?></p>
    <form action="params_manager.php" method="post" id="site_form" class="popup_form">
    <div style="width: 40%; float: left; padding: 10px">
    	<input class="form_input" name="giudizio" id="giudizio" style="width: 75%" />
    	<p style="font-weight: bold"><a href="#" id="add_button">Aggiungi un valore</a></p>
    </div>
    <div style="width: 55%; float: left">
    <table style="width: 95%; margin: auto">
    <?php while($giudizio = $res_g->fetch_assoc()){ ?>
        <tr class="popup_row header_row" id="tr_<?php echo $giudizio['id'] ?>" style="">
            <td style="width: 90%; border-bottom: 1px solid #CCC">
            	<p style="height: 20px; margin: 0" id="val_<?php echo $giudizio['id'] ?>"><?php echo $giudizio['giudizio'] ?></p>
            	<script type="text/javascript"> 
					new Ajax.InPlaceEditor('<?php print "val_".$giudizio['id'] ?>', 'params_manager.php', { 
						callback: function(form, value) { return 'action=6&_i=<?php echo $giudizio['id'] ?>&val='+encodeURIComponent(value); }
					});
				</script>
            </td>
            <td style="width: 10%; border-bottom: 1px solid #CCC; padding-top: 2px">
                <a href="#" id="del_<?php echo $giudizio['id'] ?>" class="del" style="color: red">x</a>
            </td>
        </tr>
    <?php } ?>
        <tr class="popup_row">
            <td colspan="2">
            	<input type="hidden" name="action" id="action" />
    			<input type="hidden" name="_i" id="_i" />
            </td>
        </tr>
        <tr>
            <td colspan="2" style="margin-right: 30px; text-align: right">
                <a  href="parametri_pagella.php?school_order=<?php echo $school_order ?>&id=<?php echo $_REQUEST['id'] ?>" id="close_button" class="nav_link_last">Torna all'elenco</a>
            </td>
        </tr>
    </table>
    </div>
    <p style="clear: left"></p>
   	</form>
   	</div>
</div>
</body>
</html>