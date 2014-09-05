<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link rel="stylesheet" href="../css/site_themes/blue_red/reg.css" type="text/css" />
<link rel="stylesheet" href="../css/general.css" type="text/css" />
<link rel="stylesheet" href="../css/jquery/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../js/page.js"></script>
<script type="text/javascript" src="../js/md5-min.js"></script>
<script type="text/javascript">
var table = 'rb_utenti';
var id_name = 'uid';
var uid = 0;

var gruppo = function(gr){
	document.location.href = "new_pwd.php?gruppo="+gr;
};

var new_pwd = function(gruppo, _uid, utente){
	$('#user_name').text("Modifica password di "+utente);
	if (gruppo == 2) {
		table = "rb_alunni";
		id_name = "id_alunno";
	}
	uid = _uid;
	$('#dialog').dialog({
		autoOpen: true,
		show: {
			effect: "appear",
			duration: 500
		},
		hide: {
			effect: "slide",
			duration: 300
		},
		buttons: [
			{
				text: "Chiudi",
				click: function() {
					$( this ).dialog( "close" );
				}
			},
			{
				text: "Registra",
				click: function() {
					save();
				}
			}
		],
		modal: true,
		width: 450,
		title: 'Modifica password',
		open: function(event, ui){

		}
	});
};

var save = function(){
	var patt = /[^a-zA-Z0-9]/;
	if($('#n_pwd').val() == ""){
		alert("Password non valida.");
		return false;
	}
	else if($('#n_pwd').val().match(patt)){
		alert("Password non valida: sono ammessi solo lettere e numeri");
		return false;
	}
	if($('#n_pwd').val() != $('#n_pwd2').val()){
		alert("Le password inserite sono differenti. Ricontrolla.");
		return false;
	}
	p = hex_md5($('#n_pwd').val());
	// fake password
	$('#n_pwd2').val("calatafimi");
	var url = "adm_pwd_changer.php";

	$.ajax({
		type: "POST",
		url: url,
		data: {n_p: p, table: table, campo: id_name, uid: uid, p2: $('#n_pwd').val()},
		dataType: 'json',
		error: function() {
			alert("Errore di trasmissione dei dati");
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
				alert(json.message);
			}
			$( '#dialog' ).dialog( "close" );
		}
	});
};

</script>
<style>
tbody td:hover {background-color: #e8f2fe }
</style>
<title>Modifica password</title>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "adm_users/menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Modifica password utente</div>
    <form class="no_border">
        <table class="admin_table">
        <thead>
            <tr class="admin_row" style="border: 0">
                <td colspan="4" style="text-align: center">
                	<a href="#" onclick="gruppo(1)" style="">Personale della scuola</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                	<a href="#" onclick="gruppo(2)" style="">Studenti</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                	<a href="#" onclick="gruppo(3)" style="">Genitori</a>
                </td>
            </tr>
            <tr class="admin_void" style="border: 0; height: 5px">
                <td colspan="4"></td>
            </tr>
            <?php 
            if($gruppo == 2){
            ?>
            <tr style="vertical-align: middle; text-align: center; height: 20px; background-color: #E7E7E7">
                <td colspan="4" style="border: 0; border-radius: 3px 3px 3px 3px; ">
                <?php
		        for($i = 0; $i < count($alfabeto); $i++){
		        	if(isset($_REQUEST['start']) && $_REQUEST['start'] == $alfabeto[$i]){
		        ?>
		        	<span>[&nbsp;<?php print $alfabeto[$i] ?>&nbsp;]</span>
		        <?php
		        	}
		        	else{
		        ?>
		            <a style="text-decoration: none" href="new_pwd.php?gruppo=<?php print $gruppo?>&start=<?php print $alfabeto[$i] ?>">&nbsp;&nbsp;<?php print $alfabeto[$i] ?>&nbsp;&nbsp;</a>
		        <?php
		        	}
		        }
		        ?>
		        	<a style="text-decoration: none" href="#" onclick="gruppo(<?php print $gruppo?>)">&nbsp;&nbsp;Tutti&nbsp;&nbsp;</a>
                </td>
            </tr>
            <tr class="admin_void">
                <td colspan="4"></td>
            </tr>
            <?php 
            }
            ?>
            </thead>
            <tbody>
            <tr class="admin_row" style="height: 20px">
            <?php 
            $x = 1;
            while($utente = $res_utenti->fetch_assoc()){
            ?>
                <td style="width: 20%; vertical-align: middle"><a href="#" onclick="new_pwd(<?php print $gruppo ?>, <?php  print $utente['id'] ?>, '<?php print $utente['cognome']." ".$utente['nome'] ?>')" title="<?php  print $utente['id'] ?>" style="text-decoration: none;"><?php print $utente['cognome']." ".$utente['nome'] ?></a></td>
            <?php 
            	if($x%4 == 0){
            		print("</tr>\n\t<tr class='admin_row' style='height: 20px'>\n");
            	}
            	$x++;
            }
            ?>
            </tr>
            </tbody>
            <tfoot>
            <tr class="admin_void">
                <td colspan="4"></td>
            </tr>
            <tr class="admin_void">
                <td colspan="4">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            </tfoot>
        </table>
    </form>
    </div>	
	<p class="spacer"></p>
	</div>
    <?php include "footer.php" ?>
<div id="dialog" style="width: 100%; border-radius: 0; height: 100%; background-color: whitesmoke; margin: 0; padding-top: 0; display: none">
	<p class="popup_header" id="user_name"></p>
	<form class="popup_form" style="width: 95%">
		<div style="margin-right: auto; margin-left: auto; margin-top: 5px; width: 95%">
			<div style='font-weight: bold; font-size: 11px; text-align: left; margin-top: 10px; margin-left: 15px' class='popup_title'>Nuova password<input style='width: 180px; float: right; margin-right: 20px' type='password' name='n_pwd' id='n_pwd' autofocus /></div>
			<div style='font-weight: bold; font-size: 11px; text-align: left; margin: 10px 0 15px 15px' class='popup_title'>Reinserisci<input style='width: 180px; float: right; margin-right: 20px' type='password' name='n_pwd2' id='n_pwd2' /></div>
		</div>
	</form>
</div>
</body>
</html>
