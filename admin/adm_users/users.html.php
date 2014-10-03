<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Elenco utenti</title>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" />
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

				}
			});
		};

		var go = function(){
			var url = "users.php?filter=nome";
			if(document.forms[0].nome.value != "")
				url += "&nome="+document.forms[0].nome.value;
			document.location.href = url;
		};

		<?php echo $page_menu->getJavascript() ?>

		$(function(){
			load_jalert();
			$('table tbody > tr').mouseover(function(event){
					//alert(this.id);
					var strs = this.id.split("_");
					$('#link_'+strs[1]).show();
			});
			$('table tbody > tr').mouseout(function(event){
					//alert(this.id);
					var strs = this.id.split("_");
					$('#link_'+strs[1]).hide();
			});
			$('table tbody a.del_link').click(function(event){
				event.preventDefault();
				var strs = this.parentNode.id.split("_");
				del_user(strs[1]);
			});
			$('#filter_button').click(function(event){
				event.preventDefault();
				filter();
			});
			$('#go_link').click(function(event){
				event.preventDefault();
				go();
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
		<div class="group_head"><div style="float: left"><?php $page_menu->printLink() ?></div>Elenco Utenti: pagina <?php print $page ?> di <?php print $pagine ?></div>
		<?php $page_menu->toHTML() ?>
		<table class="admin_table">
        <thead>
            <tr>
                <td style="width: 40%" class="adm_titolo_elenco_first">Nome e cognome</td>
                <td style="width: 20%" class="adm_titolo_elenco">Username</td>
                <td style="width: 40%" class="adm_titolo_elenco_last _center">Gruppi</td>
            </tr>
            <tr class="admin_row_before_text">
                <td colspan="3"></td>
            </tr>
        </thead>
        <tbody>
            <?php
            $x = 1;
            if($res_user->num_rows > $limit)
                $max = $limit;
            else
                $max = $res_user->num_rows;

            while($user = $res_user->fetch_assoc()){
                if($x > $limit) break;
                // estraggo i gruppi di appartenenza
                $sel_gruppi = "SELECT rb_gruppi.gid, nome, codice FROM rb_gruppi, rb_gruppi_utente WHERE rb_gruppi.gid = rb_gruppi_utente.gid AND rb_gruppi_utente.uid = {$user['uid']}";
                $res_gruppi = $db->execute($sel_gruppi);
                $gruppi = "";
                while($g = $res_gruppi->fetch_assoc()){
                    $gruppi .= $g['nome'].", ";
                }
                $gruppi = substr($gruppi, 0, ($gruppi - 2));
            ?>
            <tr class="admin_row" id="row_<?php echo $user['uid'] ?>">
                <td style="padding-left: 10px; ">
                	<span class="ov_red" style="font-weight: bold"><?php echo $user['cognome']." ".$user['nome'] ?></span>
                	<div id="link_<?php echo $user['uid'] ?>" style="display: none">
                	<a href="dettaglio_utente.php?id=<?php echo $user['uid'] ?>&offset=<?php echo $offset ?>" class="mod_link">Modifica</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="users_manager.php?action=2&id=<?php echo $user['uid'] ?>" class="del_link">Cancella</a>
                	</div>
                </td>
                <td><?php echo $user['username'] ?></td>
                <td class="_center"><?php echo $gruppi ?></td>
            </tr>
            <?php
                $x++;
            }
            ?>
            </tbody>
            <tfoot>
            <?php 
            include "../../shared/navigate.php";
            ?>
            <tr class="admin_menu">
                <td colspan="3">
                	<a href="dettaglio_utente.php?id=0" id="add" class="standard_link nav_link">Nuovo utente</a>
                </td>
            </tr>
        </tfoot>
        </table>
        </div>
        <p class="spacer"></p>
	</div>
<?php include "../footer.php" ?>
<div id="listfilter" style="display: none; width: 450px">
	<form action="#" method="post">
		<fieldset style="width: 350px; border: 1px solid #BBB; margin-top: 15px; margin-left: auto; margin-right: auto">
			<legend style="font-weight: bold;">Parametri di ricerca</legend>
			<table style="width: 350px; margin-left: auto; margin-right: auto; margin-top: 10px">
				<tr>
					<td class="popup_title" align="left" style="width: 150px">Nome</td>
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
			<a href="../../shared/no_js.php" id="go_link" class="standard_link nav_link_first" style="color: #003366">Estrai</a>
		</div>
	</form>
</div>
</body>
</html>
