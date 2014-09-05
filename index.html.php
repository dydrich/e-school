<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>Registro elettronico::login</title>
<script type="text/javascript" src="./js/prototype.js"></script>
<script type="text/javascript" src="./js/scriptaculous.js"></script>
<script type="text/javascript" src="./js/controls.js"></script>
<script type="text/javascript" src="./js/page.js"></script>
<script type="text/javascript" src="./js/md5-min.js"></script>
<link href="css/site_themes/blue_red/index.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
var area = "";

function check_msie(){
	if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)){
		alert("Stai usando Internet Explorer: il funzionamento del sito non è garantito con questo browser. Ti consigliamo di utilizzare Firefox o Chrome");
		return false;
	}
	return true;
}

/*
 * funzione di login 
 */
function do_login(type){
	var nick = $F('nick');
	var pwd = $F('pass');
	//alert(pwd);
	var pass = hex_md5(pwd);

	var url = "do_login.php";
	var req = new Ajax.Request(url,
		  {
		    	method:'post',
		    	parameters: {nick: nick, pass: pass, param: type},
		    	onSuccess: function(transport){
		      		var response = transport.responseText.evalJSON();
		      		//alert(response.group);
		      		//var dati = response.split(";");
		      		if(response.status == "kosql"){
		      			alert("I dati inseriti non sono corretti.");
		      			console.log("Errore SQL. \nQuery: "+response.query+"\nErrore: "+response.message);
						return;
		      		}
		      		else if(response.status == "ko"){
		      			alert("Login non riuscito.");
		      			//console.log("Dati errati");
		      			document.location.href = "index.php";
		      		}
		      		if(type == 1 || type == 2){
		      			if(response.group == "G"){
		            		link = "intranet/genitori/index.php";
		            		redirect = "intranet/genitori/modifica_password.php?from=first_access";

		            	}
		            	else{
		            		link = "intranet/alunni/index.php";
		            		redirect = "intranet/alunni/modifica_password.php?from=first_access";
		            		//if(dati[7] == 1)
		                	//	window.location = redirect;
		            	}
		            	$('login_form').update("");
						area = "";
						$('back').hide();
				        $('newpwd').hide();
		            	$('login_form').innerHTML += "<div class='start_link' style='height: 120px; text-align: center'><a href='"+link+"'>Accedi all'area privata</a></div>";
		            	$('login_form').innerHTML += "<div class='start_link' style='height: 120px; text-align: center'><a href='<?php print $_SESSION['__config__']['root_site'] ?>/shared/do_logout.php'>Logout</a></div>";
		      		}
		      		else {
			      		$('login_form').update("");
						area = "";
						$('back').hide();
				        $('newpwd').hide();
		            	gruppi = response.gids;
				        //alert(gruppi.length);
		            	col_length = parseInt(300 / (gruppi.length + 1));
		            	for(i = 0; i < gruppi.length; i++){
		            		if(gruppi[i] == 1 || gruppi[i] == 9 || gruppi[i] == 10)
		            			$('login_form').innerHTML += "<div class='start_link' style='height: "+col_length+"px; text-align: center'><a href='admin/index.php'>Amministrazione</a></div>";
		            		if(gruppi[i] == 2)
		            			$('login_form').innerHTML += "<div class='start_link' style='height: "+col_length+"px; text-align: center'><a href='intranet/teachers/index.php'>Accedi come docente</a></div>";
		            		if(gruppi[i] == 3)
		            			$('login_form').innerHTML += "<div class='start_link' style='height: "+col_length+"px; text-align: center'><a href='intranet/ata/index.php'>Accedi come ATA</a></div>";
		            		if(gruppi[i] == 4)
		            			$('login_form').innerHTML += "<div class='start_link' style='height: "+col_length+"px; text-align: center'><a href='intranet/genitori/index.php'>Accedi come genitore</a></div>";
		            		if(gruppi[i] == 6)
			            			$('login_form').innerHTML += "<div class='start_link' style='height: "+col_length+"px; text-align: center'><a href='intranet/manager/index.php?role=6'>Accedi come DS</a></div>";
			            	if(gruppi[i] == 5)
		            			$('login_form').innerHTML += "<div class='start_link' style='height: "+col_length+"px; text-align: center'><a href='intranet/manager/index.php?role=5'>Accedi alle funzioni di segreteria</a></div>";
		            		if(gruppi[i] == 7)
		            			$('login_form').innerHTML += "<div class='start_link' style='height: "+col_length+"px; text-align: center'><a href='intranet/manager/index.php?role=7'>Accedi come DSGA</a></div>";

			            	}
		      		}
	            	$('login_form').innerHTML += "<div class='start_link' style='height: "+col_length+"px; text-align: center'><a href='<?php print $_SESSION['__config__']['root_site'] ?>/shared/do_logout.php'>Logout</a></div>";
		    	},
		    	onFailure: function(){ alert("Si e' verificato un errore..."); }
		  });
}

