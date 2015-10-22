<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
	<script type="text/javascript" src="js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="./js/page.js"></script>
	<script type="text/javascript" src="./js/md5-min.js"></script>
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
	<link href="css/general.css" rel="stylesheet" type="text/css"/>
	<link href="css/site_themes/light_blue/index.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="css/site_themes/light_blue/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript">
		function registra(){
			var patt = /[^a-zA-Z0-9]/;
			if(trim(document.forms[0].pwd1.value) == ""){
				alert("Password non valida.");
				return false;
			}
			else if(document.forms[0].pwd1.value.match(patt)){
				alert("Password non valida: sono ammessi solo lettere e numeri");
				return false;
			}
			if(trim(document.forms[0].pwd1.value) != trim(document.forms[0].pwd2.value)){
				alert("Le password inserite sono differenti. Ricontrolla.");
				return false;
			}
			p = hex_md5(document.forms[0].pwd1.value);

			$.ajax({
				type: "POST",
				url: 'password_manager.php',
				data: {new_pwd: p, action: "change", area: <?php echo $area ?>, uid: <?php echo $uid ?>},
				dataType: 'json',
				error: function() {
					console.log(json.dbg_message);
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
						console.log(json.dbg_message);
						console.log(json.query);
						j_alert("error", json.message);
					}
					else {
						j_alert("alert", json.message);
						setTimeout(
							function(){
								window.location = "index.php";
							},
							2000
						);
					}
				}
			});
		}
	</script>
</head>
<body>
	<header>
		<div class="wrap">
			<div style="" id="_header">
				<?php echo stripslashes($_SESSION['__config__']['intestazione_scuola']) ?><br />
				<p id="sw_version" style="font-size: 0.7em; font-weight: normal; line-height: 20px; margin: 0; padding-top: 10px; text-transform: none">
					<?php echo $_SESSION['__config__']['software_name']." ".$_SESSION['__config__']['software_version'] ?> - Registro elettronico<span id="area"></span>
				</p>
			</div>
		</div>
	</header>
<section class="wrap">
	<div id="login_form" style="">
		<form id='myform' method='post' action='#'>
		<?php
		if ($token != null):
		?>
			<div id='r1' style='margin: 40px auto 0 auto; height: 60px; '>
				<div style='width: 50%; float: left; text-align: right'>
					<input type='password' autofocus name='pwd1' id='pwd1' style='width: 90%' />
				</div>
				<div style='width: 45%; float: left; text-align: center; font-size: 1.15em; text-transform: uppercase; text-shadow: 0 0 5px #FFFFFF; padding-top: 7px'>Inserisci password</div>
			</div>
			<div id='r2' style='clear: left; margin: 0 auto 0 auto; height: 60px;'>
				<div style='width: 50%; float: left; text-align: right'>
					<input type='password' name='pwd2' id='pwd2' style='width: 90%; ' />
				</div>
				<div style='width: 45%; float: left; text-align: center; font-size: 1.15em; text-transform: uppercase; text-shadow: 0 0 5px #FFFFFF; padding-top: 7px'>Ripeti password</div>
			</div>
			<div id='r3' style='clear: left; height: 120px; text-align: center'>
				<input type='button' id="mail_button" onclick='registra()' style="" value='INVIA' />
			</div>

		<?php
		else :
		?>
			<div id='t1' style='clear:left; width: 90%; margin: 40px auto 40px auto; height: 60px; color: #FFFFFF'>
				<p style='padding: 10px; font-size: 1.5em; margin: auto; text-align: center'>L'indirizzo inserito non &egrave; pi&ugrave; valido. Devi effettuare una nuova richiesta. <br>
				Ti ricordiamo che la password va cambiata entro 24 ore dalla richiesta.</p>
			</div>
			<div id='r3' style='clear: left; height: 80px; text-align: center'>
				<input type='button' onclick='document.location.href="index.php"' style='width: 90px; heigth: 45px; border: 2px solid #FFFFFF; border-radius: 5px; box-shadow: 0 0 5px #FFFFFF; background-color: transparent; color: #EEEEEE; font-size: 1.15em; margin-top: 20px' value='HOME' />
			</div>
		<?php
		endif;
		?>
		</form>
	</div>
</section>
	<div id="alert" class="alert_msg" style="display: none">
		<div class="alert_title">
			<i class="fa fa-thumbs-up"></i>
			<span>Successo</span>
		</div>
		<p id="alertmessage" class="alertmessage"></p>
	</div>
	<div id="error" class="error_msg" style="display: none">
		<div class="error_title">
			<i class="fa fa-warning"></i>
			<span>Errore</span>
		</div>
		<p class="errormessage" id="errormessage"></p>
	</div>
	<div id='background_msg' style='width: 200px; text-align: center; font-size: 12px; font-weight: bold; padding-top: 30px; margin: auto'></div>
	<div class="overlay" id="overlay" style="display:none;"></div>
</body>
</html>
