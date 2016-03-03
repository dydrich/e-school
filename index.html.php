<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Registro elettronico::login</title>
	<script type="text/javascript" src="js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="./js/page.js"></script>
	<script type="text/javascript" src="./js/md5-min.js"></script>
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
	<link href="css/general.css" rel="stylesheet" type="text/css"/>
	<link href="css/site_themes/light_blue/index.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="css/site_themes/light_blue/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript">
		var area = "";
		var colors = Array();
<?php
$i = 0;
foreach ($colors as $color) {
?>
		colors[<?php echo $i ?>] = '<?php echo $color ?>';
<?php
	$i++;
}
?>

		var check_msie = function(){
			if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)){
				alert("Stai usando Internet Explorer: il funzionamento del sito non è garantito con questo browser. Ti consigliamo di utilizzare Firefox o Chrome");
				return false;
			}
			return true;
		};

		/*
		 * funzione di login
		 */
		var do_login = function(type){
			var nick = $('#nick').val();
			var pwd = $('#pass').val();
			if (nick == "" || pwd == "") {
				j_alert("error", "Non hai inserito i dati necessari");
				return;
			}
			var pass = hex_md5(pwd);

			var url = "do_login.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {nick: nick, pass: pass, param: type},
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
					else if(json.status == "ko"){
						j_alert("error", json.message);
						setTimeout(function() {
							document.location.href = "index.php";
						}, 2000);
					}
					else if (json.status == "manutention") {
						document.location = "manutention.php";
						return false;
					}
					else {
						$('#login_form').fadeOut(400);
						$('#back').fadeOut(400);
						$('#newpwd').fadeOut(400);
						if(type == 1 || type == 2){
							if(json.group == "G"){
								link = "intranet/genitori/index.php";
								redirect = "intranet/genitori/modifica_password.php?from=first_access";

							}
							else{
								link = "intranet/alunni/index.php";
								redirect = "intranet/alunni/modifica_password.php?from=first_access";
								//if(dati[7] == 1)
								//	window.location = redirect;
							}
							area = "";
							setTimeout(function(){
								document.location.href=link;
								//$('#login_form').html("");
								//$("<div class='start_link' style='height: 120px; text-align: center'><a href='"+link+"'>Accedi all'area privata</a></div>").appendTo($('#login_form'));
								//$("<div class='start_link' style='height: 120px; text-align: center'><a href='<?php print $_SESSION['__config__']['root_site'] ?>/shared/do_logout.php'>Logout</a></div>").appendTo($('#login_form'));
							}, 400);

						}
						else {
							$('#login_form').html("");
							area = "";
							setTimeout(function() {
								$('#login_form').html("");
								gruppi = json.gids;
								var link = "";
								if (gruppi.length == 1) {
									switch (gruppi[0]) {
										case "2":
											link = "intranet/teachers/index.php";
											break;
										case "4":
											link = "intranet/genitori/index.php";
											break;
										case "5":
											link = "intranet/manager/index.php?role=5";
											break;
										case "6":
											link = "intranet/manager/index.php?role=6";
											break;
										case "7":
											link = "intranet/manager/index.php?role=7";
											break;
										case "1":
											link = "admin/index.php";
											break;
									}
									document.location.href = link;
								}
								col_length = parseInt(50 * (gruppi.length + 2));
								$('#login_form').css({height: col_length+"px", width: "500px", padding: "30px"});
								ct = 0;

								for(i = 0; i < gruppi.length; i++){
									if (ct < 3) {
										icon_color = "#FFFFFF";
									}
									else {
										icon_color = "#222222";
									}
									if(gruppi[i] == 1 || gruppi[i] == 9 || gruppi[i] == 10) {
										$("<a href='admin/index.php' style='text-transform: uppercase; color: #"+colors[ct]+"'><div class='nowcard'><div class='icon_card' style='background-color: #"+colors[ct]+"'><span class='fa fa-wrench' style='color: "+icon_color+"'></span></div><p class='text_card'>Amministrazione</p></div></a>").appendTo($('#login_form'));
										ct++;
										//$("<div class='start_link' style='height: " + col_length + "px; text-align: center'><a href='admin/index.php'>Amministrazione</a></div>").appendTo($('#login_form'));
									}
									if(gruppi[i] == 2) {
										$("<a href='intranet/teachers/index.php' style='text-transform: uppercase; color: #"+colors[ct]+"'><div class='nowcard'><div class='icon_card' style='background-color: #"+colors[ct]+"'><span class='fa fa-users' style='color: "+icon_color+"'></span></div><p class='text_card'>Accedi come docente</p></div></a>").appendTo($('#login_form'));
										ct++;
										//$("<div class='start_link' style='height: " + col_length + "px; text-align: center'><a href='intranet/teachers/index.php'>Accedi come docente</a></div>").appendTo($('#login_form'));
									}
									if(gruppi[i] == 3) {
										$("<a href='intranet/ata/index.php' style='text-transform: uppercase; color: #"+colors[ct]+"'><div class='nowcard'><div class='icon_card' style='background-color: #"+colors[ct]+"'><span class='fa fa-desktop' style='color: "+icon_color+"'></span></div><p class='text_card'>Accedi come ATA</p></div></a>").appendTo($('#login_form'));
										ct++;
										//$("<div class='start_link' style='height: "+col_length+"px; text-align: center'><a href='intranet/ata/index.php'>Accedi come ATA</a></div>").appendTo($('#login_form'));
									}
									if(gruppi[i] == 4) {
										//$("<div class='start_link' style='height: " + col_length + "px; text-align: center'><a href='intranet/genitori/index.php'>Accedi come genitore</a></div>").appendTo($('#login_form'));
										$("<a href='intranet/genitori/index.php' style='text-transform: uppercase; color: #" + colors[ct] + "'><div class='nowcard'><div class='icon_card' style='background-color: #" + colors[ct] + "'><span class='fa fa-male' style='color: " + icon_color + "'></span><span class='fa fa-female' style='font-size: 0.8em; color: " + icon_color + "'></span></div><p class='text_card'>Accedi come genitore</p></div></a>").appendTo($('#login_form'));
										ct++;
									}
									if(gruppi[i] == 6) {
										$("<a href='intranet/manager/index.php?role=6' style='text-transform: uppercase; color: #"+colors[ct]+"'><div class='nowcard'><div class='icon_card' style='background-color: #"+colors[ct]+"'><span class='fa fa-keyboard-o' style='color: "+icon_color+"'></span></div><p class='text_card'>Accedi come DS</p></div></a>").appendTo($('#login_form'));
										ct++;
										//$("<div class='start_link' style='height: " + col_length + "px; text-align: center'><a href='intranet/manager/index.php?role=6'>Accedi come DS</a></div>").appendTo($('#login_form'));
									}
									if(gruppi[i] == 5) {
										$("<a href='intranet/manager/index.php?role=5' style='text-transform: uppercase; color: #"+colors[ct]+"'><div class='nowcard'><div class='icon_card' style='background-color: #"+colors[ct]+"'><span class='fa fa-archive' style='color: "+icon_color+"'></span></div><p class='text_card'>Accedi alle funzioni di segreteria</p></div></a>").appendTo($('#login_form'));
										ct++;
										//$("<div class='start_link' style='height: " + col_length + "px; text-align: center'><a href='intranet/manager/index.php?role=5'>Accedi alle funzioni di segreteria</a></div>").appendTo($('#login_form'));
									}
									if(gruppi[i] == 7) {
										$("<a href='intranet/manager/index.php?role=7' style='text-transform: uppercase; color: #"+colors[ct]+"'><div class='nowcard'><div class='icon_card' style='background-color: #"+colors[ct]+"'><span class='fa fa-calculator' style='color: "+icon_color+"'></span></div><p class='text_card'>Accedi come DSGA</p></div></a>").appendTo($('#login_form'));
										ct++;
										//$("<div class='start_link' style='height: " + col_length + "px; text-align: center'><a href='intranet/manager/index.php?role=7'>Accedi come DSGA</a></div>").appendTo($('#login_form'));
									}

								}
								$("<a href='shared/do_logout.php' style='text-transform: uppercase; color: #607d8b'><div class='nowcard logout'><div class='icon_card' style='background-color: #607d8b'><span class='fa fa-desktop' style='color: #FFFFFF'></span></div><p class='text_card'>Logout</p></div></a>").appendTo($('#login_form'));
								//$("<div class='start_link' style='height: "+col_length+"px; text-align: center'><a href='<?php print $_SESSION['__config__']['root_site'] ?>/shared/do_logout.php'>Logout</a></div>").appendTo($('#login_form'));
							}, 350);
						}
						$('#login_form').fadeIn("500");
					}
				}
			});
		}

		var send_email = function(_area){
			var mail = $('#email').val();

			$.ajax({
				type: "POST",
				url: 'password_manager.php',
				data: {email: mail, area: _area, action: 'sendmail'},
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
					else if (json.status == "nomail" || json.status == "olduser"){
						j_alert("error", json.message);
					}
					else {
						j_alert("alert", json.message+". Clicca sul link che troverai nella mail e segui le istruzioni");
					}
				}
			});
		};

		var load_login = function(){
			$('#login_form').fadeOut(400);

			setTimeout(function(){
				$('#login_form').html("<form id='myform' method='post' style='flex: 1 1 auto' action='#'><div id='r1' style='width: 100%; margin: 40px auto 0 auto; height: 60px; '><div style='width: 50%; float: left; text-align: right'>			<input type='text' autofocus name='nick' id='nick' style='' />		</div>		<div class='login_label'>Username</div>	</div>	<div id='r2' style='clear: left; margin: 0 auto 0 auto; height: 60px;'>		<div style='width: 50%; float: left; text-align: right'>			<input type='password' name='pass' id='pass' style='' />		</div>		<div class='login_label'>Password</div>		<div style='width: 25%; float: left; text-align: center; color: #FFFFFF'>			<img src='images/login.jpeg' />		</div>	</div>	<div id='r3' style='clear: left; height: 120px; text-align: center'><input id='button' type='button' onclick='do_login("+area+")' style='' value='LOGIN' />	</dv></form>");
				$('#login_form').fadeIn(300);
				$('#nick').focus();
				$('#back').show();
				if (area != 2){
					$('#newpwd').show();
				}
				s = ": area ";
				if(area == 1) {
					s += "genitori";
				}
				else if (area == 3) {
					s += "scuola";
				}
				else {
					s += "studenti";
				}
				$('#area').text(s);
			}, 400);
		};

		var login_back = function(){
			$('#login_form').fadeOut(400);
			setTimeout(function() {
				$('#login_form').html('<div class="area"><a href="#" id="parents"><img src="./images/genitori.jpg" class="area_img"  /><div>Area genitori</div></a></div><div class="area" id="center_el"><a href="#" id="students"><img src="./images/studenti2.png" /><div>Area studenti</div></a></div><div class="area" id="area_school"><a href="#" id="school"><img src="./images/scuola.jpeg" /><div>Area scuola</div></a></div>');
				$('#login_form').fadeIn(300);
				$('#back').hide();
				$('#newpwd').hide();
				$('#parents').click(function(event){
					event.preventDefault();
					area = 1;
					load_login();
				});
				$('#school').click(function(event){
					event.preventDefault();
					area = 3;
					load_login();
				});
				$('#students').click(function(event){
					event.preventDefault();
					area = 2;
					load_login();
				});
				$('#area').text("");
			}, 700);

		};

		var newpwd_form = function(){
			$('#login_form').fadeOut(400);
			setTimeout(function() {
				$('#login_form').html("<form id='myform' method='post' action='#'><div id='r1' style='margin: 40px auto 0 auto; height: 60px; '><div style='width: 75%; float: left; text-align: right'><input type='email' autofocus name='email' id='email' style='' /></div><div class='login_label'>Email</div></div><div id='t1' style=''><p style='padding: 10px; font-size: 1.1em'>Inserisci l'indirizzo email col quale ti sei registrato e riceverai a breve una mail, contenente le istruzioni per modificare la tua password.</p></div><div id='r3' style='clear: left; height: 120px; text-align: center; margin-top: 50px'><input id='mail_button' type='button' onclick='send_email("+area+")' value='Invia richiesta' /></div></form>");
				$('#login_form').fadeIn(300);
				$('#email').focus();
				$('#back').show();
				$('#newpwd').hide();
			}, 700);
		};

		$(function(){
			check_msie();
			load_jalert();
			setOverlayEvent();
			if ($('#parents')){
				$('#parents').click(function(event){
					event.preventDefault();
					area = 1;
					load_login();
				});
			}
			if ($('#school')){
				$('#school').click(function(event){
					event.preventDefault();
					area = 3;
					load_login();
				});
			}
			if ($('#students')){
				$('#students').click(function(event){
					event.preventDefault();
					area = 2;
					load_login();
				});
			}
			$('#back_link').click(function(event){
				event.preventDefault();
				login_back();
			});
			$('#newpwd_link').click(function(event){
				event.preventDefault();
				newpwd_form();
			});
		});
	</script>
	<style>
		#links a {
			display: block;
			height: 25px;
			font-size: 1.2em;
		}
	</style>
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
		<div id="login_form" style="display: flex; display: -webkit-flex; flex-direction: row; flex-wrap: wrap; align-items: center; <?php if (isset($_SESSION['__user__'])) echo "height: ".$col_length."px; width: 500px; padding: 30px; " ?>">
		<?php 
		if(!isset($_SESSION['__user__'])){ 
		?>
			<div class="area">
				<a href="#" id="parents">
					<img src="./images/genitori.jpg" class="area_img"  />
					<div>Area genitori</div>
				</a>
			</div>
			<div class="area" id="center_el">
				<a href="#" id="students">
					<img src="./images/studenti2.png" />
					<div>Area studenti</div>
				</a>
			</div>
			<div class="area" id="area_school">
				<a href="#" id="school">
					<img src="./images/scuola.jpeg" />
					<div>Area scuola</div>
				</a>
			</div>
		<?php 
		}
		else {
			$groups = $_SESSION['__user__']->getGroups();
			$col_length = 50 * (count($groups) + 1);
			$ct = 0;
			for($i = 0; $i < count($groups); $i++){
				if ($ct < 3) {
					$icon_color = "#FFFFFF";
				}
				else {
					$icon_color = "#222222";
				}
				if($groups[$i] == 1 || $groups[$i] == 9 || $groups[$i] == 10){
					print("<a href='admin/index.php' style='text-transform: uppercase; color: #".$colors[$ct]."'><div class='nowcard'><div class='icon_card' style='background-color: #".$colors[$ct]."'><span class='fa fa-wrench' style='color: ".$icon_color."'></span></div><p class='text_card'>Amministrazione</p></div></a>");
					$ct++;
				}
				if($groups[$i] == 2){
					print("<a href='intranet/teachers/index.php' style='text-transform: uppercase; color: #".$colors[$ct]."'><div class='nowcard'><div class='icon_card' style='background-color: #".$colors[$ct]."'><span class='fa fa-users' style='color: ".$icon_color."'></span></div><p class='text_card'>Accedi come docente</p></div></a>");
					$ct++;
				}
				if($groups[$i] == 3){
					print("<a href='intranet/ata/index.php' style='text-transform: uppercase; color: #".$colors[$ct]."'><div class='nowcard'><div class='icon_card' style='background-color: #".$colors[$ct]."'><span class='fa fa-desktop' style='color: ".$icon_color."'></span></div><p class='text_card'>Accedi come ATA</p></div></a>");
					$ct++;
				}
				if($groups[$i] == 4){
					print("<a href='intranet/genitori/index.php' style='text-transform: uppercase; color: #".$colors[$ct]."'><div class='nowcard'><div class='icon_card' style='background-color: #".$colors[$ct]."'><span class='fa fa-male' style='color: ".$icon_color."'></span><span class='fa fa-female' style='font-size: 0.8em; color: ".$icon_color."'></span></div><p class='text_card'>Accedi come genitore</p></div></a>");
					$ct++;
				}
				if($groups[$i] == 5){
					print("<a href='intranet/manager/index.php?role=5' style='text-transform: uppercase; color: #".$colors[$ct]."'><div class='nowcard'><div class='icon_card' style='background-color: #".$colors[$ct]."'><span class='fa fa-archive' style='color: ".$icon_color."'></span></div><p class='text_card'>Accedi alle funzioni di segreteria</p></div></a>");
					$ct++;
				}
				if($groups[$i] == 6){
					print("<a href='intranet/manager/index.php?role=6' style='text-transform: uppercase; color: #".$colors[$ct]."'><div class='nowcard'><div class='icon_card' style='background-color: #".$colors[$ct]."'><span class='fa fa-keyboard-o' style='color: ".$icon_color."'></span></div><p class='text_card'>Accedi come DS</p></div></a>");
					$ct++;
				}
				if($groups[$i] == 7){
					print("<a href='intranet/manager/index.php?role=7' style='text-transform: uppercase; color: #".$colors[$ct]."'><div class='nowcard'><div class='icon_card' style='background-color: #".$colors[$ct]."'><span class='fa fa-calculator' style='color: ".$icon_color."'></span></div><p class='text_card'>Accedi come DSGA</p></div></a>");
					$ct++;
				}
				if($groups[$i] == 8){
					print("<a href='intranet/alunni/index.php' style='text-transform: uppercase; color: #".$colors[$ct]."'><div class='nowcard'><div class='icon_card' style='background-color: #".$colors[$ct]."'><span class='fa fa-graduation-cap' style='color: ".$icon_color."'></span></div><p class='text_card'>Accedi all'area studenti</p></div></a>");
					$ct++;
				}
			
			}
			print("<a href='shared/do_logout.php' style='text-transform: uppercase; color: #607d8b'><div class='nowcard logout'><div class='icon_card' style='background-color: #607d8b'><span class='fa fa-undo' style='color: #FFFFFF'></span></div><p class='text_card'>Logout</p></div></a>");
		} ?>
		</div>
		<nav style="clear: both">
			<div id="back" style="width: 500px; margin: 10px auto 0 auto; text-align: center; display: none">
				<a href="#" id="back_link" style="color: #EEEEEE; font-size: 1.5em; text-shadow: 0 0 2px #FFFFFF; text-decoration: underline">Indietro</a>
			</div>
			<div id="newpwd" style="width: 500px; margin: 10px auto 0 auto; text-align: center; display: none">
				<a href="#" id="newpwd_link" style="color: #EEEEEE; font-style: italic; font-size: 1.2em; text-shadow: 0 0 2px #FFFFFF;; text-decoration: underline">Password dimenticata?</a>
			</div>
		</nav>
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
