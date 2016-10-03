<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../js/page.js"></script>
	<script type="text/javascript">

	var check = function(uid, name){
		url = "permission_check.php";

		$.ajax({
			type: "POST",
			url: url,
			data: {uid: uid},
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
					for(k = 1; k <= <?php echo $groups->num_rows ?>; k++){
						$('#gr_'+k).text("NO");
					}
					$('#is_admin').text("NO");
					$('#is_ps_admin').text("NO");
					$('#is_ms_admin').text("NO");

					$('#us_label').text(name);
					gids = json.gid;
					for(i = 0; i < gids.length; i++){
						$('#gr_'+gids[i]).text("SI");
					}
					if(json.admin == 1){
						$('#is_admin').text("SI");
					}
					if(json.psadmin == 1){
						$('#is_ps_admin').text("SI");
					}
					if(json.msadmin == 1){
						$('#is_ms_admin').text("SI");
					}
					$('#panel').show();
				}
			}
		});
	};

		$(function () {
			load_jalert();
			setOverlayEvent();
		});
	</script>
<title>Verifica permessi utente</title>
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
            <tr class="admin_void" style="border: 0; height: 5px">
                <td colspan="5"></td>
            </tr>
            </thead>
            <tbody>
            <tr class="admin_row" style="height: 20px">
            <?php 
            $x = 1;
            while($utente = $res_utenti->fetch_assoc()){
            ?>
                <td style="width: 20%; vertical-align: middle"><a href="#" onclick="check(<?php  print $utente['id'] ?>, '<?php print $utente['cognome']." ".$utente['nome'] ?>')" title="<?php  print $utente['id'] ?>" style="text-decoration: none;"><?php print $utente['cognome']." ".$utente['nome'] ?></a></td>
            <?php 
            	if($x%5 == 0){
            		print("</tr>\n\t<tr class='admin_row' style='height: 20px'>\n");
            	}
            	$x++;
            }
            ?>
            </tr>
            <tr id="panel" class="admin_row" style="border: 0; height: 5px; display: none">
                <td colspan="5">
                <p id="us_label" style="font-weight: bold"></p>
                	<?php 
                	while($g = $groups->fetch_assoc()){
                	?>
                	<p style="margin: 0"><?php echo $g['nome'] ?>: <span id="gr_<?php echo $g['gid'] ?>" style="font-weight: bold">NO</span></p>
                	<?php } ?>
                	<p style="margin: 0">isAdministrator: <span id="is_admin" style="font-weight: bold">NO</span></p>
                	<p style="margin: 0">isPrimarySchoolAdministrator: <span id="is_ps_admin" style="font-weight: bold">NO</span></p>
                	<p style="margin: 0">isMiddleSchoolAdministrator: <span id="is_ms_admin" style="font-weight: bold">NO</span></p>
                </td>
            </tr>
            </tbody>
            <tfoot>
            <tr class="admin_void">
                <td colspan="5"></td>
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
