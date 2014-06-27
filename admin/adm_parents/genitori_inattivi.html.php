<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Genitori inattivi</title>
<link href="../../css/reg.css" rel="stylesheet" />
<link href="../../css/general.css" rel="stylesheet" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/mac_os_x.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript">
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
	var checkboxes = $$("#myform input[type=checkbox]");
	$('select_all').observe("click", function(event){
		checkboxes.each(function(box){
	        box.checked = $('select_all').checked;
	    });
	});
	$$('table tbody a.del_link').invoke("observe", "click", function(event){
		event.preventDefault();
		var strs = this.parentNode.id.split("_");
		del_user(strs[1], 'delete', this.readAttribute("st"));
	});
});

function del_user(id){
	if(!confirm("Sei sicuro di voler cancellare questo utente?"))
        return false;
	var url = "parent_manager.php";
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {action: 2, _i: id},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		if(dati[0] == "kosql"){
				      		sqlalert();
							console.log("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
							return;
			      		}
			      		$('row_'+id).hide();
			      		link = "genitori_inattivi.php";
			      		_alert("Utente cancellato correttamente");
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

function del_all(){
	if(!confirm("Sei sicuro di voler cancellare tutti gli utenti selezionati?"))
        return false;
	var url = "parent_manager.php";
	$('action').setValue(5);
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: $('myform').serialize(true),
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		if(dati[0] == "kosql"){
				      		sqlalert();
							console.log("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
							return;
			      		}
			      		//$('row_'+id).hide();
			      		link = "genitori_inattivi.php";
			      		_alert("Operazione completata");
			      		window.setTimeout("document.location.href = link", 2000);
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "../adm_users/menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Elenco genitori inattivi</div>
        <form id="myform" method="post">
        <table class="admin_table">
        <thead>
            <tr>
            	<td style="width: 15%; text-align: center" class="adm_titolo_elenco_first"><input type="checkbox" name="select_all" id="select_all" /></td>
                <td style="width: 45%" class="adm_titolo_elenco">Genitore</td>
                <td style="width: 40%" class="adm_titolo_elenco_last">Username</td>
                
            </tr>
            <tr class="admin_row_before_text">
                <td colspan="2"></td>
            </tr>
        </thead>
        <tbody>
            <?php
            $res_new_parents->data_seek(0);

            while($parent = $res_new_parents->fetch_assoc()){
            ?>
            <tr class="admin_row" id="row_<?php echo $parent['uid'] ?>">
            	<td style="width: 15%; text-align: center; padding-left: 10px"><input type="checkbox" name="ids[]" value="<?php echo $parent['uid'] ?>" /></td>
            	<td style="">
                	<span id="span_<?php echo $parent['uid'] ?>" class="ov_red"><?php echo $parent['cognome']." ".$parent['nome'] ?></span>
                	<div id="link_<?php echo $parent['uid'] ?>" style="display: none; vertical-align: bottom">
                	<a href="dettaglio_genitore.php?id=<?php echo $parent['uid'] ?>" class="ren_link">Modifica</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="../../shared/no_js.php" class="del_link">Elimina</a>
                	</div>
                </td>
                <td style=""><span><?php echo $parent['username'] ?></span></td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
        <tr class="admin_void">
	        <td colspan="3">&nbsp;&nbsp;&nbsp;<input type="hidden" name="action" id="action" /></td>
        </tr>
        	<tr class="admin_menu">
                <td colspan="3" >
                	<a href="#" class="standard_link nav_link_first" onclick="del_all()">Elimina selezionati</a>|
                    <a href="../index.php" class="standard_link nav_link_last">Torna al menu</a>
                </td>
            </tr>

        </tfoot>
        </table>
        </form>
        </div>
        <p class="spacer"></p>
    </div>
<?php include "../footer.php" ?>
</body>
</html>