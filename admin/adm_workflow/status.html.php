<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="author" content="" />
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
var messages = new Array('', 'Status inserito con successo', 'Status cancellato con successo', 'Status modificato con successo');
function status(id){
	//var newwin = window.open_centered("dettaglio_status.php?id="+id, "news", 550, 300, "");
	win = new Window({className: "mac_os_x", url: "dettaglio_status.php?id="+id,  width:550, height:250, zIndex: 100, resizable: true, title: "Dettaglio step", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
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
        <table class="admin_table">
            <tr class="admin_title_row">
                <td style="font-weight: bold" colspan="3" align="center">Elenco Status</td>
            </tr>
            <tr class="admin_row">
                <td style="width: 10%" class="adm_titolo_elenco">ID status</td>
                <td style="padding-left: 20px; width: 30%" class="adm_titolo_elenco">Descrizione</td>
                <td style="padding-left: 20px; width: 60%" class="adm_titolo_elenco">Permessi</td>
            </tr>
            <tr class="admin_row">
                <td colspan="3"></td>
            </tr>
            <?php
            $x = 1;
			
            $id_attuale = 0;
            $uffici = array();
            while($status = $res_status->fetch_assoc()){
            	if($id_attuale != $status['id_status'] && $id_attuale != 0){
            		$stringa_uffici = join(", ", $uffici);
            ?>
            <tr class="admin_row<?php if($x % 2) print(" odd") ?>">
                <td style="padding-left: 5px"><?php print $id_attuale ?></td>
                <td style="padding-left: 20px"><a href="#" onclick="status(<?php print $id_attuale ?>)"><?php print $st ?></a></td>
                <td style="padding-left: 20px"><a href="#" onclick="status(<?php print $id_attuale ?>)"><?php print $stringa_uffici ?></a></td>
            </tr>
            <?php 
            		unset($uffici);
            		$uffici = array();
            		$x++;
				}
				$st = $status['nome'];
				if(!in_array($status['uff'], $uffici))
					array_push($uffici, $status['uff']);
				$id_attuale = $status['id_status'];
				
			} 
			$stringa_uffici = join(", ", $uffici);
			?>
			<tr class="admin_row<?php if($x % 2) print(" odd") ?>">
                <td style="padding-left: 5px"><?php print $id_attuale ?></td>
                <td style="padding-left: 20px"><a href="#" onclick="status(<?php print $id_attuale ?>)"><?php print $st ?></a></td>
                <td style="padding-left: 20px"><a href="#" onclick="status(<?php print $id_attuale ?>)"><?php print $stringa_uffici ?></a></td>
            </tr>
			<tr class="admin_row">
                <td colspan="3"></td>
            </tr>
            <tr class="admin_row_menu">
                <td colspan="3" align="right">
                	<a href="#" onclick="status(0)" class="nav_link_first">Nuovo status</a>|
                	<a href="index.php" class="nav_link_last">Torna indietro</a>
                </td>
            </tr>
            <tr class="admin_row">
                <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
            </tr>
		</table>
	</div>
        <?php include "../footer.php" ?>
    </div>				
</body>
</html>			