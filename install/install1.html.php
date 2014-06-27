<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Installazione software</title>
<link rel="stylesheet" href="../css/main.css" type="text/css" />
<link rel="stylesheet" href="../css/themes/default.css" type="text/css"/>
<link rel="stylesheet" href="../css/themes/alphacube.css" type="text/css"/>
<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/scriptaculous.js"></script>
<script type="text/javascript" src="../js/controls.js"></script>
<script type="text/javascript" src="../js/window.js"></script>
<script type="text/javascript" src="../js/window_effects.js"></script>
<script type="text/javascript" src="../js/page.js"></script>
<script type="text/javascript">
var _submit = function(){
	/*
	if($('host').empty() || $('user').empty() || $('password').empty() || $('db').empty() || $('port').empty()){
		alert("Tutti i campi sono obbligatori.");
		return false;
	}
	*/
	msg = "Errori: \n";
	index = 1;
	go = true;
	if(trim($F('host')) == ""){
		msg += index+". host empty\n";
		go = false;
		index++;
	}
	if(trim($F('user')) == ""){
		msg += index+". user empty\n";
		go = false;
		index++;
	}
	if(trim($F('password')) == ""){
		msg += index+". password empty\n";
		go = false;
		index++;
	}
	if(trim($F('db')) == ""){
		msg += index+". db empty\n";
		go = false;
		index++;
	}
	if(trim($F('port')) == ""){
		msg += index+". port empty\n";
		go = false;
		index++;
	}
	if(!go){
		alert(msg);
		return false;
	}
	var url = "init.php";
	req = new Ajax.Request(url,
		  {
		    	method:'post',
		    	parameters: $('form1').serialize(true),
		    	onSuccess: function(transport){
			    	var response = transport.responseText || "no response text";
			    	dati = response.split("#");
			    	//alert(response);
		    		if(dati[0] == "ko"){
		    			$('error_space').setStyle({border: '1px solid red', color: 'red', fontWeight: 'bold', textAlign: 'center'});
		    			$('error_space').update(dati[1]);
		    			switch(dati[2]){
		    				case "2005":
		    					$('server_error').setStyle({color: 'red', fontWeight: 'bold', textAlign: 'center'});
			    				$('server_error').update(dati[3]);
			    				$('user_error').update('');
		    					$('db_error').update('');
		    					$('port_error').update('');
			    				break;
		    				case  "1045":
		    					$('user_error').setStyle({color: 'red', fontWeight: 'bold', textAlign: 'center'});
		    					$('user_error').update(dati[3]);
		    					$('server_error').update('');
		    					$('db_error').update('');
		    					$('port_error').update('');
			    				break;
		    				case  "1049":
		    					$('db_error').setStyle({color: 'red', fontWeight: 'bold', textAlign: 'center'});
		    					$('db_error').update(dati[3]);
		    					$('user_error').update('');
		    					$('server_error').update('');
		    					$('port_error').update('');
			    				break;
		    				case  "2003":
		    					$('port_error').setStyle({color: 'red', fontWeight: 'bold', textAlign: 'center'});
		    					$('port_error').update(dati[3]);
		    					$('user_error').update('');
		    					$('db_error').update('');
		    					$('server_error').update('');
			    				break;
		    			}
		    			return;
		    		}
		    		else{
		    			$('go_link').update("<a href='install.php?step=2' class='nav_link_last'>Avanti</a>");
		    			$('error_space').setStyle({border: '0'});
		    			$('error_space').update("");
		    			$('db_error').update('');
    					$('user_error').update('');
    					$('server_error').update('');
    					$('port_error').update('');
		    			_alert("Connessione completata. Puoi proseguire col passo successivo: creazione del database");
		    		}
		    	},
		    	onFailure: function(){ alert("Si e' verificato un errore..."); }
		  });
};

document.observe("dom:loaded", function(){
	$('sub').observe("click", function(event){
		event.preventDefault();
		_submit();
	});
	<?php 
	if($warning){
	?>
	$('error_space').setStyle({border: '1px solid red', color: 'red', fontWeight: 'bold', textAlign: 'center'});
	$('error_space').update("<?php echo $warning ?>");
	$('sub').removeClassName('nav_link_last');
	$('sub').addClassName('nav_link');
	_a = document.createElement("a");
	_a.setAttribute("href", "install.php?step=2");
	_a.addClassName("nav_link_last");
	_a.appendChild(document.createTextNode("Avanti"));
	$('go_link').appendChild(document.createTextNode("|"));
	$('go_link').appendChild(_a);
	<?php } ?>
});
</script>
<style>
label{font-weight: bold}
tr{height: 25px}
</style>
</head>
<body>
	<div id="header">
		<div class="wrap">
			<h1 id="logo" style="margin-left: auto; margin-right: auto">Regel 1.0</h1><br />
			<div id="menu" style="clear: both; text-align: center; font-size: 15px; font-weight: bold; color: white">Installazione</div>
		</div>
	</div>
	<div class="wrap">
	<div id="main" style="background-color: #FFFFFF; padding-bottom: 30px; width: 100%">
	<form method="post" action="init.php" id="form1">
        <table style="width: 90%; margin-right: auto; margin-left: auto; border-collapse: collapse">
        	<tr>
                <td style="font-weight: bold; font-size: 15px; text-align: center; text-shadow: 2px 2px 1px #eee" colspan="3">Parametri di connessione al database</td>
            </tr>
            <tr style="height: 30px">
            	<td colspan="3" id="error_space"></td>
            </tr>
            <tr>
                <td style=" width: 20%"><label for="host">Server</label></td>
                <td style="width: 40%; text-align: right"><input type="text" id="host" name="host" style="width: 95%" autofocus /></td>
                <td style="width: 40%" id="server_error"></td>
            </tr>
            <tr>
                <td style=" width: 20%"><label for="db">Nome database</label></td>
                <td style="width: 40%; text-align: right"><input type="text" id="db" name="db" style="width: 95%" /></td>
                <td style="width: 40%" id="db_error"></td>
            </tr>
            <tr>
                <td style=" width: 20%"><label for="user">Utente</label></td>
                <td style="width: 40%; text-align: right"><input type="text" id="user" name="user" style="width: 95%" /></td>
                <td style="width: 40%" id="user_error" rowspan="2"></td>
            </tr>
            <tr>
                <td style=" width: 20%"><label for="password">Password</label></td>
                <td style="width: 40%; text-align: right"><input type="text" id="password" name="password" style="width: 95%" /></td>
            </tr>
            <tr>
                <td style=" width: 20%"><label for="port">Porta</label></td>
                <td style="width: 40%; text-align: right"><input type="text" id="port" name="port" style="width: 95%" value="3306" /></td>
                <td style="width: 40%" id="port_error"></td>
            </tr>
            <tr>
                <td colspan="2"><input type="hidden" name="step" id="step" value="1" /></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right">
                	<a href="index.php" class="nav_link_first">Indietro</a>|
                	<span id="go_link"><a href="../shared/no_js.php" id="sub" class="nav_link_last">Registra</a></span>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;&nbsp;&nbsp;</td>
            </tr>
        </table>
    </form>
    </div>
    <?php include "../admin/footer.php" ?>
	</div>		
</body>
</html>