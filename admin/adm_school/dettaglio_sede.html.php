<!DOCTYPE html>
<html>
<head>
<title>Dettaglio sede</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../../css/reg.css" rel="stylesheet" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javaScript">
var messages = new Array('', 'Sede inserita con successo', 'Sede cancellata con successo', 'Sede modificata con successo');
function go(par, sede){
    if(par == 2){
        if(!confirm("Sei sicuro di voler cancellare questa sede?"))
            return false;
    }
    $('_i').setValue(sede);
    $('action').setValue(par);
    var url = "sites_manager.php";
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
			      		
			      		link = "sedi.php?msg="+par;
			      		if(par != 1){
							link += "&second=1&offset=<?php print $offset ?>";
			      		}
			      		_alert(messages[par]);	
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

<?php include "../popup_dom.php" ?>

</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col">
	<div class="group_head">Dettaglio sede o plesso</div>
    <form action="sites_manager.php" method="post" id="site_form" class="popup_form">
    <table class="popup_table">
        <tr class="popup_row header_row">
            <td style="width: 30%"><label for="titolo" class="popup_title">Nome</label></td>
            <td style="width: 70%">
                <input class="form_input" type="text" name="titolo" id="titolo" style="width: 100%" <?php if(isset($sede)) print("value='".utf8_decode($sede['nome'])."'"); else print "autofocus" ?> />
            </td>
        </tr>
        <tr class="popup_row">
            <td style="width: 30%"><label for="testo" class="popup_title">Indirizzo</label></td>
            <td style="width: 70%">     
                <input class="form_input" type="text" name="testo" id="testo" style="width: 100%" <?php if(isset($sede)) print("value='".utf8_decode($sede['indirizzo'])."'") ?> />
            </td>
        </tr>
        <tr class="popup_row">
            <td colspan="2">
            	<input type="hidden" name="action" id="action" />
    			<input type="hidden" name="_i" id="_i" />
            </td>
        </tr>
        <tr>
            <td colspan="2" style="margin-right: 30px; text-align: right">
                <a href="../../shared/no_js.php" id="save_button" class="standard_link nav_link_last">Registra</a>
            </td>
        </tr>
    </table>
   	</form>
   	</div>
	<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
</body>
</html>