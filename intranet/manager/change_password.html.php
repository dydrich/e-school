<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
<link rel="stylesheet" href="../../css/site_themes/blue_red/reg.css" type="text/css" media="screen,projection" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/md5-min.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript">
function registra(){
	var patt = /[^a-zA-Z0-9]/;
	if(trim(document.forms[0].new_pwd.value) == ""){
		alert("Password non valida.");
		return false;
	}
	else if(document.forms[0].new_pwd.value.match(patt)){
		alert("Password non valida: sono ammessi solo lettere e numeri");
		return false;
	}
	if(trim(document.forms[0].new_pwd.value) != trim(document.forms[0].cfm_pwd.value)){
		alert("Le password inserite sono differenti. Ricontrolla.");
		return false;
	}
	p = hex_md5(document.forms[0].new_pwd.value);
	// fake password
	document.forms[0].new_pwd.value = "calatafimi";
	var req = new Ajax.Request('../../shared/new_pwd.php',
			  {
			    	method:'post',
			    	parameters: {new_pwd: p, table: 'utenti', campo: 'uid', p2: document.forms[0].new_pwd.value, from: ''},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		alert("Password modificata correttamente");
		            	if(dati[1] == "redirect"){
							window.location = "index.php";
		            	}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}
</script>
</head>
<body>
<?php include "header.php" ?>
<?php include $_SESSION['__administration_group__']."/navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include $_SESSION['__administration_group__']."/menu.php" ?>
</div>
<div id="left_col">
	<div class="group_head">
		Modifica password
	</div>
 	<form id="my_form" method="post" action="../../shared/new_pwd.php" style="border: 1px solid #666666; border-radius: 10px; margin-top: 30px; text-align: left; width: 460px; margin-left: auto; margin-right: auto">
	<table style="width: 400px; margin-left: auto; margin-right: auto; margin-top: 30px; margin-bottom: 20px">
		<tr>
			<td style="width: 60%">Nuova password</td>
			<td style="width: 40%"><input type="password" name="new_pwd" id="new_pwd" style="width: 250px; font-size: 11px; border: 1px solid #AAAAAA" /></td> 
		</tr>
		<tr>
			<td style="width: 60%">Conferma password</td>
			<td style="width: 40%"><input type="password" name="cfm_pwd" id="cfm_pwd" style="width: 250px; font-size: 11px; border: 1px solid #AAAAAA" /></td> 
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td> 
		</tr>
		<tr>
			<td colspan="2" style="text-align: right; margin-right: 50px">
				<a href="#" onclick="registra()" style="text-decoration: none; text-transform: uppercase">Registra</a>
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
