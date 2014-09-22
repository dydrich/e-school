<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Dettaglio parametro</title>
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../js/page.js"></script>
	<script type="text/javaScript">

	var go = function(par, sede){
	    if(par == 2){
	        if(!confirm("Sei sicuro di voler cancellare questo parametro?"))
	            return false;
	    }
	    $('#_i').val(sede);
	    $('#action').val(par);
	    var url = "params_manager.php";

		$.ajax({
			type: "POST",
			url: url,
			data: $('#site_form').serialize(true),
			dataType: 'json',
			error: function() {
				console.log(json.dbg_message);
				j_alert("error", "Errore di trasmissione dei dati");
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
					console.log(json.dbg_message);
					console.log(json.query);
					j_alert("error", json.message);
				}
				else {
					j_alert("alert", "Operazione conclusa");
				}
			}
		});
	};

	$(function(){
		load_jalert();
		$('.form_input').focus(function(event){
			$(this).css({outline: '1px solid blue'});
		});
		$('.form_input').blur(function(event){
			$(this).css({outline: ''});
		});
		$('#save_button').click(function(event){
			event.preventDefault();
			go(<?php if(isset($_REQUEST['id']) && $_REQUEST['id'] != 0) print("3, ".$_REQUEST['id']); else print("1, 0"); ?>);
		});
	});

	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "scr_menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Dettaglio parametro di valutazione</div>
    <form action="sites_manager.php" method="post" id="site_form" class="popup_form">
    <table style="width: 90%; margin: auto">
        <tr class="popup_row header_row">
            <td style="width: 30%"><label for="titolo" class="popup_title">Nome</label></td>
            <td style="width: 70%">
                <input class="form_input" type="text" name="titolo" id="titolo" style="width: 100%" <?php if(isset($param)) print("value='".utf8_decode($param['nome'])."'"); else print "autofocus" ?> />
            </td>
        </tr>
        <tr class="popup_row">
            <td style="width: 30%"><label for="testo" class="popup_title">Quadrimestre</label></td>
            <td style="width: 70%">     
                <select class="form_input" name="q" id="q" style="width: 100%">
                	<option value="0">Tutti</option>
                	<option value="1" <?php if(isset($param) && $param['quadrimestre'] == 1) echo "selected" ?>>Primo</option>
                	<option value="2" <?php if(isset($param) && $param['quadrimestre'] == 2) echo "selected" ?>>Secondo</option>
                </select>
            </td>
        </tr>
        <tr class="popup_row">
            <td colspan="2">
            	<input type="hidden" name="action" id="action" />
    			<input type="hidden" name="_i" id="_i" />
    			<input type="hidden" name="school_order" id="school_order" value="<?php echo $school_order ?>" />
            </td>
        </tr>
        <tr>
            <td colspan="2" style="margin-right: 30px; text-align: right">
                <a href="../shared/no_js.php" id="save_button" class="standard_link nav_link_first">Registra</a>|
                <a  href="parametri_pagella.php?school_order=<?php echo $school_order ?>" id="close_button" class="standard_link nav_link_last">Torna all'elenco</a>
            </td>
        </tr>
    </table>
   	</form>
   	</div>
	<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
