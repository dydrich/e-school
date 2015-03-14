<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Dettaglio sede</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link href="../../css/general.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javaScript">

		var go = function (par, sede){
		    if(par == 2){
		        if(!confirm("Sei sicuro di voler cancellare questa sede?"))
		            return false;
		    }
		    $('#_i').val(sede);
		    $('#action').val(par);
		    var url = "venues_manager.php";

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
						j_alert("alert", json.message);
					}
				}
			});
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
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
			<?php if(isset($_REQUEST['id']) && $_REQUEST['id'] != 0){
		    ?>
			$('#del_button').click(function(event){
				event.preventDefault();
				go(2, <?php print $_REQUEST['id'] ?>);
			});
			<?php
			}
			?>
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
	<form action="venues_manager.php" method="post" id="site_form" class="popup_form">
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
            <td colspan="2" style="margin-right: 30px; text-align: right" class="">
                <a href="../../shared/no_js.php" id="save_button" class="standard_link nav_link_last">Registra</a>
            </td>
        </tr>
    </table>
   	</form>
   	</div>
	<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="../../index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="../index.php"><img src="../../images/31.png" style="margin-right: 10px; position: relative; top: 5%" />Admin</a></div>
		<div class="drawer_link"><a href="http://www.istitutoiglesiasserraperdosa.it"><img src="../../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
