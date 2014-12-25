<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Dettaglio utente</title>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript" src="../../js/md5-min.js"></script>
	<script type="text/javaScript">
	var go = function(par, user){
	    if(par == 1){
	        if($('#pwd').val() != $('#pwd2').val()){
	            alert("Le password inserite sono differenti. Ricontrolla.");
	            return false;
	        }
	        $('#pwd').val(hex_md5($('#pwd').val()));
	        // fake password
	        $('#pwd2').val("calatafimi");
	    }
	    $('#_i').val(user);
	    $('#action').val(par);
	    var url = "users_manager.php";

		$.ajax({
			type: "POST",
			url: url,
			data: $('#user_form').serialize(true),
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
				}
				else {
					j_alert("alert", json.message);
				}
			}
		});
	};

	$(function(){
		load_jalert();
		setOverlayEvent();
		$('.form_input').focus(function(event){
			$(this).css({outline: '1px solid blue'});
		});
		$('.form_input').blur(function(event){
			$(this).css({outline: ''});
		});
		$('#save_button').button();
		$('#save_button').click(function(event){
			event.preventDefault();
			go(<?php if(isset($_REQUEST['id']) && $_REQUEST['id'] != 0) print("3, ".$_REQUEST['id']); else print("1, 0"); ?>);
		});
		<?php if(isset($_REQUEST['id']) && $_REQUEST['id'] != 0){
		?>
		$('#del_button').click(function(event){
			event.preventDefault();
			go(2, <?php print $_REQUEST['id'] ?>);
		});
		<?php
		}
		?>
	});

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
		<div style="position: absolute; top: 75px; margin-left: 625px; margin-bottom: -5px" class="rb_button">
			<a href="<?php echo $back_link ?>">
				<img src="../../images/47bis.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<form action="users_manager.php" method="post" id="user_form" class="popup_form no_border" style="width: 90%">
	    <div style="text-align: left; width: 100%; margin: auto; ">
	    <fieldset style="margin-right: auto; margin-left: auto; margin-bottom: 20px; padding-bottom: 20px; width: 95%; padding-top: 10px; <?php if(isset($_GET['id']) && $_GET['id'] != 0) print('display: none') ?>">
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
	    <fieldset style="width: 95%; padding-top: 10px; margin: auto; padding-bottom: 20px; ">
	    <legend>Dati anagrafici</legend>
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
	    <fieldset style="margin-right: auto; margin-left: auto; width: 95%; padding-top: 20px; padding-bottom: 20px; margin-bottom: 30px; margin-top: 20px">
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
	    <div style="width: 99%; margin-right: 0px; text-align: right">
		    <button id="save_button">Registra</button>
	    </div>
		</form>
	</div>
	<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="../../index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="../index.php"><img src="../../images/31.png" style="margin-right: 10px; position: relative; top: 5%" />Admin</a></div>
		<div class="drawer_link"><a href="http://www.istitutoiglesiasserraperdosa.it"><img src="../../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
