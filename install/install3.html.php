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
		    			$('error_space').update(dati[1]+". Query: "+dati[2]);
		    			
		    			return;
		    		}
		    		else{
						$('link_row').setAttribute("colspan", "3");
		    			$('link_row').update("L'installazione &egrave; stata completata con successo. Ora puoi cominciare ad usare il software. <a href='../index.php'>Vai alla home page</a>");
		    			$('error_space').setStyle({border: '0'});
		    			$('error_space').update("");
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
                <td style="font-weight: bold; font-size: 15px; text-align: center; text-shadow: 2px 2px 1px #eee" colspan="3">Creazione e popolamento del database</td>
            </tr>
            <tr style="height: 30px">
            	<td colspan="3" id="error_space"></td>
            </tr>
            <tr>
                <td style=" width: 20%"><label for="school">Denominazione scuola</label></td>
                <td style="width: 40%; text-align: right"><input type="text" id="school" name="school" style="width: 95%" autofocus /></td>
                <td style="width: 40%"></td>
            </tr>
            <tr>
                <td style=" width: 20%"><label for="address">Indirizzo scuola</label></td>
                <td style="width: 40%; text-align: right"><input type="text" id="address" name="address" style="width: 95%" /></td>
                <td style="width: 40%"></td>
            </tr>
            <tr>
                <td style=" width: 20%"><label for="start">Orario inizio lezioni</label></td>
                <td style="width: 40%; text-align: right"><input type="text" id="start" name="start" maxlength="5" style="width: 95%" value="08:30" /></td>
                <td style="width: 40%"></td>
            </tr>
            <tr>
                <td style=" width: 20%"><label for="stop">Orario termine lezioni</label></td>
                <td style="width: 40%; text-align: right"><input type="text" id="start" name="start" maxlength="5" style="width: 95%" value="13:30" /></td>
                <td style="width: 40%"></td>
            </tr>
            <tr>
                <td colspan="2"><input type="hidden" name="step" id="step" value="3" /></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right" id="link_row">
                	<a href="index.php?step=3" class="nav_link_first">Indietro</a>|
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