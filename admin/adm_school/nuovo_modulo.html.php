<!DOCTYPE html>
<html>
<head>
<title>Nuovo modulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../../css/site_themes/blue_red/reg.css" rel="stylesheet" />
<link href="../../css/general.css" rel="stylesheet" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javaScript">
function go(){
	msg = "Ci sono degli errori nel modulo: \n";
	index = 1;
	ok = true;
	if ($F('giorni').empty() || isNaN($F('giorni')) || ($F('giorni') > 6) || ($F('giorni') < 1)){
		msg += index+". Numero di giorni assente o non valido (inserire un numero compreso tra 1 e 6)\n";
		index++;
		ok = false;
	}
	if ($F('ore').empty() || isNaN($F('ore'))){
		msg += index+". Numero di ore settimanali assente o non valido (inserire un numero)\n";
		index++;
		ok = false;
	}
	if(!ok){
		alert(msg);
		return false;
	}
	$('action').setValue("new_module");
    var url = "module_manager.php";
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: $('site_form').serialize(true),
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		if(dati[0] == "ko"){
				      		_alert("Impossibile completare l'operazione. Si prega di riprovare tra poco");
							console.log("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
							return;
			      		}
			      		
			      		document.location.href = "dettaglio_modulo.php?idm="+dati[1];
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

document.observe("dom:loaded", function(){
	$('save_button').observe("click", function(event){
		event.preventDefault();
		go();
	});
});

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
	<div class="group_head">Nuovo modulo</div>
    <form action="module_manager.php" method="post" id="site_form" class="popup_form">
    <table style="width: 90%; margin: auto">
        <tr class="popup_row header_row">
            <td style="width: 30%"><label for="giorni" class="popup_title">Giorni</label></td>
            <td style="width: 70%">
                <input class="form_input" type="text" name="giorni" id="giorni" style="width: 100%" autofocus />
            </td>
        </tr>
        <tr class="popup_row header_row">
            <td style="width: 30%"><label for="ore" class="popup_title">Ore settimanali</label></td>
            <td style="width: 70%">
                <input class="form_input" type="text" name="ore" id="ore" style="width: 100%" />
            </td>
        </tr>
        <tr class="popup_row">
            <td colspan="2">
            	<input type="hidden" name="action" id="action" />
            </td>
        </tr>
        <tr>
            <td colspan="2" style="margin-right: 30px; text-align: right">
                <a href="../../shared/no_js.php" id="save_button" class="standard_link nav_link_first">Registra</a>|
                <a  href="moduli_orario.php" id="close_button" class="standard_link nav_link_last">Torna all'elenco</a>
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
