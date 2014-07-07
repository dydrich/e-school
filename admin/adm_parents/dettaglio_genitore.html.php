<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dettaglio genitore</title>
<link href="../../css/reg.css" rel="stylesheet" />
<link href="../../css/general.css" rel="stylesheet" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/md5-min.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript">
var messages = new Array('', 'Genitore inserito con successo', 'Genitore cancellato con successo', 'Genitore modificato con successo');
var new_account = false;
var new_data = false;
var oldLink = null;
var _win;
<?php if(isset($genitore)) print("var old_login = '".$genitore['username']."';\n") ?>
var id_alunni = new Array;
<?php
if($_i != 0){
	//for($i = 0; $i < count($id_figli); $i++)
		//print("id_alunni[$i] = $id_figli[$i];\n");
	while(list($key, $value) = each($id_figli)){
		$value = $db->real_escape_string($value);
		print("id_alunni['$key'] = '$value';\n");
	}
}
?>

function go(par, genit){
    $('_i').setValue(genit);
    $('action').setValue(par);
    var url = "parent_manager.php";
    //alert($F('pwd'));
	var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: $('parent_form').serialize(true),
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		if(dati[0] == "kosql"){
							alert("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
							return;
			      		}
			      		else if (dati[0] == "ko"){
							alert(dati[1]);
							return;
			      		}
			      		link = "genitori.php?msg="+par;
			      		_alert(messages[par]);
			      		setTimeout("document.location.href='dettaglio_genitore.php?id=0&school_order=<?php echo $school_order ?>'", 2000);
			      		if(par != 1){
				      		//reset_form();
							link += "&second=1&offset=<?php print $offset ?>";
			      		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore...");}
			  });
    
}

var reset_form = function(){
	$('us').setValue("");
	$('pwd').setValue("");
	$('nome').setValue("");
	$('cognome').setValue("");
	$('email').setValue("");
};

