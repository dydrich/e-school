<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Giudizi parametro</title>
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../js/jquery.jeditable.mini.js"></script>
	<script type="text/javascript" src="../js/page.js"></script>
<script type="text/javaScript">

var add = function(){
    $('#_i').val(<?php echo $param['id'] ?>);
    $('#action').val(4);
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
				j_alert("alert", json.message);
				//document.location.href = document.location.href;
			}
		}
	});
};

var del = function(id){
    
    $('#_i').val(id);
    $('#action').val(5);
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
				j_alert("alert", json.message);
				$('#tr_'+id).hide();
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
	$('#add_button').click(function(event){
		event.preventDefault();
		add();
	});
	$('.del').click(function(event){
		event.preventDefault();
		strs = this.id.split("_");
		del(strs[1]);
	});
	$('.edit').editable('params_manager.php', {
		indicator : 'Saving...',
		tooltip   : 'Click to edit...',
		submitdata: {action: 6},
		cssclass: "no_border"
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
		<div class="group_head">Parametro: <?php echo $param['nome'] ?></div>
    <form action="params_manager.php" method="post" id="site_form" class="popup_form">
    <div style="width: 40%; float: left; padding: 10px">
    	<input class="form_input" name="giudizio" id="giudizio" style="width: 75%" />
    	<p style="font-weight: bold"><a href="#" id="add_button">Aggiungi un valore</a></p>
    </div>
    <div style="width: 55%; float: left">
    <table style="width: 95%; margin: auto">
    <?php while($giudizio = $res_g->fetch_assoc()){ ?>
        <tr class="manager_row header_row" id="tr_<?php echo $giudizio['id'] ?>" style="">
            <td style="width: 90%; border-bottom: 1px solid #CCC">
            	<p style="height: 20px; margin: 0" id="val_<?php echo $giudizio['id'] ?>" class="edit"><?php echo utf8_encode($giudizio['giudizio']) ?></p>
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
<?php include "footer.php" ?>
</body>
</html>
