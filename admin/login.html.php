<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="../css/main.css" type="text/css" />
<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/scriptaculous.js"></script>
<script type="text/javascript" src="../js/page.js"></script>
<script type="text/javascript" src="../js/md5-min.js"></script>
<script type="text/javascript">
/*
 * funzione di login 
 */
function do_login(type){
	var nick = $('uname').value;
	var pwd = $('pwd').value;
	//alert(pwd);
	var pass = hex_md5(pwd);

	var url = "check_admin_auth.php";
	var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {uname: nick, pass: pass},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split(";");
			      		if(dati[0] == "ko"){
							alert("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
							return;
			      		}
			      		document.location.href = "index.php";
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}
</script>
<title>Registro elettronico</title>
</head>
<body>
	<div id="header">
		<div class="wrap">
			<?php include "header.php" ?>
		</div>
	</div>
	<div class="wrap">
		<div id="main" style="background-color: #FFFFFF; padding-bottom: 30px; margin: auto">
			<h3 style="margin-bottom: 20px; margin-left: auto; margin-right: auto; text-align: center">Accesso livello amministratore</h3>
			<fieldset style="padding: 10px; width: 80%; border: 1px solid; margin: auto">
			<legend style="padding: 5px">Login</legend>
			<table style="border-collapse: collapse">
				<tr style="height: 25px">
					<td style="width: 40%">username</td>
					<td style="width: 60%"><input type="text" style="width: 400px; " name="uname" id="uname" autofocus /></td>
				</tr>
				<tr style="height: 20px">
					<td style="width: 40%">password</td>
					<td style="width: 60%"><input type="password" style="width: 400px; " name="pwd" id="pwd" /></td>
				</tr>
				<tr style="height: 40px">
					<td colspan="2" style="text-align: right"><a href="#" onclick="do_login()">Log In</a></td>
				</tr>
			</table>
			</fieldset>
		</div>		
		<?php include "footer.php" ?>		
	</div>	
</body>
</html>