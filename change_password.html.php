<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
	<link rel="stylesheet" href="css/site_themes/<?php echo getTheme() ?>/index.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="css/themes/default.css" type="text/css"/>
	<link rel="stylesheet" href="css/themes/alphacube.css" type="text/css"/>
	<script type="text/javascript" src="js/prototype.js"></script>
	<script type="text/javascript" src="js/scriptaculous.js"></script>
	<script type="text/javascript" src="js/page.js"></script>
	<script type="text/javascript" src="js/md5-min.js"></script>
	<script type="text/javascript" src="js/window.js"></script>
	<script type="text/javascript" src="js/window_effects.js"></script>
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

			var req = new Ajax.Request('password_manager.php',
				{
					method:'post',
					parameters: {new_pwd: p, action: "change", area: <?php echo $area ?>, uid: <?php echo $uid ?>},
					onSuccess: function(transport){
						var response = transport.responseText || "no response text";
						var json = response.evalJSON();
						if (json.status == "ok"){
							_alert("Password modificata correttamente");
							setTimeout(function(){window.location = "index.php"}, 4000);
						}
					},
					onFailure: function(){ alert("Si e' verificato un errore..."); }
				});
		}
	</script>
</head>
<body>
<body>
<header id="header">
	<div class="wrap">
		<div style="" id="_header">
			<?php echo stripslashes($_SESSION['__config__']['intestazione_scuola']) ?><br />
			<p style="font-size: 0.7em; font-weight: normal; line-height: 20px; margin: 0; padding-top: 10px; text-transform: none">
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
					<input type='password' autofocus name='pwd1' id='pwd1' style='width: 90%; color: #EEEEEE; border: 2px solid #FFFFFF; border-radius: 5px; box-shadow: 0 0 5px #FFFFFF; background-color:transparent; font-size: 1.5em; margin: auto' />
				</div>
				<div style='width: 45%; float: left; text-align: center; color: #FFFFFF; font-size: 1.15em; text-transform: uppercase; text-shadow: 0 0 5px #FFFFFF; padding-top: 7px'>Inserisci password</div>
			</div>
			<div id='r2' style='clear: left; margin: 0 auto 0 auto; height: 60px;'>
				<div style='width: 50%; float: left; text-align: right'>
					<input type='password' name='pwd2' id='pwd2' style='width: 90%; color: #EEEEEE; border: 2px solid #FFFFFF; border-radius: 5px; box-shadow: 0 0 5px #FFFFFF; background-color:transparent; font-size: 1.5em; margin: auto' />
				</div>
				<div style='width: 45%; float: left; text-align: center; color: #FFFFFF; font-size: 1.15em; text-transform: uppercase; text-shadow: 0 0 5px #FFFFFF; padding-top: 7px'>Ripeti password</div>
			</div>
			<div id='r3' style='clear: left; height: 120px; text-align: center'>
				<input type='button' onclick='registra()' style='width: 90px; heigth: 45px; border: 2px solid #FFFFFF; border-radius: 5px; box-shadow: 0 0 5px #FFFFFF; background-color: transparent; color: #EEEEEE; font-size: 1.15em; margin-top: 20px' value='INVIA' />
			</div>

		<?php
		else :
		?>
			<div id='t1' style='clear:left; width: 90%; margin: 40px auto 40px auto; height: 60px; color: #FFFFFF'>
				<p style='padding: 10px; font-size: 1.5em; margin: auto; text-align: center'>L'indirizzo inserito non e` piu` valido. Devi effettuare una nuova richiesta. <br>
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
</body>
</html>
