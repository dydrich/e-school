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
var messages = new Array('', 'Workflow inserito con successo', 'Workflow cancellato con successo', 'Workflow modificato con successo');
function flusso(id){
	//var newwin = window.open_centered("dettaglio_workflow.php?id="+id, "work", 650, 300, "");
	win = new Window({className: "mac_os_x", url: "dettaglio_workflow.php?id="+id,  width:650, height:230, zIndex: 100, resizable: true, title: "Dettaglio workflow", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
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
                <td style="font-weight: bold" colspan="3" align="center">Elenco tipologie di richiesta</td>
            </tr>
            <tr class="admin_row">
                <td style="width: 30%" class="adm_titolo_elenco">Richiesta</td>
                <td style="padding-left: 20px; width: 50%" class="adm_titolo_elenco">Workflow</td>
                <td style="padding-left: 20px; width: 20%" class="adm_titolo_elenco">Permessi</td>
            </tr>
            <tr class="admin_row">
                <td colspan="3"></td>
            </tr>
            <?php
            if($res_flow->num_rows < 1){
            	print "<tr><td colspan='3' style='height: 50px; text-align: center; font-weight: bold; text-decoration: underline'>Nessun risultato trovato</tr></td>";
            }
            else{
	            $x = 1;
				
	            while($flusso = $res_flow->fetch_assoc()){
	            	$sel_step = "SELECT * FROM w_step WHERE id_step IN (".$flusso['codice_step'].")";
	            	$res_step = $db->execute($sel_step);
	            	$stringa_step = "";
	            	while($s = $res_step->fetch_assoc()){
	            		$stringa_step .= $s['descrizione']."-&gt;"; 
	            	}
	            	$stringa_step = substr($stringa_step, 0, (count($stringa_step) - 6));
	            	
	            	$sel_gruppi = "SELECT * FROM gruppi WHERE codice&".$flusso['gruppi'];
	            	$res_gruppi = $db->execute($sel_gruppi);
	            	$stringa_gruppi = "";
	            	while($g = $res_gruppi->fetch_assoc()){
	            		$stringa_gruppi .= $g['nome'].", "; 
	            	}
	            	$stringa_gruppi = substr($stringa_gruppi, 0, (count($stringa_gruppi) - 3));
            ?>
            <tr class="admin_row<?php if($x % 2) print(" odd") ?>">
                <td style="padding-left: 5px"><a href="#" onclick="flusso(<?php print $flusso['id_workflow'] ?>)"><?php print $flusso['richiesta'] ?></a></td>
                <td style="padding-left: 20px; "><a href="#" onclick="flusso(<?php print $flusso['id_workflow'] ?>)"><?php print $stringa_step ?></a></td>
                <td style="padding-left: 20px; "><a href="#" onclick="flusso(<?php print $flusso['id_workflow'] ?>)"><?php print $stringa_gruppi ?></a></td>
            </tr>
            <?php 
            		$x++;
				}
			} 
			?>
			<tr class="admin_row">
                <td colspan="3"></td>
            </tr>
            <tr class="admin_row_menu">
                <td colspan="3" align="right">
                	<a href="#" onclick="flusso(0)" class="nav_link_first">Nuovo workflow</a>|
                	<a href="index.php" class="nav_link_last">Torna indietro</a>
                </td>
            </tr>
            <tr class="admin_row">
                <td colspan="3"></td>
            </tr>
		</table>
	</form>
	</div>
        <?php include "../footer.php" ?>
    </div>				
</body>
</html>	