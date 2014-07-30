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
<link href="css/index.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
var area = "";

function check_msie(){
	if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)){
		alert("Stai usando Internet Explorer: il funzionamento del sito non e` garantito con questo browser. Ti consigliamo di utilizzare Firefox o Chrome");
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
		      		var response = transport.responseText || "no response text";
		      		//alert(response);
		      		var dati = response.split(";");
		      		if(dati[0] == "kosql"){
		      			alert("I dati inseriti non sono corretti.");
		      			console.log("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
						return;
		      		}
		      		else if(dati[0] == "ko"){
		      			alert("Login non riuscito.");
		      			//console.log("Dati errati");
		      			document.location.href = "index.php";
		      		}
		      		if(type == 1 || type == 2){
		      			if(dati[0] == "G"){
		            		link = "intranet/genitori/index.php";
		            		redirect = "intranet/genitori/modifica_password.php?from=first_access";
		            		if(dati[8] == 1){
		                		//window.location = redirect;
		            		}
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
		            	$('login_form').innerHTML += "<div style='height: 120px; text-align: center'><a style='position: relative; top: 40%; color: #EEEEEE; text-shadow: 0 0 5px #FFFFFF; font-size: 1.6em; text-transform: uppercase' href='"+link+"'>Accedi all'area privata</a></div>";
		            	$('login_form').innerHTML += "<div style='height: 120px; text-align: center'><a style='position: relative; top: 40%; color: #EEEEEE; text-shadow: 0 0 5px #FFFFFF; font-size: 1.6em; text-transform: uppercase' href='<?php print $_SESSION['__config__']['root_site'] ?>/shared/do_logout.php'>Logout</a></div>";
		      		}
		      		else {
			      		$('login_form').update("");
						area = "";
						$('back').hide();
				        $('newpwd').hide();
		            	gruppi = dati[1].split(",");
		            	col_length = parseInt(240 / (gruppi.length + 1));
		            	for(i = 0; i < gruppi.length; i++){
		            		if(gruppi[i] == 1 || gruppi[i] == 9 || gruppi[i] == 10)
		            			$('login_form').innerHTML += "<div style='height: "+col_length+"px; text-align: center'><a style='position: relative; top: 40%; color: #EEEEEE; text-shadow: 0 0 5px #FFFFFF; font-size: 1.6em; text-transform: uppercase' href='admin/index.php'>Amministrazione</a></div>";
		            		if(gruppi[i] == 2)
		            			$('login_form').innerHTML += "<div style='height: "+col_length+"px; text-align: center'><a style='position: relative; top: 40%; color: #EEEEEE; text-shadow: 0 0 5px #FFFFFF; font-size: 1.6em; text-transform: uppercase' href='intranet/teachers/index.php'>Accedi come docente</a></div>";
		            		if(gruppi[i] == 3)
		            			$('login_form').innerHTML += "<div style='height: "+col_length+"px; text-align: center'><a style='position: relative; top: 40%; color: #EEEEEE; text-shadow: 0 0 5px #FFFFFF; font-size: 1.6em; text-transform: uppercase' href='intranet/ata/index.php'>Accedi come ATA</a></div>";
		            		if(gruppi[i] == 4)
		            			$('login_form').innerHTML += "<div style='height: "+col_length+"px; text-align: center'><a style='position: relative; top: 40%; color: #EEEEEE; text-shadow: 0 0 5px #FFFFFF; font-size: 1.6em; text-transform: uppercase' href='intranet/genitori/index.php'>Accedi come genitore</a></div>";
		            		if(gruppi[i] == 6)
			            			$('login_form').innerHTML += "<div style='height: "+col_length+"px; text-align: center'><a style='position: relative; top: 40%; color: #EEEEEE; text-shadow: 0 0 5px #FFFFFF; font-size: 1.6em; text-transform: uppercase' href='intranet/manager/index.php?role=6'>Accedi come DS</a></div>";
			            	if(gruppi[i] == 5)
		            			$('login_form').innerHTML += "<div style='height: "+col_length+"px; text-align: center'><a style='position: relative; top: 40%; color: #EEEEEE; text-shadow: 0 0 5px #FFFFFF; font-size: 1.6em; text-transform: uppercase' href='intranet/manager/index.php?role=5'>Accedi alle funzioni di segreteria</a></div>";
		            		if(gruppi[i] == 7)
		            			$('login_form').innerHTML += "<div style='height: "+col_length+"px; text-align: center'><a style='position: relative; top: 40%; color: #EEEEEE; text-shadow: 0 0 5px #FFFFFF; font-size: 1.6em; text-transform: uppercase' href='intranet/manager/index.php?role=7'>Accedi come DSGA</a></div>";

			            	}
		      		}
	            	$('login_form').innerHTML += "<div style='height: "+col_length+"px; text-align: center'><a style='position: relative; top: 40%; color: #EEEEEE; text-shadow: 0 0 5px #FFFFFF; font-size: 1.6em; text-transform: uppercase' href='<?php print $_SESSION['__config__']['root_site'] ?>/shared/do_logout.php'>Logout</a></div>";
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
	$('login_form').update("<form id='myform' method='post' action='#'>	<div id='r1' style='margin: 40px auto 0 auto; height: 60px; '>		<div style='width: 50%; float: left; text-align: right'>			<input type='text' autofocus name='nick' id='nick' style='width: 90%; color: #EEEEEE; border: 2px solid #FFFFFF; border-radius: 5px; box-shadow: 0 0 5px #FFFFFF; background-color:transparent; font-size: 1.5em; margin: auto' />		</div>		<div style='width: 25%; float: left; text-align: center; color: #FFFFFF; font-size: 1.15em; text-transform: uppercase; text-shadow: 0 0 5px #FFFFFF; padding-top: 7px'>			Login		</div>	</div>	<div id='r2' style='clear: left; margin: 0 auto 0 auto; height: 60px;'>		<div style='width: 50%; float: left; text-align: right'>			<input type='password' name='pass' id='pass' style='width: 90%; color: #EEEEEE; border: 2px solid #FFFFFF; border-radius: 5px; box-shadow: 0 0 5px #FFFFFF; background-color:transparent; font-size: 1.5em; margin: auto' />		</div>		<div style='width: 25%; float: left; text-align: center; color: #FFFFFF; font-size: 1.15em; text-transform: uppercase; text-shadow: 0 0 5px #FFFFFF; padding-top: 7px'>			Password		</div>		<div style='width: 25%; float: left; text-align: center; color: #FFFFFF; background: url() no-repeat'>			<img src='images/pwd.png' />		</div>	</div>	<div id='r3' style='clear: left; height: 120px; text-align: center'>		<input type='button' onclick='do_login("+area+")' style='width: 90px; heigth: 45px; border: 2px solid #FFFFFF; border-radius: 5px; box-shadow: 0 0 5px #FFFFFF; background-color: transparent; color: #EEEEEE; font-size: 1.15em; margin-top: 20px' value='LOGIN' />	</div></form>");
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
	$('login_form').update('<div style="width: 33%; text-align: center; padding-top: 10%; float: left"><a href="#" id="parents"><img src="./images/genitori.png" /></a></div><div style="width: 33%; text-align: center; padding-top: 20%; float: left"><a href="#" id="students"><img src="./images/studenti.png" /></a></div><div style="width: 33%; text-align: center; padding-top: 10%; float: left"><a href="#" id="school"><img src="./images/scuola.png" /></a></div>');
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
	$('login_form').update("<form id='myform' method='post' action='#'><div id='r1' style='margin: 40px auto 0 auto; height: 60px; '><div style='width: 75%; float: left; text-align: right'><input type='email' autofocus name='email' id='email' style='width: 90%; color: #EEEEEE; border: 2px solid #FFFFFF; border-radius: 5px; box-shadow: 0 0 5px #FFFFFF; background-color:transparent; font-size: 1.5em; margin: auto' /></div><div style='width: 25%; float: left; text-align: center; color: #FFFFFF; font-size: 1.15em; text-transform: uppercase; text-shadow: 0 0 5px #FFFFFF; padding-top: 7px'>Email</div></div><div id='t1' style='clear:left; width: 90%; margin: auto; height: 60px; color: #FFFFFF'><p style='padding: 10px; font-size: 1.1em'>Inserisci l'indirizzo email col quale ti sei registrato e riceverai a breve una mail, contenente le istruzioni per modificare la tua password.</p></div><div id='r3' style='clear: left; height: 120px; text-align: center'><input type='button' onclick='send_email("+area+")' style='width: 110px; heigth: 45px; border: 2px solid #FFFFFF; border-radius: 5px; box-shadow: 0 0 5px #FFFFFF; background-color: transparent; color: #EEEEEE; font-size: 1.15em; margin-top: 20px' value='Invia richiesta' /></div></form>");
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
*{margin:0;padding:0;}:focus,:active {outline:0}ul,ol{list-style:none}img{border:0} 

body {
	background: url(images/body.png) repeat; 
	font-size: 12px; 
	font-family: Georgia
}
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
			<div class="area"><a href="#" id="parents"><img src="./images/genitori.png" class="area_img"  /></a></div>
			<div class="area" id="center_el"><a href="#" id="students"><img src="./images/studenti.png" /></a></div>
			<div class="area"><a href="#" id="school"><img src="./images/scuola.png" /></a></div>
		<?php 
		}
		else {
			$groups = $_SESSION['__user__']->getGroups();
			$col_length = 240 / (count($groups) + 1);
			for($i = 0; $i < count($groups); $i++){
				if($groups[$i] == 1 || $groups[$i] == 9 || $groups[$i] == 10){
					print("<div style='height: {$col_length}px; text-align: center'><a style='position: relative; top: 40%; color: #EEEEEE; text-shadow: 0 0 5px #FFFFFF; font-size: 1.6em; text-transform: uppercase' href='./admin/index.php'>Amministrazione</a></div>");
				}
				if($groups[$i] == 2){
					print("<div style='height: {$col_length}px; text-align: center'><a style='position: relative; top: 40%; color: #EEEEEE; text-shadow: 0 0 5px #FFFFFF; font-size: 1.6em; text-transform: uppercase' href='intranet/teachers/index.php'>Accedi come docente</a></div>");
				}
				if($groups[$i] == 3){
					print("<div style='height: {$col_length}px; text-align: center'><a style='position: relative; top: 40%; color: #EEEEEE; text-shadow: 0 0 5px #FFFFFF; font-size: 1.6em; text-transform: uppercase' href='intranet/ata/index.php'>Accedi come ATA</a></div>");
				}
				if($groups[$i] == 4){
					print("<div style='height: {$col_length}px; text-align: center'><a style='position: relative; top: 40%; color: #EEEEEE; text-shadow: 0 0 5px #FFFFFF; font-size: 1.6em; text-transform: uppercase' href='intranet/genitori/index.php'>Accedi come genitore</a></div>");
				}
				if($groups[$i] == 5){
					print("<div style='height: {$col_length}px; text-align: center'><a style='position: relative; top: 40%; color: #EEEEEE; text-shadow: 0 0 5px #FFFFFF; font-size: 1.6em; text-transform: uppercase' href='intranet/manager/index.php?role=5'>Accedi alle funzioni di segreteria</a></div>");
				}
				if($groups[$i] == 6){
					print("<div style='height: {$col_length}px; text-align: center'><a style='position: relative; top: 40%; color: #EEEEEE; text-shadow: 0 0 5px #FFFFFF; font-size: 1.6em; text-transform: uppercase' href='intranet/manager/index.php?role=6'>Accedi come DS</a></div>");
				}
				if($groups[$i] == 7){
					print("<div style='height: {$col_length}px; text-align: center'><a style='position: relative; top: 40%; color: #EEEEEE; text-shadow: 0 0 5px #FFFFFF; font-size: 1.6em; text-transform: uppercase' href='intranet/manager/index.php?role=7'>Accedi come DSGA</a></div>");
				}
				if($groups[$i] == 8){
					print("<div style='height: {$col_length}px; text-align: center'><a style='position: relative; top: 40%; color: #EEEEEE; text-shadow: 0 0 5px #FFFFFF; font-size: 1.6em; text-transform: uppercase' href='intranet/alunni/index.php'>Accedi all'area studenti</a></div>");
				}
			
			}
			print("<div style='height: {$col_length}px; text-align: center'><a style='position: relative; top: 40%; color: #EEEEEE; text-shadow: 0 0 5px #FFFFFF; font-size: 1.6em; text-transform: uppercase' href='shared/do_logout.php'>Logout</a></div>");
		} ?>
		</div>
		<nav>
			<div id="back" style="width: 500px; margin: 10px auto 0 auto; text-align: center; display: none"><a href="#" id="back_link" style="color: #EEEEEE; font-size: 1.5em; text-shadow: 0 0 5px #FFFFFF;">Indietro</a></div>
			<div id="newpwd" style="width: 500px; margin: 10px auto 0 auto; text-align: center; display: none"><a href="#" id="newpwd_link" style="color: #EEEEEE; font-style: italic; font-size: 1.2em; text-shadow: 0 0 5px #FFFFFF;">Password dimenticata?</a></div>
		</nav>
	</section>	
</body>
</html>
