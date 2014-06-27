<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Elenco uffici</title>
<link rel="stylesheet" href="../../css/main.css" type="text/css" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript">
var myvar;
var messages = new Array('', 'Ufficio inserito con successo', 'Ufficio cancellato con successo', 'Ufficio modificato con successo');
function makeRequest(getvar) {
	var url = "uffici.php";
	var evaluated = eval("document.forms[0]."+getvar+".value");
	myvar = getvar;
	var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {action: "update", campo: getvar, value: evaluated},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		if(response == "ko"){
			      			alert("Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore al webmaster");
				     		return;
			     		}
			     		else{
			     			document.getElementById(myvar).innerHTML = "<a href=\"javascript:void(null);\" onclick=\"change(this,'"+myvar+"')\">"+eval("document.forms[0]."+myvar+".value")+"</a>";
			     		}
			      		openInfoDialog(messages[3], 2);
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

function change(link,textname){
     //link.innerText e link.text sono stessa cosa, c'è differenza tra ie e firefox
	if(link.innerText){
		testovecchio = link.innerText;
	}
	else{
		testovecchio = link.text;
	}
	//riscrivo il contenuto del div
	document.getElementById(textname).innerHTML="<input style='font-size: 11px; border: 1px solid gray' type='text' name='"+textname+"' value='"+testovecchio+"'> <a href='#' onclick=\"makeRequest(\'"+textname+"\')\">Registra</a>";
}

function del_ufficio(){
	var nome = prompt("Inserisci l'ID dell'ufficio da cancellare.");
	var ids = new Array();
	<?php
	for($i = 0; $i < count($array_id); $i++){
		print ("ids[$i] = ".$array_id[$i].";\n");
	}
	?> 
	if(isNaN(nome) || !in_array(ids, nome)){
		alert("Valore inserito non valido o non presente");
		return false;
	}
	
	var url = "<?php print $_SESSION['__config__']['root_site'] ?>admin/adm_workflow/uffici.php";
	var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {action: "delete", id: nome},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		if(response == "ko"){
			      			alert("Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore al webmaster");
				     		return;
			     		}
			     		else{
			     			window.document.location.href = "uffici.php?msg=2";
			     		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore...") }
			  });
}

function ufficio(){
	var nome = prompt("Inserisci il nome del nuovo ufficio.");
	if(!nome)
		return false;
	var url = "<?php print $_SESSION['__config__']['root_site'] ?>admin/adm_workflow/uffici.php";
	var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {action: "insert", nome: nome},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		if(response == "ko"){
			      			alert("Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore al webmaster");
				     		return;
			     		}
			     		else{
			     			window.document.location.href = "uffici.php?msg=3";
			     		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore...") }
			  });
}
</script>
<title>Amministrazione</title>
</head>
<body <?php if(isset($_REQUEST['msg'])){ ?>onload="openInfoDialog(messages[<?php print $_REQUEST['msg'] ?>], 2)"<?php } ?>>
    <div id="header">
		<div class="wrap" style="text-align: center">
			<?php include "../header.php" ?>
		</div>
	</div>
	<div class="wrap">
	<div id="main" style="background-color: #FFFFFF; padding-bottom: 30px; width: 100%">
    <form>
        <table class="admin_table">
            <tr class="admin_title_row">
                <td style="font-weight: bold" colspan="2" align="center">Elenco Uffici</td>
            </tr>
            <tr>
                <td style="width: 20%" class="adm_titolo_elenco_first">ID ufficio</td>
                <td style="width: 80%; padding-left: 20px" class="adm_titolo_elenco_last">Ufficio</td>
            </tr>
            <tr class="admin_row">
                <td colspan="2"></td>
            </tr>
            <?php
            $x = 1;
            $res_uffici->data_seek(0);
            while($ufficio = $res_uffici->fetch_assoc()){
            ?>
            <tr class="admin_row">
                <td style="padding-left: 5px;"><?php print $ufficio['id_ufficio'] ?></td>
                <td style="padding-left: 20px; color: #003366"><span id="row<?php print $ufficio['id_ufficio'] ?>"><a href="#" onclick="change(this, 'row<?php print $ufficio['id_ufficio'] ?>')"><?php print $ufficio['nome'] ?></a></span></td>
            </tr>
            <?php 
				$x++;
			} 
			?>
            <tr class="admin_void">
                <td colspan="2"></td>
            </tr>
            <tr class="admin_row_menu">
                <td colspan="2" align="right">
                	<a href="#" onclick="ufficio()" class="nav_link_first">Nuovo ufficio</a>|
                	<a href="#" onclick="del_ufficio()" class="nav_link">Cancella ufficio</a>|
                    <a href="index.php" class="nav_link_last">Torna indietro</a>
                </td>
            </tr>
            <tr class="admin_void">
                <td colspan="2"></td>
            </tr>
		</table>
	</form>
	</div>
        <?php include "../footer.php" ?>
    </div>				
</body>
</html>	