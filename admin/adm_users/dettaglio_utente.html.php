<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dettaglio utente</title>
<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
<link href="../../css/general.css" rel="stylesheet" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/md5-min.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javaScript">
var messages = new Array('', 'Utente inserito con successo', 'Utente cancellato con successo', 'Utente modificato con successo');
var timeout;
var msg;
function go(par, user){
    if(par == 1){
    	if(trim($F('pwd')) != trim($F('pwd2'))){
    		alert("Le password inserite sono differenti. Ricontrolla.");
    		return false;
    	}
    	$('pwd').setValue(hex_md5($F('pwd')));
    	// fake password
    	$('pwd2').setValue("calatafimi");
    }
    $('_i').setValue(user);
    $('action').setValue(par);
    var url = "users_manager.php";
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: $('user_form').serialize(true),
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		if(dati[0] == "ko"){
							alert("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
							parent.win.close();
							return;
			      		}
			      		
			      		link = "users.php?msg="+par;
			      		if(par != 1){
							link += "&second=1&offset=<?php print $offset ?>";
			      		}
						_alert(messages[par]);						
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

<?php include "../popup_dom.php" ?>
	 
</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Dettaglio utente</div>
	    <form action="users_manager.php" method="post" id="user_form" class="popup_form" style="width: 90%">
	    <div style="text-align: left; width: 100%; margin: auto; ">
	    <fieldset style="background: #f4f4f4; margin-right: auto; margin-left: auto; margin-bottom: 20px; padding-bottom: 20px; width: 95%; border: 1px solid #BBB; padding-top: 10px; <?php if(isset($_GET['id']) && $_GET['id'] != 0) print('display: none') ?>">
	    <legend>Account</legend>
	    <table style="margin: auto; width: 95%">
	        <tr class="popup_row header_row">
	            <td style="width: 30%"><label for="uname" class="popup_title">UserName</label></td>
	            <td style="width: 70%">
	                <input class="form_input" type="text" name="uname" style="width: 100%" value="" <?php if(isset($user)) print("readonly='readonly'") ?> />
	            </td>
	        </tr>
	        <tr class="popup_row">
	            <td style="width: 30%"><label for="pwd" class="popup_title">Password</label></td>
	            <td style="width: 70%">
	                <input class="form_input" type="password" id="pwd" name="pwd" style="width: 100%" value="" <?php if(isset($user)) print("readonly='readonly'") ?> />
	            </td>
	        </tr>
	        <tr class="popup_row">
	            <td style="width: 30%"><label for="pwd2" class="popup_title">Ripeti Password</label></td>
	            <td style="width: 70%">
	                <input class="form_input" type="password" id="pwd2" name="pwd2" style="width: 100%" value="" <?php if(isset($user)) print("readonly='readonly'") ?> />
	            </td>
	        </tr>
	    </table>
	    </fieldset>
	    <fieldset style="background: #f4f4f4; width: 95%; border: 1px solid #BBB; padding-top: 10px; margin: auto; padding-bottom: 20px; ">
	    <legend>Dati personali</legend>
	    <table style="width: 95%; margin: auto">
	        <tr class="popup_row header_row">
	            <td style="width: 30%"><label for="nome" class="popup_title">Nome</label></td>
	            <td style="width: 70%">
	                <input class="form_input" type="text" name="nome" style="width: 100%" value="<?php if(isset($user)) print($user['nome']) ?>" />
	            </td>
	        </tr>
	        <tr class="popup_row">
	            <td style="width: 30%"><label for="cognome" class="popup_title">Cognome</label></td>
	            <td style="width: 70%">
	                <input class="form_input" type="text" name="cognome" style="width: 100%" value="<?php if(isset($user)) print($user['cognome']) ?>" />
	            </td>
	        </tr>
	    </table>
	    </fieldset>
	    <fieldset style="background: #f4f4f4; margin-right: auto; margin-left: auto; width: 95%; border: 1px solid #BBB; padding-top: 20px; padding-bottom: 20px; margin-bottom: 30px; margin-top: 20px">
	    <legend style="">Gruppi</legend>
	    <table style="width: 95%">
	        <tr class="popup_row header_row _center">
	            <td colspan="2">
	                <?php
	                while($gr = $res_g->fetch_assoc()){
	                    $checked = "";
	                    if(isset($_GET['id']) && $_GET['id'] != 0){
	                        if(in_array($gr['gid'], $gid)) {
	                            $checked = "checked";
	                        }
	                    }
	                    else {
	                    	if(basename($_SERVER['HTTP_REFERER']) == "genitori.php") {
	                    		if($gr['gid'] == 4) {
	                    			$checked = "checked";
	                    		}
	                    	}
	                    }
	                ?>
	                <input type="checkbox" style="margin: auto" value="<?php print $gr['gid'] ?>" name="gruppi[]" <?php print $checked ?> />&nbsp;&nbsp;&nbsp;<?php print $gr['nome'] ?>&nbsp;&nbsp;&nbsp;
	                <?php } ?>
	                <input type="hidden" name="action" id="action" />
	    			<input type="hidden" name="_i" id="_i" />
	            </td>
	        </tr>
	    </table>
	    </fieldset>
	    </div>
	    <div style="width: 95%; margin-right: 0px; text-align: right">
	        <a href="../../shared/no_js.php" id="save_button" class="standard_link nav_link">Registra</a>
	    </div>
		</form>
	</div>
	<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
</body>
</html>
