<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="author" content="" />
<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
<link rel="stylesheet" href="../css/general.css" type="text/css" />
<link href="../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/scriptaculous.js"></script>
<script type="text/javascript" src="../js/page.js"></script>
<script type="text/javascript">
var win; 
var msg;

var timeout; 
function openInfoDialog() {
	var html = "<div style='width: 100%, text-align: center; font-size: 12px; font-weight: bold; padding-top: 30px; margin: auto'>"+msg+"</div>";
	Dialog.info(html, 
	{
		width:250, 
		height:100, 
		showProgress: false,
		className: "alphacube"
	}); 
	timeout = 2; 
	setTimeout(infoTimeout, 1000);
} 
function infoTimeout() { 
	timeout--; 
	if (timeout > 0) { 
		//Dialog.setInfoMessage(messages[index]); 
		setTimeout(infoTimeout, 1000); 
	} 
	else 
		Dialog.closeInfo();
}

function upd_modulo(cbox){
	var url = "simula_installazione_modulo.php";
	
    req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {field: cbox.name, value: cbox.checked},
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
			    		dati = response.split(";");
			    		if(dati[0] == "ko"){
			    			alert("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
			    			return;
			    		}
			    		else{
							alert("Operazione conclusa con successo");
			    		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}
</script>
<title>Modifica moduli installati</title>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "dev_menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Modifica moduli installati</div>
    <form class="no_border">
        <table class="admin_table">
        	<thead>
            </thead>
            <tbody>
            <tr>
            	<td style="width: 50%" class="adm_titolo_elenco_first">Modulo</td>
            	<td style="width: 30%" class="adm_titolo_elenco">Tipo</td>
            	<td style="width: 20%; text-align: center" class="adm_titolo_elenco_last">Installato</td>
            </tr>
            <tr class="admin_void">
                <td colspan="3"></td>
            </tr>
            <?php
            $x = 1;
            while($mod = $res_modules->fetch_assoc()){
            ?>
            <tr class="admin_row" style="height: 20px; vertical-align: middle">
            	<td style="width: 50%"><?= $mod['name'] ?></td>
            	<td style="width: 30%"><?= $mod['tipo'] ?></td>
            	<td style="width: 20%; text-align: center"><input type="checkbox" id="<?= $mod['code_name'] ?>" name="<?= $mod['code_name'] ?>" <?php if($mod['active'] == 1) print "checked" ?> onclick="upd_modulo(this)" /></td>
            </tr>
            <?php 
            	$x++;
            } 
            ?>
            </tbody>
            <tfoot>
            <tr class="admin_void">
                <td colspan="3"></td>
            </tr>
            <tr class="admin_void">
                <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            </tfoot>
        </table>
    </form>
    </div>	
	<p class="spacer"></p>
	</div>
<?php include "footer.php" ?>
</body>
</html>
