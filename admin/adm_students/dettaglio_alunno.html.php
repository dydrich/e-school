<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dettaglio alunno</title>
<link href="../../css/reg.css" rel="stylesheet" />
<link href="../../css/general.css" rel="stylesheet" />
<link href="../../css/skins/aqua/theme.css" type="text/css" rel="stylesheet"  />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/calendar.js"></script>
<script type="text/javascript" src="../../js/lang/calendar-it.js"></script>
<script type="text/javascript" src="../../js/calendar-setup.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript" src="../../js/md5-min.js"></script>
<script type="text/javascript">
var messages = new Array('', 'Alunno inserito con successo', 'Alunno cancellato con successo', 'Alunno modificato con successo', 'Account modificato con successo');
<?php
if($_i != 0){
	echo "var old_login = '{$alunno['username']}';\n";	
}
?>
function go(par, student){
    $('_i').setValue(student);
    $('action').setValue(par);
    var nick = $F('uname');
	var pwd =  $F('pwd');
	<?php if($type != 1){ ?>
	if(nick == "" || pwd == ""){
		alert("Username o password non presente");
		return false;
	}
	<?php } ?>
    var url = "student_manager.php";
    //alert(url);
	var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: $('st_form').serialize(true),
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("#");
			      		if(dati[0] == "kosql"){
				      		sqlalert();
							console.log("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
							return;
			      		}
			      		
			      		link = "alunni.php?msg="+par;
			      		if(par != 1){
							link += "&second=1&offset=<?php print $offset ?>";
			      		}

						_alert(messages[par]);
						if(par == 1){
							clean_form();
						}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore...");}
			  });
	  
}

var clean_form = function(){
	//alert("clean");
	$('uname').value = "";
	$('pwd').value = "";
	$('nome').value = "";
	$('cognome').value = "";
	$('cf').value = "";
	$('sel3').value = "";
	$('classe').options.selectedIndex = 0;
};

