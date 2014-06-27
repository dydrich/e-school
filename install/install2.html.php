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
		    			$('error_space').setStyle({border: '1px solid red', color: 'red', fontWeight: 'bold'});
		    			$('error_space').update(dati[1]+"===>"+dati[2]);
		    			
		    			return;
		    		}
		    		else{
		    			$('go_link').update("<a href='install.php?step=3' class='nav_link_last'>Avanti</a>");
		    			$('error_space').setStyle({border: '0'});
		    			$('error_space').update("");
		    			_alert("Operazione completata. Puoi passare all'ultima fase: inserimento dei dati essenziali");
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
                <td style="font-weight: bold; font-size: 15px; text-align: center; text-shadow: 2px 2px 1px #eee" colspan="3">Creazione e popolamento del database: utente amministratore</td>
            </tr>
            <tr style="height: 30px">
            	<td colspan="3" id="error_space"></td>
            </tr>
            <tr>
                <td style=" width: 20%"><label for="fname">Nome</label></td>
                <td style="width: 40%; text-align: right"><input type="text" id="fname" name="fname" style="width: 95%" autofocus /></td>
                <td style="width: 40%"></td>
            </tr>
            <tr>
                <td style=" width: 20%"><label for="lname">Cognome</label></td>
                <td style="width: 40%; text-align: right"><input type="text" id="lname" name="lname" style="width: 95%" /></td>
                <td style="width: 40%"></td>
            </tr>
            <tr>
                <td style=" width: 20%"><label for="email">Email</label></td>
                <td style="width: 40%; text-align: right"><input type="text" id="email" name="email" style="width: 95%" /></td>
                <td style="width: 40%"></td>
            </tr>
            <tr>
                <td style=" width: 20%"><label for="pwd">Password</label></td>
                <td style="width: 40%; text-align: right"><input type="text" id="pwd" name="pwd" style="width: 95%" /></td>
                <td style="width: 40%"></td>
            </tr>
            <tr>
                <td colspan="2"><input type="hidden" name="step" id="step" value="2" /></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right">
                	<a href="index.php?step=2" class="nav_link_first">Indietro</a>|
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