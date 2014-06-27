<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link rel="stylesheet" href="../../css/main.css" type="text/css" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript">
var win;
var messages = new Array('', 'Step inserito con successo', 'Step cancellato con successo', 'Step modificato con successo');
function step(id){
	//var newwin = window.open_centered("dettaglio_step.php?id="+id, "news", 550, 300, "");
	win = new Window({className: "mac_os_x", url: "dettaglio_step.php?id="+id,  width:550, height:250, zIndex: 100, resizable: true, title: "Dettaglio step", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
	win.showCenter(false);
}	
</script>
<title>Amministrazione</title>
</head>
<body <?php if(isset($_REQUEST['msg'])){ ?>onload="openInfoDialog(messages[<?php print $_REQUEST['msg'] ?>], 2)"<?php } ?>>
    <div id="header">
		<div class="wrap" style="text-align: center">
			<?php include "../header.php" ?>
		</div>
	</div>
	<div class="wrap">
	<div id="main" style="background-color: #FFFFFF; padding-bottom: 30px; width: 100%">
    <form>
        <table class="admin_table">
            <tr class="admin_title_row">
                <td style="font-weight: bold" colspan="3" align="center">Elenco Step</td>
            </tr>
            <tr class="admin_row">
                <td style="width: 10%" class="adm_titolo_elenco">ID step</td>
                <td style="padding-left: 20px; width: 60%" class="adm_titolo_elenco">Descrizione</td>
                <td style="padding-left: 20px; width: 30%" class="adm_titolo_elenco">Ufficio</td>
            </tr>
            <tr class="admin_row">
                <td colspan="3"></td>
            </tr>
            <?php
            $x = 1;

            while($step = $res_step->fetch_assoc()){
            ?>
            <tr class="admin_row<?php if($x % 2) print(" odd") ?>">
                <td style="padding-left: 5px"><?php print $step['id_step'] ?></td>
                <td style="padding-left: 20px"><a href="#" onclick="step(<?php print $step['id_step'] ?>)"><?php print $step['descrizione'] ?></a></td>
                <td style="padding-left: 20px"><a href="#" onclick="step(<?php print $step['id_step'] ?>)"><?php print $step['nome'] ?></a></td>
            </tr>
            <?php 
				$x++;
			} 
			?>
            <tr class="admin_row">
                <td colspan="3"></td>
            </tr>
            <tr class="admin_row_menu">
                <td colspan="3" align="right">
                	<a href="#" onclick="step(0)" class="nav_link_first">Nuovo step</a>|
                	<a href="index.php" class="nav_link">Torna indietro</a>
                </td>
            </tr>
            <tr class="admin_row">
                <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
            </tr>
		</table>
	</form>
	</div>
        <?php include "../footer.php" ?>
    </div>				
</body>
</html>	