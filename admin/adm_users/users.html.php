<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Elenco utenti</title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript">
var messages = new Array('', 'Utente inserito con successo', 'Utente cancellato con successo', 'Utente modificato con successo');
var index = 0;
<?php 
if(isset($_REQUEST['msg'])){
?>
index = <?php print $_REQUEST['msg'] ?>;
<?php } ?>

function del_user(id){
	if(!confirm("Sei sicuro di voler cancellare questo utente?"))
        return false;
	var url = "users_manager.php";
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {action: 2, _i: id},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		if(dati[0] == "ko"){
							alert("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
							return;
			      		}
			      		link = "users.php?msg=2&second=1&offset=<?php print $offset ?>";
			      		//alert(link);
			      		document.location.href = link;
			      		//parent.win.close();
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

document.observe("dom:loaded", function(){
	$$('table tbody > tr').invoke("observe", "mouseover", function(event){
			//alert(this.id);
			var strs = this.id.split("_");
			$('link_'+strs[1]).setStyle({display: 'block'});
	});
	$$('table tbody > tr').invoke("observe", "mouseout", function(event){
			//alert(this.id);
			var strs = this.id.split("_");
			$('link_'+strs[1]).setStyle({display: 'none'});
	});
	$$('table tbody a.del_link').invoke("observe", "click", function(event){
		event.preventDefault();
		var strs = this.parentNode.id.split("_");
		del_user(strs[1]);
	});
});

</script>
</head>
<body <?php if(isset($_REQUEST['msg'])){ ?>onload="openInfoDialog(messages[<?php print $_REQUEST['msg'] ?>], 2)"<?php } ?>>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Elenco Utenti: pagina <?php print $page ?> di <?php print $pagine ?></div>
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
</body>
</html>
