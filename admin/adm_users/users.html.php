<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Elenco utenti</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css" />
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">

		var del_user = function(id){
			if(!confirm("Sei sicuro di voler cancellare questo utente?"))
		        return false;
			var url = "users_manager.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {action: 2, _i: id},
				dataType: 'json',
				error: function() {
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
						j_alert("error", json.message);
						console.log(json.dbg_message);
					}
					else {
						j_alert("alert", json.message);
						$('#row_'+id).hide();
					}
				}
			});
		};

		var filter = function(){
			$('#drawer').hide();
			$('#listfilter').dialog({
				autoOpen: true,
				show: {
					effect: "appear",
					duration: 200
				},
				hide: {
					effect: "slide",
					duration: 200
				},
				modal: true,
				width: 450,
				height: 350,
				title: 'Filtra elenco',
				open: function(event, ui){

				},
				close: function(event) {
					$('#overlay').hide();
				}
			});
		};

		var go = function(){
			var url = "users.php?filter=nome";
			if(document.forms[0].nome.value != "")
				url += "&nome="+document.forms[0].nome.value;
			document.location.href = url;
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('a.del_link').click(function(event){
				event.preventDefault();
				var strs = this.parentNode.id.split("_");
				del_user(strs[1]);
			});
			$('#go_link').click(function(event){
				event.preventDefault();
				go();
			});
			$('#open_search').click(function(event){
				event.preventDefault();
				filter();
			});
			$('#top_btn').click(function() {
				$('html,body').animate({
					scrollTop: 0
				}, 700);
				return false;
			});

			var amountScrolled = 200;

			$(window).scroll(function() {
				if ($(window).scrollTop() > amountScrolled) {
					$('#plus_btn').fadeOut('slow');
					$('#float_btn').fadeIn('slow');
					$('#top_btn').fadeIn('slow');
				} else {
					$('#float_btn').fadeOut('slow');
					$('#plus_btn').fadeIn();
					$('#top_btn').fadeOut('slow');
				}
			});
		});

	</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col">
		<div style="position: absolute; top: 75px; left: 49%; margin-bottom: -5px" class="rb_button">
			<a href="#" id="open_search">
				<img src="../../images/7.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<div style="position: absolute; top: 75px; left: 53%; margin-bottom: -5px" class="rb_button">
			<a href="dettaglio_utente.php?id=0">
				<img src="../../images/39.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<div class="card_container" style="margin-top: 20px">
        <?php
        $x = 1;

        while($user = $res_user->fetch_assoc()){
            // estraggo i gruppi di appartenenza
            $sel_gruppi = "SELECT rb_gruppi.gid, nome, codice FROM rb_gruppi, rb_gruppi_utente WHERE rb_gruppi.gid = rb_gruppi_utente.gid AND rb_gruppi_utente.uid = {$user['uid']}";
            $res_gruppi = $db->execute($sel_gruppi);
            $gruppi = "";
            while($g = $res_gruppi->fetch_assoc()){
                $gruppi .= $g['nome'].", ";
            }
            $gruppi = substr($gruppi, 0, ($gruppi - 2));
	        $area = 'school';
	        if ($gruppi == "genitori") {
		        $area = "parents";
	        }
        ?>
	        <div class="card" id="row_<?php echo $user['uid'] ?>">
		        <div class="card_title">
			        <a href="dettaglio_utente.php?id=<?php echo $user['uid'] ?>" class="mod_link"><?php echo $user['cognome']." ".$user['nome'] ?></a>
			        <div style="float: right; margin-right: 20px" id="del_<?php echo $user['uid'] ?>">
				        <a href="users_manager.php?action=2&id=<?php echo $user['uid'] ?>" class="del_link">
					        <img src="../../images/51.png" style="position: relative; bottom: 2px" />
				        </a>
			        </div>
			        <div style="float: right; margin-right: 120px; text-align: left; width: 200px; text-transform: none" class="normal">
				        <a href="modifica_account.php?uid=<?php echo $user['uid'] ?>&area=<?php echo $area ?>" class="normal"><?php echo $user['username'] ?></a>
			        </div>
		        </div>
		        <div class="card_minicontent">
			        Gruppi: <?php echo $gruppi ?>
		        </div>
	        </div>
        <?php
            $x++;
        }
        ?>
		</div>
    </div>
    <p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="../../index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="../index.php"><img src="../../images/31.png" style="margin-right: 10px; position: relative; top: 5%" />Admin</a></div>
		<div class="drawer_link"><a href="http://www.istitutoiglesiasserraperdosa.it"><img src="../../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<div id="listfilter" style="display: none; width: 450px">
	<form action="#" method="post">
		<fieldset style="width: 350px; border: 1px solid #BBB; margin-top: 15px; margin-left: auto; margin-right: auto">
			<legend style="font-weight: bold;">Parametri di ricerca</legend>
			<table style="width: 350px; margin-left: auto; margin-right: auto; margin-top: 10px">
				<tr>
					<td class="popup_title" align="left" style="width: 150px">Cognome</td>
					<td style="width: 200px">
						<input type="text" name="nome" style="width: 199px; font-size: 11px" value="" />
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;&nbsp;&nbsp;</td>
				</tr>
			</table>
		</fieldset>
		<div style="width: 350px; margin-left: 15px; margin-top: 20px; margin-bottom: 20px; text-align: right">
			<a href="../../shared/no_js.php" id="go_link" class="material_link nav_link_first" style="color: #003366">Estrai</a>
		</div>
	</form>
</div>
<a href="dettaglio_utente.php?id=0" id="float_btn" class="rb_button float_button">
	<i class="fa fa-pencil"></i>
</a>
<a href="#" id="top_btn" class="rb_button float_button top_button">
	<i class="fa fa-arrow-up"></i>
</a>
</body>
</html>