var send_email = function(_area){
	var mail = $F('email');
	req = new Ajax.Request("password_manager.php",
			  {
			    	method:'post',
			    	parameters: {email: mail, area: _area, action: 'sendmail'},
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
			      		var json = response.evalJSON();
			      		if(json.status == "kosql"){
				      		alert("Errore  SQL.");
			      			console.log(json.message);
							return;
			      		}
					    else if (json.status == "nomail" || json.status == "olduser"){
					        alert(json.message);
				        }
					    else {
			     		    alert(json.message+". Clicca sul link che troverai nella mail e segui le istruzioni");
				        }
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};

var load_login = function(){
	$('login_form').update("<form id='myform' method='post' action='#'>	<div id='r1' style='margin: 40px auto 0 auto; height: 60px; '>		<div style='width: 50%; float: left; text-align: right'>			<input type='text' autofocus name='nick' id='nick' style='' />		</div>		<div class='login_label'>Username</div>	</div>	<div id='r2' style='clear: left; margin: 0 auto 0 auto; height: 60px;'>		<div style='width: 50%; float: left; text-align: right'>			<input type='password' name='pass' id='pass' style='' />		</div>		<div class='login_label'>Password</div>		<div style='width: 25%; float: left; text-align: center; color: #FFFFFF'>			<img src='images/login.jpeg' />		</div>	</div>	<div id='r3' style='clear: left; height: 120px; text-align: center'><input id='button' type='button' onclick='do_login("+area+")' style='' value='LOGIN' />	</div></form>");
	$('nick').focus();
	$('back').show();
	if (area != 2){
		$('newpwd').show();
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
	$('area').update(s);
};

var login_back = function(){
	$('login_form').update('<div class="area"><a href="#" id="parents"><img src="./images/genitori.jpg" class="area_img"  /><div>Area genitori</div></a></div><div class="area" id="center_el"><a href="#" id="students"><img src="./images/studenti2.png" /><div>Area studenti</div></a></div><div class="area" id="area_school"><a href="#" id="school"><img src="./images/scuola.jpeg" /><div>Area scuola</div></a></div>');
	$('back').hide();
	$('newpwd').hide();
	$('parents').observe("click", function(event){
		event.preventDefault();
		area = 1;
		load_login();
	});
	$('school').observe("click", function(event){
		event.preventDefault();
		area = 3;
		load_login();
	});
	$('students').observe("click", function(event){
		event.preventDefault();
		area = 2;
		load_login();
	});
};

var newpwd_form = function(){
	$('login_form').update("<form id='myform' method='post' action='#'><div id='r1' style='margin: 40px auto 0 auto; height: 60px; '><div style='width: 75%; float: left; text-align: right'><input type='email' autofocus name='email' id='email' style='' /></div><div class='login_label'>Email</div></div><div id='t1' style=''><p style='padding: 10px; font-size: 1.1em'>Inserisci l'indirizzo email col quale ti sei registrato e riceverai a breve una mail, contenente le istruzioni per modificare la tua password.</p></div><div id='r3' style='clear: left; height: 120px; text-align: center; margin-top: 50px'><input id='mail_button' type='button' onclick='send_email("+area+")' value='Invia richiesta' /></div></form>");
	$('email').focus();
	$('back').show();
	$('newpwd').hide();
};

document.observe("dom:loaded", function(){
	if ($('parents')){
		$('parents').observe("click", function(event){
			event.preventDefault();
			area = 1;
			load_login();
		});
	}
	if ($('school')){
		$('school').observe("click", function(event){
			event.preventDefault();
			area = 3;
			load_login();
		});
	}
	if ($('students')){
		$('students').observe("click", function(event){
			event.preventDefault();
			area = 2;
			load_login();
		});
	}
	$('back_link').observe("click", function(event){
		event.preventDefault();
		login_back();
	});
	$('newpwd_link').observe("click", function(event){
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
<body onload="check_msie()">
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
			$col_length = 300 / (count($groups) + 1);
			for($i = 0; $i < count($groups); $i++){
				if($groups[$i] == 1 || $groups[$i] == 9 || $groups[$i] == 10){
					print("<div class='start_link' style='height: {$col_length}px; text-align: center'><a style='' href='./admin/index.php'>Amministrazione</a></div>");
				}
				if($groups[$i] == 2){
					print("<div class='start_link' style='height: {$col_length}px; text-align: center'><a href='intranet/teachers/index.php'>Accedi come docente</a></div>");
				}
				if($groups[$i] == 3){
					print("<div class='start_link' style='height: {$col_length}px; text-align: center'><a href='intranet/ata/index.php'>Accedi come ATA</a></div>");
				}
				if($groups[$i] == 4){
					print("<div class='start_link' style='height: {$col_length}px; text-align: center'><a href='intranet/genitori/index.php'>Accedi come genitore</a></div>");
				}
				if($groups[$i] == 5){
					print("<div class='start_link' style='height: {$col_length}px; text-align: center'><a href='intranet/manager/index.php?role=5'>Accedi alle funzioni di segreteria</a></div>");
				}
				if($groups[$i] == 6){
					print("<div class='start_link' style='height: {$col_length}px; text-align: center'><a href='intranet/manager/index.php?role=6'>Accedi come DS</a></div>");
				}
				if($groups[$i] == 7){
					print("<div class='start_link' style='height: {$col_length}px; text-align: center'><a href='intranet/manager/index.php?role=7'>Accedi come DSGA</a></div>");
				}
				if($groups[$i] == 8){
					print("<div class='start_link' style='height: {$col_length}px; text-align: center'><a href='intranet/alunni/index.php'>Accedi all'area studenti</a></div>");
				}
			
			}
			print("<div class='start_link' style='height: {$col_length}px; text-align: center'><a href='shared/do_logout.php'>Logout</a></div>");
		} ?>
		</div>
		<nav>
			<div id="back" style="width: 500px; margin: 10px auto 0 auto; text-align: center; display: none"><a href="#" id="back_link" style="color: #EEEEEE; font-size: 1.5em; text-shadow: 0 0 2px #FFFFFF; text-decoration: underline">Indietro</a></div>
			<div id="newpwd" style="width: 500px; margin: 10px auto 0 auto; text-align: center; display: none"><a href="#" id="newpwd_link" style="color: #EEEEEE; font-style: italic; font-size: 1.2em; text-shadow: 0 0 2px #FFFFFF;; text-decoration: underline">Password dimenticata?</a></div>
		</nav>
	</section>	
</body>
</html>
