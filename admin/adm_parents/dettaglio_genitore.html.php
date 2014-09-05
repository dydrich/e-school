<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dettaglio genitore</title>
<link href="../../css/site_themes/blue_red/reg.css" rel="stylesheet" />
<link href="../../css/general.css" rel="stylesheet" />
<link rel="stylesheet" href="../../css/jquery/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/md5-min.js"></script>
<script type="text/javascript">
var messages = new Array('', 'Genitore inserito con successo', 'Genitore cancellato con successo', 'Genitore modificato con successo');
var new_account = false;
var new_data = false;
var oldLink = null;

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

var go = function(par, genit){
    $('#_i').val(genit);
    $('#action').val(par);
    var url = "parent_manager.php";
    $.ajax({
		type: "POST",
		url: url,
		data: $('#parent_form').serialize(true),
		dataType: 'json',
		error: function() {
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
				j_alert("error", json.message);
				console.log(json.dbg_message);
				return;
			}
			else if (json.status == "ko"){
				j_alert("error", json.message);
				return;
			}
			else {
				j_alert("alert", json.message);
				setTimeout("document.location.href='dettaglio_genitore.php?id='+$('#_i').val()+'&school_order=<?php echo $school_order ?>'", 2000);
			}
		}
	});
};

var reset_form = function(){
	$('#us').val("");
	$('#pwd').val("");
	$('#nome').val("");
	$('#cognome').val("");
	$('#email').val("");
};

var reg = function(par){
    var id = <?php print $_REQUEST['id'] ?>;
    if(par == 1){
		var nick = $('#uname').val();
		var pwd =  $('#pwd').val();
		var url = "../../shared/account_manager.php";
	    $.ajax({
		    type: "POST",
		    url: url,
		    data: {nick: nick, pwd: pwd, id: id},
		    dataType: 'json',
		    error: function() {
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
				    j_alert("error", json.message);
				    console.log(json.dbg_message);
				    return;
			    }
			    else if (json.status == "ko"){
				    j_alert("error", json.message);
				    return;
			    }
			    else {
				    new_account = false;
				    $('#account_button').click(function(){
					    no_change();
				    });
				    $('#account_field').css({border: "1px solid black"});
				    j_alert("alert", json.message);
			    }
		    }
	    });
    }
};

var gen_pwd = function(){
	pass = genera_password('<?php echo $_SESSION['__path_to_root__'] ?>', false, true);
	passws = pass.split(";");
	$('#pwd').val(passws[0]);
	$('#pclear').val(passws[1]);
	new_account = true;
	$('#account_button').click(function(){
		reg(1);
	});
	$('#account_button').show();
	var field = document.getElementById("account_field");
	var legend = document.getElementById("account_legend");
	$('#account_field').css({border: "1px solid #ff0000", color: "#ff0000"});
};

var gen_login = function(){
	if ($('#useemail').is(":checked")){
		$('#us').val($('#email').val());
		return;
	}
	if((trim(document.forms[0].nome.value) == "") || (trim(document.forms[0].cognome.value) == "")){
		alert("inserisci nome e cognome per generare la username");
		return;
	}
	var nome = $('#nome').val();
	var cognome = $('#cognome').val();
	var url = "../../shared/account_manager.php";
	$.ajax({
		type: "POST",
		url: url,
		data: {nome: nome, cognome: cognome, action: "get_user_login"},
		dataType: 'json',
		error: function() {
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
				j_alert("error", json.message);
				console.log(json.dbg_message);
				return;
			}
			else if (json.status == "ko"){
				j_alert("error", json.message);
				return;
			}
			else {
				$('#us').val(json.login);
			}
		}
	});
};

var no_change = function(){
	alert("Nessun dato modificato");
};

var add_student = function(){
	$('#list').dialog({
		autoOpen: true,
		show: {
			effect: "fadeIn",
			duration: 500
		},
		hide: {
			effect: "fadeOut",
			duration: 300
		},
		modal: true,
		width: 470,
		title: 'Elenco alunni',
		open: function(event, ui){

		}
	});
};

var dialogclose = function(){
	$('#list').dialog("close");
};

var del = function(id){
	var campo = "al"+id;
	delete id_alunni[id];
	var stringa_nomi = document.getElementById("figli").innerHTML;
	stringa_nomi = "";
	stringa_nomi = crea_stringa_nomi(id_alunni);
	document.getElementById("figli").innerHTML = stringa_nomi;
	document.forms[0].id_figli.value = crea_stringa_id(id_alunni);
};

var crea_stringa_nomi = function(ar){
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
};

var crea_stringa_id = function(ar){
	var _a = new Array;
	for(chiave in ar){
		if(!isNaN(chiave))
			_a.push(chiave);
	}	
	return _a.join();
};

var display_check = function(field){
	if(field.value != old_login){
		new_account = true;
		$('#account_button').show();
	}
	else{
		if($('#pwd').val() == ""){
			new_account = false;
			$('#account_button').hide();
		}
	}
};

var resend_email = function(genit){
	if((trim(document.forms[0].email.value) == "")){
		alert("inserisci un indirizzo email valido");
		return;
	}
	var email = document.forms[0].email.value;
	$('#_i').val(genit);
	$('#action').val(6);
	var url = "parent_manager.php";
	$.ajax({
		type: "POST",
		url: url,
		data: $('#parent_form').serialize(true),
		dataType: 'json',
		error: function() {
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
				j_alert("error", json.message);
				console.log(json.dbg_message);
				return;
			}
			else if (json.status == "ko"){
				j_alert("error", json.message);
				return;
			}
			else {
				j_alert("alert", "Mail inviata correttamente");
			}
		}
	});
};

$(function(){
	load_jalert();
});
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
    <fieldset id="account_field" style="width: 95%; padding-top: 10px; margin-left: auto; margin-right: auto; display: <?php if(!$show_account) echo "none" ?>">
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
    <fieldset style="width: 95%; padding-top: 10px; margin-top: 30px; margin-left: auto; margin-right: auto">
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
    			<input type="hidden" name="alunni" id="alunni" value="" />
    			<input type="hidden" name="pclear" id="pclear" />
	            <input type="hidden" name="school_order" id="school_order" value="<?php echo $_GET['school_order'] ?>" />
            </td>
            <td style="text-align: right; width: 25%"><a href="#" onclick="add_student()" class="standard_link">Aggiungi</a></td>
        </tr>
        <tr class="popup_row">
            <td colspan="3" style="height: 5px"><input type="hidden" name="id_figli" value="<?php if(isset($id_figli) && count($id_figli) > 0){ print join(",", array_keys($id_figli));} ?>" /></td>
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
<div id="list" style="display: none">
	<iframe id="iframe" src="elenco_alunni.php?school_order=<?php echo $_GET['school_order'] ?>" style="width: 450px; height: 500px; border: 0; margin: auto"></iframe>
</div>
<?php include "../footer.php" ?>
</body>
</html>
