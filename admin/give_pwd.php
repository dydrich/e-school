<?php 
switch($_REQUEST['gruppo']){
	case 2:
		$table = "rb_alunni";
		$id_name = "id_alunno";
		break;
	case 3:
	case 1:
	default:
		$table = "rb_utenti";
		$id_name = "uid";
		break;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="author" content="" />
<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/scriptaculous.js"></script>
<script type="text/javascript" src="../js/controls.js"></script>
<script type="text/javascript" src="../js/page.js"></script>
<script type="text/javascript" src="../js/md5-min.js"></script>
<script type="text/javascript">
	document.observe("dom:loaded", function(){
		$('n_pwd').focus();
	});

function registra(){
	var patt = /[^a-zA-Z0-9]/;
	if(trim(document.forms[0].n_pwd.value) == ""){
		alert("Password non valida.");
		return false;
	}
	else if(document.forms[0].n_pwd.value.match(patt)){
		alert("Password non valida: sono ammessi solo lettere e numeri");
		return false;
	}
	if(trim(document.forms[0].n_pwd.value) != trim(document.forms[0].n_pwd2.value)){
		alert("Le password inserite sono differenti. Ricontrolla.");
		return false;
	}
	p = hex_md5(document.forms[0].n_pwd.value);
	// fake password
	document.forms[0].n_pwd2.value = "calatafimi";
	var url = "adm_pwd_changer.php";
	var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {n_p: p, table: '<?php print $table ?>', campo: '<?php print $id_name ?>', uid: <?php print $_REQUEST['uid'] ?>, p2: document.forms[0].n_pwd.value},
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
			    		dati = response.split(";");
			      		if(dati[0] == "ko"){
			      			//alert("Password non modificata\n"+dati[1]+"\n"+dati[2]);
			      			parent.msg = "Password non modificata\n"+dati[1]+"\n"+dati[2];
			      			parent.openInfoDialog();
			            	parent.win.close();
			                return;
			     		}
			     		else{
			     			parent.msg = "Password modificata correttamente";
			     			parent.openInfoDialog();
			            	parent.win.close();
			     		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

</script>
</head>
<body style="background-color: whitesmoke; margin: 0; background-image: none">
	<div id="left_col" style="width: 100%; border-radius: 0; height: 100%; background-color: whitesmoke; margin: 0; padding-top: 0">
		<p class="popup_header">Modifica password di <?php print $_GET['ut'] ?></p>
		<form class="popup_form" style="width: 95%">
		<div style="margin-right: auto; margin-left: auto; margin-top: 5px; width: 95%">
		<div style='font-weight: bold; font-size: 11px; text-align: left; margin-top: 10px; margin-left: 15px' class='popup_title'>Nuova password<input style='width: 180px; float: right; margin-right: 20px' type='password' name='n_pwd' id='n_pwd' autofocus /></div>
		<div style='font-weight: bold; font-size: 11px; text-align: left; margin-top: 10px; margin-left: 15px' class='popup_title'>Reinserisci<input style='width: 180px; float: right; margin-right: 20px' type='password' name='n_pwd2' /></div>
		<div style='font-weight: bold; font-size: 11px; text-align: right; margin-top: 20px; margin-right: 20px; margin-bottom: 10px'>
			<a href="#" onclick='registra()' style="font-weight: normal; color: #003366" class="standard_link">Registra</a>&nbsp;&nbsp;|&nbsp;&nbsp;
			<a href="#" onclick="parent.win.close()" style="font-weight: normal; color: #003366" class="standard_link">Chiudi</a>
		</div>
		</div>
		</form>
	</div>
</body>
</html>
