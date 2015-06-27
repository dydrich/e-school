<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Dettaglio alunno</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link href="../../css/general.css" rel="stylesheet" />
	<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript" src="../../js/md5-min.js"></script>
	<script type="text/javascript">
		var messages = new Array('', 'Alunno inserito con successo', 'Alunno cancellato con successo', 'Alunno modificato con successo', 'Account modificato con successo');
		<?php
		if($_i != 0){
			echo "var old_login = '{$alunno['username']}';\n";
		}
		?>
		var go = function(par, student){
		    $('#_i').val(student);
		    $('#action').val(par);
		    var nick = $('#uname').val();
			var pwd =  $('#pwd').val();
			<?php if($type != 1){ ?>
			if(nick == "" || pwd == ""){
				alert("Username o password non presente");
				return false;
			}
			<?php } ?>
		    var url = "student_manager.php";
		    //alert(url);
			$.ajax({
				type: "POST",
				url: url,
				data: $('#st_form').serialize(true),
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

		var clean_form = function(){
			//alert("clean");
			$('#uname').val("");
			$('#pwd').val("");
			$('#nome').val("");
			$('#cognome').val("");
			$('#cf').val("");
			$('#sel3').val("");
			$('#classe').options.selectedIndex = 0;
		};

		var reg = function(par){
		    var id = <?php print $_REQUEST['id'] ?>;
		    if(par == 1){
				var nick = $('#uname').val();
				var pwd =  $('#pwd').val();
				<?php if($type == 1){ ?>
				if(nick == "" || pwd == ""){
					alert("Username o password non presente");
					return false;
				}
				<?php } ?>
				var url = "student_manager.php";
			    $.ajax({
				    type: "POST",
				    url: url,
				    data: {uname: nick, pwd: pwd, _i: id, action: 4},
				    dataType: 'json',
				    error: function() {
					    j_alert("error", "Errore di trasmissione dei dati");
				    },
				    succes: function() {

				    },
				    complete: function(data){
					    $('#check').text("");
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
						    new_account = false;
						    $('#account_field').css({border: '1px solid ', color: '#000000'});
					    }
				    }
			    });
			}
		};

		var gen_pwd = function(){
			pass = genera_password('<?php echo $_SESSION['__path_to_root__'] ?>', false, true);
			passws = pass.split(";");
			$('#pwd').val(passws[0]);
			//$('#pclear').val(passws[1]);
			new_account = true;
			$('#account_field').css({border: "1px solid #ff0000", color: "#ff0000"});
		};

		var gen_login = function(){
			if(($('#nome').val() == "") || ($('#cognome').val() == "")){
				alert("Inserisci nome e cognome per generare la username");
				return;
			}
			var nome = $('#nome').val();
			var cognome = $('#cognome').val();
			var url = "../../shared/account_manager.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {nome: nome, cognome: cognome, action: "get_student_login"},
				dataType: 'json',
				error: function() {
					j_alert("error", "Errore di trasmissione dei dati");
				},
				succes: function() {

				},
				complete: function(data){
					$('#check').text("");
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
						$('#uname').val(json.login);
					}
				}
			});
		};

		var no_change = function(){
			alert("Nessun dato modificato");
		};

		var account_wrapper = function(){
			reg(1);
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#sel3').datepicker({
				dateFormat: "dd/mm/yy",
				changeYear: true,
				changeMonth: true,
				yearRange: "1990:<?php echo date("Y") ?>"
			})
			<?php if($_REQUEST['id'] == 0){ ?>
			$('#gen_uname').click(function(event){
				event.preventDefault();
				gen_login();
			});
			<?php } ?>
			<?php if($_REQUEST['id'] != 0 && $type != 1){ ?>

			$('#verify').click(function(event){
				event.preventDefault();
				verifica();
			});
			<?php } ?>
			$('#gen_pass').click(function(event){
				event.preventDefault();
				gen_pwd();
			});
			$('#uname').blur(function(event){
				if(old_login != $('#uname').val){
					new_account = true;

					$('#account_field').css({border: '1px solid #ff0000', color: '#ff0000'});
				}
			});
			$('#save_button').button();
			$('#save_button').click(function(event){
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
	<style>
		.ui-datepicker-month {color: white}
		.ui-datepicker-year {color: white}
	</style>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include $menu ?>
	</div>
	<div id="left_col">
		<div style="position: absolute; top: 75px; margin-left: 625px; margin-bottom: -5px" class="rb_button">
			<a href="<?php echo $back_link ?>">
				<img src="../../images/47bis.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
    <form action="dettaglio_alunno.php?upd=1&offset=<?php print $offset ?>&order=<?php if(isset($_REQUEST['order'])) echo $_REQUEST['order'] ?>" method="post" id="st_form" style="width: 75%" class="no_border">
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
            		<option <?php if (isset($alunno) && $cls['id_classe'] == $alunno['id_classe']) print("selected='selected'") ?> value="<?php print $cls['id_classe'].";".$cls['anno_corso'].$cls['sezione'] ?>"><?php echo $class_string ?></option>
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
    <div style="width: 98%;  margin: 30px auto 0 auto; padding-bottom: 20px; text-align: right">
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
