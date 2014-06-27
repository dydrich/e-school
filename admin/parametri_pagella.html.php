<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Elenco parametri pagella</title>
<link href="../css/reg.css" rel="stylesheet" />
<link href="../css/general.css" rel="stylesheet" />
<link href="../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/scriptaculous.js"></script>
<script type="text/javascript" src="../js/page.js"></script>
<script type="text/javascript" src="../js/window.js"></script>
<script type="text/javascript" src="../js/window_effects.js"></script>
<script type="text/javascript">
var index = 0;
<?php 
if(isset($_REQUEST['msg'])){
?>
index = <?php print $_REQUEST['msg'] ?>;
<?php } ?>

function del_sede(id){
	if(!confirm("Sei sicuro di voler cancellare questo parametro?"))
        return false;
	var url = "params_manager.php";
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
			      		_alert("Parametro cancellato");
			      		$("row_"+id).hide();
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
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "scr_menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Elenco parametri</div>
		<table class="admin_table">
		<thead>
            <tr>
                <td class="adm_titolo_elenco_first">Nome</td>
            </tr>
            <tr class="admin_row_before_text">
                <td></td>
            </tr>
		</thead>
		<tbody id="t_body">
            <?php
            while($param = $res_params->fetch_assoc()){
               
            ?>
            <tr class="admin_row" id="row_<?php echo $param['id'] ?>">
                <td style="padding-left: 10px; ">
                	<span class="ov_red" style="font-weight: bold" id="param_mod"><?php echo $param['nome'] ?></span>
                	<div id="link_<?php echo $param['id'] ?>" style="display: none">
                	<a href="dettaglio_parametro.php?id=<?php echo $param['id'] ?>&school=<?php echo $school_order ?>" class="mod_link">Modifica</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="valori_parametro.php?id=<?php echo $param['id'] ?>&school=<?php echo $school_order ?>" class="mod_par">Modifica valori</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="params_manager.php?action=2&_id=<?php echo $param['id'] ?>&school=<?php echo $school_order ?>" class="del_link">Cancella</a>
                	
                	</div>
                </td>
            </tr>
            <?php
            }
            ?>
            </tbody>
            <tfoot>
            <tr class="admin_menu">
                <td>
                    <a href="dettaglio_parametro.php?id=0&school=<?php echo $school_order ?>" id="new_site" class="standard_link nav_link_first">Nuovo parametro</a>|
                    <a href="index.php" class="standard_link nav_link_last">Torna indietro</a>
                </td>
            </tr>
            <tr class="admin_void">
                <td></td>
            </tr>
        </tfoot>
        </table>
        </div>
        <p class="spacer"></p>
    </div>
<?php include "footer.php" ?>
</body>
</html>