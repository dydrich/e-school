<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Installazione software</title>
<link rel="stylesheet" href="../css/main.css" type="text/css" />
<script type="text/javascript" src="../js/page.js"></script>
<script type="text/javascript">
var forward_not_perm = function(){
	alert("Questa funzione non puo` essere attivata senza aver prima completato le precedenti");
};

var already_done = function(){
	alert("Operazione completata");
};
</script>
<style>
#wait_label{
	width: 200px;
	height: 40px;
	text-align: center;
	background-color: #000000; 
	border: 1px solid #CCCCCC; 
	border-radius: 8px 8px 8px 8px;
	color: white;
	font-weight: bold;
	vertical-align: middle;
}
.index_link {#border-bottom: 1px solid #CCCCCC; height: 25px}
.group_head{
	padding-top: 5px; 
	padding-bottom: 5px; 
	text-align: center; 
	font-weight: bold; 
	background-color: #E7E7E7; 
	border-radius: 5px 5px 5px 5px
}

</style>
</head>
<body>
	<div id="header">
		<div class="wrap">
			<h1 id="logo" style="margin-left: auto; margin-right: auto">Regel 1.0</h1><br />
			<div id="menu" style="clear: both; text-align: center; font-size: 15px; font-weight: bold; color: white">Installazione</div>
		</div>
	</div>
	<div class="wrap">
	<div id="main" style="background-color: #FFFFFF; padding-bottom: 30px; width: 100%">
        <table style="width: 90%; margin-right: auto; margin-left: auto; border-collapse: separate">
        	<tr>
                <td style="font-weight: bold; font-size: 15px; text-align: center; text-shadow: 2px 2px 1px #eee" colspan="2">Gestione ambiente di installazione</td>
            </tr>
            <tr>
            	<td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" class="group_head">Database</td>
            </tr>
            <tr class="index_link" id="step1" <?php if(file_exists("../lib/conn.php")){ ?>style="background-color: #FAF6B7"<?php } ?>>
                <td style=" width: 30%"><a href="install.php?step=1" id="head_lnk">Connessione al database</a></td>
                <td style="color: #003366">
                    <a href="install.php?step=1" id="head_lnk_1" style="width: 100px">Imposta i parametri di connessione al database: host, user...</a>
                    <?php if(file_exists("../lib/conn.php")){ ?>
                    <div style="width: 15px; height: 15px; border-radius: 50%; background-color: green; float: right; margin-right: 5px"> </div>
                    <span style="float: right; margin-right: 15px; font-weight: bold; ">Fatto!</span>
                    <?php } ?>
                </td>
            </tr>
            <tr class="index_link" id="step2" <?php if($_SESSION['step'] > 2){ ?>style="background-color: #FAF6B7"<?php } ?>>
                <td style=" width: 30%">
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
                <td style="color: #003366">
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
                <td colspan="2" class="group_head">Ambiente</td>
            </tr>
            <tr class="index_link" id="step3" <?php if($_SESSION['step'] > 3){ ?>style="background-color: #FAF6B7"<?php } ?>>
                <td style=" width: 30%">
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
                <td style="color: #003366">
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
    </div>
    <?php include "../admin/footer.php" ?>
	</div>		
</body>
</html>
