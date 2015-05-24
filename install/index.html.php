<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Installazione software</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="../css/site_themes/indigo/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../css/site_themes/indigo/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
		});

		var forward_not_perm = function(){
			j_alert("error", "Questa funzione non puo` essere attivata senza aver prima completato le precedenti");
		};

		var already_done = function(){
			j_alert("alert", "Operazione completata");
		};
	</script>

</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "working.php" ?>
	</div>
	<div id="left_col">
        <div class="card_container">
	        <div class="card">
		        <div class="card_title">Database</div>
		        <div class="card_varcontent">
			        <div class="material_card cyan_contrast">
				        <a href="install.php?step=1" id="head_lnk">Connessione al database</a>
			        </div>
			        <div class="material_card" style="visibility: <?php if(file_exists("../lib/conn.php")) echo ""; else echo "hidden"; ?>; margin-left: 5%">
				        <span style="font-weight: bold; ">Fatto!</span>
				        <i class="fa fa-check main700 fright" style="font-size: 1.1em; margin-right: 15px"></i>
			        </div>
			        <div class="material_card blue_contrast">
				        <?php if($_SESSION['step'] == 2){ ?>
					        <a href="install.php?step=2">Creazione e popolamento database</a>
				        <?php
				        }
				        else if($_SESSION['step'] < 2){
					        ?>
					        <a href="#" onclick="forward_not_perm()">Creazione e popolamento database</a>
				        <?php
				        }
				        else if($_SESSION['step'] > 2){
					        ?>
					        <a href="#" onclick="already_done()">Creazione e popolamento database</a>
				        <?php
				        }
				        ?>
				        <?php if($_SESSION['step'] > 2){ ?>
					        <span style="float: right; margin-right: 15px; font-weight: bold; ">Fatto!</span>
					        <i class="fa fa-check main700 fright" style="font-size: 1.1em; margin-right: 15px"></i>
				        <?php } ?>
			        </div>
		        </div>
	        </div>
        </div>
    </div>
	</div>
    <?php include "footer.php" ?>
</body>
</html>

<table style="width: 90%; margin-right: auto; margin-left: auto; border-collapse: collapse">
	<tr>
		<td colspan="2" class="accent_color _bold bottom_decoration" style="font-size: 1.1em">Database</td>
	</tr>
	<tr id="step1" class="accent_decoration" <?php if(file_exists("../lib/conn.php")){ ?>style="background-color: rgba(250, 246, 183, .7)"<?php } ?>>
		<td style=" width: 30%" class="accent_decoration"><a href="install.php?step=1" id="head_lnk" class="normal">Connessione al database</a></td>
		<td style="color: #003366" class="accent_decoration">
			<a href="install.php?step=1" id="head_lnk_1" style="width: 100px">Imposta i parametri di connessione al database: host, user...</a>
			<?php if(file_exists("../lib/conn.php")){ ?>
				<span style="float: right; margin-right: 15px; font-weight: bold; ">Fatto!</span>
				<i class="fa fa-check main700 fright" style="font-size: 1.1em; margin-right: 15px"></i>
			<?php } ?>
		</td>
	</tr>
	<tr class="accent_decoration" id="step2" <?php if($_SESSION['step'] > 2){ ?>style="background-color: #FAF6B7"<?php } ?>>
		<td style=" width: 30%" class="accent_decoration">
			<?php if($_SESSION['step'] == 2){ ?>
				<a href="install.php?step=2">Creazione e popolamento database</a>
			<?php
			}
			else if($_SESSION['step'] < 2){
				?>
				<a href="#" onclick="forward_not_perm()">Creazione e popolamento database</a>
			<?php
			}
			else if($_SESSION['step'] > 2){
				?>
				<a href="#" onclick="already_done()">Creazione e popolamento database</a>
			<?php
			}
			?>
		</td>
		<td style="color: #003366" class="accent_decoration">
			<?php if($_SESSION['step'] == 2){ ?>
				<a href="install.php?step=2" style="width: 100px">Crea il database e inserisce i dati essenziali...</a>
			<?php
			}
			else if($_SESSION['step'] < 2){
				?>
				<a href="#" onclick="forward_not_perm()" style="width: 100px">Crea il database e inserisce i dati essenziali...</a>
			<?php
			}
			else if($_SESSION['step'] > 2){
				?>
				<a href="#" onclick="already_done()" style="width: 100px">Crea il database e inserisce i dati essenziali...</a>
			<?php
			}
			?>
			<?php if($_SESSION['step'] > 2){ ?>
				<div style="width: 15px; height: 15px; border-radius: 50%; background-color: green; float: right; margin-right: 5px"> </div>
				<span style="float: right; margin-right: 15px; font-weight: bold; ">Fatto!</span>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" class="accent_color _bold bottom_decoration" style="font-size: 1.1em">Ambiente</td>
	</tr>
	<tr class="accent_decoration" id="step3" <?php if($_SESSION['step'] > 3){ ?>style="background-color: #FAF6B7"<?php } ?>>
		<td style=" width: 30%" class="accent_decoration">
			<?php
			if($_SESSION['step'] == 3){
				?>
				<a href="install.php?step=3">Configurazione software</a>
			<?php
			}
			else if($_SESSION['step'] < 3){
				?>
				<a href="#" onclick="forward_not_perm()">Configurazione software</a>
			<?php
			}
			else{
				?>
				<a href="#" onclick="already_done()">Configurazione software</a>
			<?php
			}
			?>
		</td>
		<td style="color: #003366" class="accent_decoration">
			<?php if($_SESSION['step'] == 3){ ?>
				<a href="install.php?step=3" style="width: 100px">Inserisci le impostazione base del software...</a>
			<?php
			}
			else if($_SESSION['step'] < 3){
				?>
				<a href="#" onclick="forward_not_perm()" style="width: 100px">Inserisci le impostazione base del software...</a>
			<?php
			}
			else{
				?>
				<a href="#" onclick="already_done()" style="width: 100px">Inserisci le impostazione base del software...</a>
			<?php
			}
			?>
			<?php if($_SESSION['step'] > 3){ ?>
				<div style="width: 15px; height: 15px; border-radius: 50%; background-color: green; float: right; margin-right: 5px"> </div>
				<span style="float: right; margin-right: 15px; font-weight: bold; ">Fatto!</span>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;&nbsp;&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;&nbsp;&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;&nbsp;&nbsp;</td>
	</tr>
</table>