function verifica(){
    //alert("ok");
	var nick = $F('uname');
	if(nick == ""){
		alert("Username non presente");
		return false;
	}
	var url = "../../shared/verifica_login.php";
	var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {nick: nick},
			    	onSuccess: function(transport){
			    		$('check').update("");
			      		var response = transport.responseText || "no response text";
			      		if(response == "ko"){
			      			$('check').setStyle({color: "red"});
			                $('check').update("<br />Login gi&agrave; presente.");
			                return;
			     		}
			     		else{
			     			$('check').update("<img src='../../images/54.png' style='width: 15px; height: 15px; vertical-align: bottom' />");
			            	new_account = true;
			            	
			     		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

function reg(par){
    var id = <?php print $_REQUEST['id'] ?>;
    if(par == 1){
		var nick = $F('uname');
		var pwd =  $F('pwd');
		<?php if($type == 1){ ?>
		if(nick == "" || pwd == ""){
			alert("Username o password non presente");
			return false;
		}
		<?php } ?>
		var url = "student_manager.php";
		var req = new Ajax.Request(url,
				  {
				    	method:'post',
				    	parameters: {uname: nick, pwd: pwd, _i: id, action: 4},
				    	onSuccess: function(transport){
				    		$('check').update("");
				      		var response = transport.responseText || "no response text";
				      		var dati = response.split("#");
				      		if(dati[0] == "kosql"){
					      		sqlalert();
					      		console.log("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
				                return;
				     		}
				     		else{
				     			new_account = false;
				     			$('account_field').setStyle({border: '1px solid ', color: '#000000'});
				     			_alert(messages[4]);
					      	}
							
				    	},
				    	onFailure: function(){ alert("Si e' verificato un errore..."); }
				  });
	}
}

function gen_pwd(){
	var pass = genera_password($F('nome'), $F('cognome'), true, false);
	$('pwd').setValue(pass);
	new_account = true;
	$('account_field').setStyle({border: '1px solid #ff0000', color: '#ff0000'});
}

function gen_login(){
	if((trim($F('nome')) == "") || (trim($F('cognome')) == "")){
		alert("Inserisci nome e cognome per generare la username");
		return;
	}
	var nome = $F('nome');
	var cognome = $F('cognome');
	var url = "crea_login.php";
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {nome: nome, cognome: cognome},
			    	onSuccess: function(transport){
			    		//var x = document.getElementById("check");
			            $('check').update("");
			      		var response = transport.responseText || "no response text";
			      		if(response == "ko"){
			      			alert(response);
			                return;
			     		}
			     		else{
			     			$('uname').setValue(response);
			     		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

function no_change(){
	alert("Nessun dato modificato");
}

function account_wrapper(){
	reg(1);
}

document.observe("dom:loaded", function(){
	<?php if($_REQUEST['id'] == 0){ ?>
	$('gen_uname').observe("click", function(event){
		event.preventDefault();
		gen_login();
	});
	<?php } ?>
	<?php if($_REQUEST['id'] != 0 && $type != 1){ ?>
	
	$('verify').observe("click", function(event){
		event.preventDefault();
		verifica();
	});	
	<?php } ?>
	$('gen_pass').observe("click", function(event){
		event.preventDefault();
		gen_pwd();
	});
	$('uname').observe("blur", function(event){
		if(old_login != $F('uname')){
			new_account = true;
			
			$('account_field').setStyle({border: '1px solid #ff0000', color: '#ff0000'});
		}
	});
	$('save_button').observe("click", function(event){
		event.preventDefault();
		<?php 
		if(isset($_REQUEST['id']) && $_REQUEST['id'] != 0){
			if($type == 1){
				print("go(3, ".$_REQUEST['id'].")");
			}
			else{
				print("reg(1)");
			}
		} 
		else{
			print("go(1, 0)");
		} 
		?>

	});
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
		<div class="group_head">Dettaglio alunno</div>
    <form action="dettaglio_alunno.php?upd=1&offset=<?php print $offset ?>&order=<?php print $_REQUEST['order'] ?>" method="post" id="st_form" class="popup_form">
    <fieldset id="account_field" style="width: 95%; padding-top: 10px; margin-left: auto; margin-right: auto; <?php if($type == 1) echo "display: none" ?>">
    <legend id="account_legend" style="font-weight: bold;">Account</legend>
    <table style="width: 95%">
        <tr class="popup_row header_row">
            <td class="popup_title" style="width: 25%">UserName *</td>
            <td style="width: 50%">
                <input class="form_input" type="text" id="uname" name="uname" style="width: 80%" value="<?php if(isset($alunno)) print($alunno['username']) ?>" <?php if($_i == 0) print("readonly='readonly'")  ?> />
                <span id="check"></span>
            </td>
            <td style="width: 25%; text-align: center">
                <!-- <input type="button" onclick="verifica()" value="Verifica username" style="border: 1px solid; float: right; display: inline" />  -->
                <?php if($_i != 0){ ?>
                <a href="../../shared/no_js.php" id="verify">Verifica username</a>
                <?php 
				}else{
                ?>
                <a href="../../shared/no_js.php" id="gen_uname">Crea username</a>
                <?php } ?>
                <span id="verify"></span>
            </td>
        </tr>
        <tr class="popup_row">
            <td class="popup_title" style="width: 25%">Password *</td>
            <td style="width: 50%">
                <input type="password" id="pwd" name="pwd" style="width: 80%" class="form_input" value="" readonly="readonly" />
            </td>
            <td style="width: 25%; text-align: center">
              <!--   <input type="button" onclick="gen_pwd('<?php print $alunno['nome'] ?>', '<?php print $alunno['cognome'] ?>')" value="Genera  Password" style="border: 1px solid; float: right; display: inline" /> -->
              <a href="../../shared/no_js.php" id="gen_pass">Genera password</a>
            </td>
        </tr>
        <tr class="popup_row">
            <td colspan="2" style="height: 5px"></td>
            <td>
            	
            </td>
        </tr>
    </table>
    </fieldset>
    <fieldset style="width: 95%; padding-top: 10px; margin-top: 30px; margin-left: auto; margin-right: auto; <?php if($type == 2) echo "display: none" ?>">
    <legend style="font-weight: bold">Dati personali</legend>
    <table style="width: 95%">
        <tr class="popup_row header_row">
            <td class="popup_title" style="width: 30%">Nome *</td>
            <td colspan="2" style="width: 70%">
                <input class="form_input" type="text" id="nome" name="nome" style="width: 100%" value="<?php if(isset($alunno)) print($alunno['nome']) ?>" />
            </td>
        </tr>
        <tr class="popup_row">
            <td class="popup_title" style="width: 30%">Cognome *</td>
            <td colspan="2" style="width: 70%">
                <input class="form_input" type="text" id="cognome" name="cognome" style="width: 100%" value="<?php if(isset($alunno)) print($alunno['cognome']) ?>" />
            </td>
        </tr>
        <tr class="popup_row">
            <td class="popup_title" style="width: 30%">Codice fiscale</td>
            <td colspan="2" style="width: 70%">
                <input class="form_input" type="text" name="cf" id="cf" style="width: 34%" value="<?php if(isset($alunno)) print($alunno['codice_fiscale']) ?>" style="width: 380px; font-size: 11px" />
            	<div style="float: right; width: 59%; text-align: right">
            	<span class="popup_title" style="padding-right: 5px">Sesso *</span>
            	<select class="form_input" name="sesso" style="width: 70%">
            		<option value="M" <?php if(isset($alunno) && ($alunno['sesso'] == "M")) print("selected='selected'") ?>>Maschio</option>
            		<option value="F" <?php if(isset($alunno) && ($alunno['sesso'] == "F")) print("selected='selected'") ?>>Femmina</option>
            	</select>
            	</div>
            </td>
        </tr>
        <tr class="popup_row">
            <td class="popup_title" style="width: 30%">Data di nascita </td>
            <td colspan="2" style="width: 70%">
                <input class="form_input" type="text" id="sel3" name="data_nascita" style="width: 34%" value="<?php if(isset($alunno)) print(format_date($alunno['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/")) ?>" />
                <script type="text/javascript">
                <?php 
                if(isset($alunno) && $alunno['data_nascita'] != ""){
                	list($y, $m, $d) = explode("-", $alunno['data_nascita']);
                	$m--;
                }
                ?>
	            Calendar.setup({
	                date		: new Date(<?php if(isset($alunno) && $alunno['data_nascita'] != "") print("$y, $m, $d") ?>),
					inputField	: "sel3",
					ifFormat	: "%d/%m/%Y",
					showsTime	: false,
					firstDay	: 1,
					timeFormat	: "24"					
				});
	        	</script>
	        	<div style="float: right; width: 64%; text-align: right">
                <span class="popup_title" style="padding-right: 5px">Classe *</span>
            	<select class="form_input" name="classe" id="classe" style="width: 64%; margin-left: 1px">
            		<option value="all">Scegli una classe</option>
            	<?php
				while($cls = $res_classi->fetch_assoc()){
					$class_string = $cls['anno_corso'].$cls['sezione']." (";
					if($classes_table == "rb_classi"){
						$class_string .= $cls['codice']." - ";
					}
					$class_string .= $cls['nome'].")";
            	?>
            		<option <?php if($cls['id_classe'] == $alunno['id_classe']) print("selected='selected'") ?> value="<?php print $cls['id_classe'].";".$cls['anno_corso'].$cls['sezione'] ?>"><?php echo $class_string ?></option>
            	<?php
				}
            	?>
            	</select>
            	</div>
            </td>
        </tr>
        <tr class="popup_row">
            <td class="popup_title" style="width: 30%">Luogo di nascita *</td>
            <td colspan="2" style="width: 70%">
                <input class="form_input" type="text" id="luogo" name="luogo" style="width: 100%" value="<?php if(isset($alunno)) print($alunno['luogo_nascita']) ?>" />
            </td>
        </tr>
        <tr class="popup_row">
            <td colspan="3" style="height: 1px">
            	<input type="hidden" name="action" id="action" />
    			<input type="hidden" name="_i" id="_i" />
    			<input type="hidden" name="old_class" id="old_class" value="<?php echo $old_class ?>" />
            </td>
        </tr>       
    </table>
    </fieldset> 
    <div style="width: 95%;  margin: 30px auto 0 auto; padding-bottom: 20px; text-align: right">
        <a href="../../shared/no_js.php" id="save_button" class="standard_link nav_link">Registra</a>
    </div>
   	</form>
   	</div>
	<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
</body>
</html>