function verifica(){
    //alert("ok");
	var nick = $F('us');
	var url = "../../shared/verifica_login.php";
	var id = <?php print $_REQUEST['id'] ?>;
	var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {nick: nick, table: 'rb_genitori', id: id},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var x = $("check");
			            x.update("");
			            var dati = response.split("|");
			      		if(dati[0] == "ko"){
							alert("Si e` verificato un errore. Riprova tra qualche minuto");
							console.log(dati[1]);
			      		}
			      		else if(dati[1] > 0){
			      			x.setStyle({color: "red"});
			                x.update("<br />Login gi&agrave; presente.");
			                return;
			     		}
			     		else{
			     			x.update("<img src='../../images/54.png' style='width: 15px; height: 15px; vertical-align: bottom' />");
			            	new_account = true;
			            	document.getElementById("account_button").onclick = account_wrapper;
			     		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

function reg(par){
    var id = <?php print $_REQUEST['id'] ?>;
    if(par == 1){
		var nick = document.forms[0].uname.value;
		var pwd =  document.forms[0].pwd.value;
		var url = "modifica_account.php";
		var req = new Ajax.Request(url,
				  {
				    	method:'post',
				    	parameters: {nick: nick, pwd: pwd, id: id},
				    	onSuccess: function(transport){
				      		var response = transport.responseText || "no response text";
				      		var dati = response.split("|");
				      		if(dati[0] == "ko"){
				      			alert("Non e` stato possibile modificare l'account per un errore del server. Riprova tra qualche minuto");
				      			console.log(dati[1]+"===>"+dati[2]);
				                return;
				     		}
				     		else{
				     			new_account = false;
								document.getElementById("account_button").onclick = no_change;
								var field = document.getElementById("account_field");
								var legend = document.getElementById("account_legend");
								field.style.border = "1px solid ";
								field.style.color = "#000000";
								_alert("Account modificato correttamente");
				     		}
				    	},
				    	onFailure: function(){ alert("Si e' verificato un errore..."); }
				  });
	}
}

function gen_pwd(){
	var pass = genera_password(document.forms[0].nome.value, document.forms[0].cognome.value, false, true);
	//alert(pass);
	document.forms[0].pwd.value = pass[0];
	$('pclear').value = pass[1];
	new_account = true;
	$("account_button").setAttribute("onclick", "account_wrapper()");
	$('account_button').show();
	var field = document.getElementById("account_field");
	var legend = document.getElementById("account_legend");
	field.style.border = "1px solid #ff0000";
	field.style.color = "#ff0000";
}

function gen_login(){
	if ($('useemail').checked){
		$('us').value = $F('email');
		return;
	}
	if((trim(document.forms[0].nome.value) == "") || (trim(document.forms[0].cognome.value) == "")){
		alert("inserisci nome e cognome per generare la username");
		return;
	}
	var nome = document.forms[0].nome.value;
	var cognome = document.forms[0].cognome.value;
	var url = "crea_login.php";
	var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {nome: nome, cognome: cognome},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split(":");
			      		if(dati[0] == "ko"){
			      			alert(dati[1]);
			                return;
			     		}
			     		else{
			     			document.forms[0].us.value = response;
			     		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore...") }
			  });
	
}

function no_change(){
	alert("Nessun dato modificato");
}

function account_wrapper(){
	reg(1);
}

function add_student(){
	_win = new Window({className: "mac_os_x", url: "elenco_alunni.php?school_order=<?php echo $_GET['school_order'] ?>", top: 100, left: 700, width:450, height:500, zIndex: 100, resizable: true, title: "Elenco alunni", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
	_win.showCenter(false);
}

function del(id){
	var campo = "al"+id;
	delete id_alunni[id];
	var stringa_nomi = document.getElementById("figli").innerHTML;
	stringa_nomi = "";
	stringa_nomi = crea_stringa_nomi(id_alunni);
	document.getElementById("figli").innerHTML = stringa_nomi;
	document.forms[0].id_figli.value = crea_stringa_id(id_alunni);
}

function deleteArrayElement(elem, myarray){
	var c = myarray.length;
   	for(var i = 0; i < c; i++){
   		if(myarray[i] == elem){
      		//alert("JS: Ho trovato "+elem);
			for(var x = i+1; x < c; x++){
         		myarray[i++] = myarray[x];
         	}
         	myarray.pop();   
   		}
	}
}

function crea_stringa_nomi(ar){
	str = "";
	for(chiave in ar){
		if(!isNaN(chiave)){
			str += "<a href='#' onclick='del("+chiave+")' id='al"+chiave+"'>"+ar[chiave]+"</a>, ";
			alert("ar["+chiave+"]="+ar[chiave]);
		}
	}
	if(str.endsWith(", "))
		return str.substr(0, (str.length - 3)); 
	//alert(str);
	return str;
}

function crea_stringa_id(ar){
	var _a = new Array;
	for(chiave in ar){
		if(!isNaN(chiave))
			_a.push(chiave);
	}	
	return _a.join();
}

function display_check(field){
	if(field.value != old_login){
		new_account = true;
		$('account_button').show();
	}
	else{
		if($F('pwd').empty()){
			new_account = false;
			$('account_button').hide();
		}
	}
}

var resend_email = function(genit){
	if((trim(document.forms[0].email.value) == "")){
		alert("inserisci un indirizzo email valido");
		return;
	}
	var email = document.forms[0].email.value;
	$('_i').setValue(genit);
	$('action').setValue(6);
	var url = "parent_manager.php";
	var req = new Ajax.Request(url,
		{
			method:'post',
			parameters: $('parent_form').serialize(true),
			onSuccess: function(transport){
				var response = transport.responseText || "no response text";
				var dati = response.split(":");
				if(dati[0] == "ko"){
					alert(dati[1]);
					return;
				}
				else{
					_alert("Mail inviata correttamente");
				}
			},
			onFailure: function(){ alert("Si e' verificato un errore...") }
		});
};
</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "../adm_users/menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Dettaglio genitore</div>
    <form action="dettaglio_genitore.php?upd=1" method="post" id="parent_form" class="popup_form">
    <fieldset id="account_field" style="width: 95%; border: 1px solid #BBB; padding-top: 10px; margin-left: auto; margin-right: auto; display: <?php if(!$show_account) echo "none" ?>">
    <legend id="account_legend" style="font-weight: bold;">Account</legend>
    <table style="width: 95%">
        <tr class="popup_row header_row">
            <td class="popup_title" style="width: 25%">Username</td>
            <td style="width: 50%">
                <input id="us" type="text" class="form_input" name="uname" onchange="display_check(this)" style="width: 90%" value="<?php if(isset($genitore)) print($genitore['username']) ?>" <?php if($_i == 0) print("readonly='readonly'")  ?> />
                <span id="check"></span>
            </td>
            <td style="width: 25%; text-aling: center">
                <?php if($_i != 0){ ?>
                <a href="#" onclick="verifica()" id="ver_us">Verifica username</a>
                <?php 
				}else{
                ?>
                <a href="#" onclick="gen_login()">Crea username</a>
                <?php } ?>
                <span id="verify"></span>
            </td>
        </tr>
        <tr class="popup_row">
            <td class="popup_title" style="width: 25%">Password</td>
            <td style="width: 50%">
                <input type="password" class="form_input" id="pwd" name="pwd" style="width: 90%" value="" readonly="readonly" />
            </td>
            <td style="width: 25%">
              <a href="#" onclick="gen_pwd()">Genera password</a>
            </td>
        </tr>
        <tr class="popup_row">
            <td colspan="2" style="height: 5px"></td>
            <td>
            	<?php if($_i != 0){ ?>
            	<a href="#" id="account_button" onclick="alert('Nessun dato modificato')" style="display: none">Registra account</a>
            	<?php } ?>
            </td>
        </tr>
    </table>
    </fieldset>
    <fieldset style="width: 95%; border: 1px solid #BBB; padding-top: 10px; margin-top: 30px; margin-left: auto; margin-right: auto">
    <legend style="font-weight: bold">Dati personali</legend>
    <table style="width: 95%">
        <tr class="popup_row header_row">
            <td class="popup_title" style="width: 25%">Nome *</td>
            <td colspan="2" style="width: 75%">
                <input type="text" name="nome" id="nome" class="form_input" style="width: 100%" autofocus value="<?php if(isset($genitore)) print($genitore['nome']) ?>" />
            </td>
        </tr>
        <tr class="popup_row">
            <td class="popup_title" style="width: 25%">Cognome *</td>
            <td colspan="2" style="width: 75%">
                <input type="text" name="cognome" id="cognome" class="form_input" style="width: 100%" value="<?php if(isset($genitore)) print($genitore['cognome']) ?>" />
            </td>
        </tr>
        <tr class="popup_row">
            <td class="popup_title" style="width: 25%">E-mail *</td>
            <td colspan="2" style="width: 75%">
                <input type="text" name="email" id="email" class="form_input" style="width: 100%" value="<?php if(isset($genitore)) print($genitore['email']) ?>" />
            </td>
        </tr>
        <?php if($_i == 0): ?>
        <tr class="popup_row">
            <td class="popup_title" style="width: 25%">Usa e-mail *</td>
            <td colspan="2" style="width: 75%">
                <input type="checkbox" name="useemail" id="useemail" class="form_input" style="" />
            </td>
        </tr>
        <?php endif; ?>
    	<tr class="popup_row">
            <td class="popup_title" style="width: 25%">Alunni *</td>
            <td style="width: 50%">
                <span id="figli" style=""><?php if (isset($figli)) echo $figli ?></span>
                <input type="hidden" name="action" id="action" />
    			<input type="hidden" name="_i" id="_i" />
    			<input type="hidden" name="gruppi" id="gruppi" value="4" />
    			<input type="hidden" name="alunni" id="alunni" value="<?php print $a ?>" />
    			<input type="hidden" name="pclear" id="pclear" />
	            <input type="hidden" name="school_order" id="school_order" value="<?php echo $_GET['school_order'] ?>" />
            </td>
            <td style="text-align: right; width: 25%"><a href="#" onclick="add_student()" class="standard_link">Aggiungi</a></td>
        </tr>
        <tr class="popup_row">
            <td colspan="3" style="height: 5px"><input type="hidden" name="id_figli" value="<?php if(count($id_figli) > 0){ print join(",", array_keys($id_figli));} ?>" /></td>
        </tr>
        
    </table>
    </fieldset>
     
    <div style="width: 95%;  margin-top: 30px; padding-bottom: 30px; text-align: right">
        <a href="#" onclick="go(<?php if(isset($_REQUEST['id']) && $_REQUEST['id'] != 0) print("3, ".$_REQUEST['id']); else print("1, 0"); ?>)" class="standard_link">Registra</a>
	    <?php if(isset($_REQUEST['id']) && $_REQUEST['id'] != 0): ?>
	    &nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" onclick="resend_email(<?php echo $_REQUEST['id'] ?>)" style="margin-right: 0px;" class="standard_link">Invia mail</a>
	    <?php endif; ?>
    </div>
   	</form>
	</div>
	<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
</body>
</html>