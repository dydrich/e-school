<!DOCTYPE html>
<html>
<head>
	<title>Nuovo modulo</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link href="../../css/general.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javaScript">
	var go = function(){
		msg = "Ci sono degli errori nel modulo: \n";
		index = 1;
		ok = true;
		$giorni = $('#giorni').val();
		$ore = $('#ore').val();
		if ($giorni == "" || isNaN($giorni) || ($giorni > 6) || ($giorni < 1)){
			msg += index+". Numero di giorni assente o non valido (inserire un numero compreso tra 1 e 6)\n";
			index++;
			ok = false;
		}
		if ($ore == "" || isNaN($ore)){
			msg += index+". Numero di ore settimanali assente o non valido (inserire un numero)\n";
			index++;
			ok = false;
		}
		if(!ok){
			alert(msg);
			return false;
		}
		$('#action').val("new_module");
	    var url = "module_manager.php";

		$.ajax({
			type: "POST",
			url: url,
			data:  $('#site_form').serialize(true),
			dataType: 'json',
			error: function() {
				show_error("Errore di trasmissione dei dati");
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
					document.location.href = "dettaglio_modulo.php?idm="+json.idm;
				}
			}
		});
	};

	$(function(){
		$('#save_button').click(function(event){
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
