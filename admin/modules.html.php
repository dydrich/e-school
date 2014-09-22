<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../js/page.js"></script>
	<script type="text/javascript">

	var upd_modulo = function(cbox){
		var url = "aggiorna_modulo.php";

		$.ajax({
			type: "POST",
			url: url,
			data: {field: cbox.name, value: cbox.checked},
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
				else {
					j_alert("alert", json.message);
				}
			}
		});
	};

	$(function(){
		load_jalert();
	});
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
