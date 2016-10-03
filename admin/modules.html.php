<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../css/general.css" type="text/css" />
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
			setOverlayEvent();
			$(":checkbox").button();
			$(":checkbox").click(function(event) {
				mod = $(this).attr("data-mod");
				if ($(this).prop("checked") == true) {
					$('#label'+mod+ " span").text("ON ");
				}
				else {
					$('#label'+mod+ " span").text("OFF");
				}
			});
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
    <form class="no_border">
        <table class="admin_table">
        	<thead>
            </thead>
            <tbody>
            <tr class="accent_decoration _bold">
            	<td style="width: 50%">Modulo</td>
            	<td style="width: 30%">Tipo</td>
            	<td style="width: 20%; text-align: center">Installato</td>
            </tr>
            <tr class="admin_void">
                <td colspan="3"></td>
            </tr>
            <?php
            $x = 1;
            while($mod = $res_modules->fetch_assoc()){
            ?>
            <tr class="admin_row" style="height: 20px; vertical-align: middle">
            	<td style="width: 50%"><?php echo $mod['name'] ?></td>
            	<td style="width: 30%"><?php echo $mod['tipo'] ?></td>
            	<td style="width: 20%; text-align: center">
		            <input type="checkbox" id="<?php echo $mod['code_name'] ?>" data-mod="<?php echo $mod['code_name'] ?>" name="<?php echo $mod['code_name'] ?>" <?php if($mod['active'] == 1) print "checked" ?> onclick="upd_modulo(this)" />
		            <label for="<?php echo $mod['code_name'] ?>" id="label<?php echo $mod['code_name'] ?>" style="font-size: 0.8em"><?php if($mod['active'] == 1) echo "ON "; else echo "OFF" ?></label>
	            </td>
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
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="../index.php"><img src="../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="index.php"><img src="../images/31.png" style="margin-right: 10px; position: relative; top: 5%" />Admin</a></div>
		<div class="drawer_link"><a href="https://www.istitutoiglesiasserraperdosa.gov.it"><img src="../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../shared/do_logout.php"><img src="../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
