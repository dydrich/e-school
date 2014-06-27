<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Elenco sedi</title>
<link rel="stylesheet" href="../../css/reg.css" type="text/css" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript">
var messages = new Array('', 'Sede inserita con successo', 'Sede cancellata con successo', 'Sede modificata con successo');
var index = 0;
<?php 
if(isset($_REQUEST['msg'])){
?>
index = <?php print $_REQUEST['msg'] ?>;
<?php } ?>

function del_sede(id){
	if(!confirm("Sei sicuro di voler cancellare questa sede?"))
        return false;
	var url = "sites_manager.php";
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
			      		link = "sedi.php?msg=2&second=1&offset=<?php print $offset ?>";
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
			del_sede(strs[1]);
	});
});

</script>
<title>Registro elettronico</title>
</head>
<body <?php if(isset($_REQUEST['msg'])) print("onload='openInfoDialog(messages[".$_REQUEST['msg']."], 2)'") ?>>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Elenco sedi: pagina <?php print $page ?> di <?php print $pagine ?></div>
		<table class="admin_table">
		<thead>
            <tr>
                <td style="width: 45%" class="adm_titolo_elenco_first">Sede</td>
                <td style="width: 55%" class="adm_titolo_elenco">Indirizzo</td>
            </tr>
            <tr class="admin_row_before_text">
                <td colspan="2"></td>
            </tr>
		</thead>
		<tbody id="t_body">
            <?php
            $x = 1;
            while($sede = $res_sedi->fetch_assoc()){
            ?>
            <tr class="admin_row" id="row_<?php echo $sede['id_sede'] ?>">
                <td style="padding-left: 10px; ">
                	<span class="ov_red" style="font-weight: bold"><?php echo $sede['nome'] ?></span>
                	<div id="link_<?php echo $sede['id_sede'] ?>" style="display: none">
                	<a href="dettaglio_sede.php?id=<?php echo $sede['id_sede'] ?>" class="mod_link">Modifica</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="sites_manager.php?action=2&_id=<?php echo $sede['id_sede'] ?>" class="del_link">Cancella</a>
                	</div>
                </td>
                <td><?php echo $sede['indirizzo'] ?></td>
            </tr>
            <?php
                $x++;
            }
            ?>
            </tbody>
            <tfoot>
            <tr class="admin_menu">
                <td colspan="2">
                    <a href="dettaglio_sede.php?id=0" id="new_site" class="standard_link nav_link_last">Nuova sede</a>
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