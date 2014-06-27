<!DOCTYPE html>
<html>
<head>
<title>Dettaglio news</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../../css/main.css" rel="stylesheet" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javaScript">
function go(par, id){
    if(par == 2){
        if(!confirm("Sei sicuro di voler cancellare questo status?"))
            return false;
    }
    $('_i').value = id;
    $('action').value = par;
    var url = "<?php print $_SESSION['__config__']['root_site'] ?>admin/adm_workflow/status_manager.php";
    //alert(url);
	var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: $('wf_form').serialize(true),
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		if(dati[0] == "ko"){
							alert("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
							return;
			      		}
			      		parent.win.close();
			      		link = "status.php?msg="+par;
			      		parent.document.location.href = link;
			      		//parent.win.close();
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}
</script>
</head>
<body <?php if(isset($msg)) print("onLoad=\"alert('".$msg."'); window.close(); window.opener.document.location.href='status.php'\"") ?>>
    <p class="popup_header">Gestione status workflow</p>
    <form action="dettaglio_status.php?upd=1" method="post" id="wf_form">
    <div style="margin-right: auto; margin-left: auto; margin-top: 10px; width: 500px">
    	<fieldset style="width: 98%; border: 1px solid; padding-top: 10px; margin-right: auto; margin-left: auto">
	    <legend style="font-weight: bold;">Status workflow</legend>
	    <table style="width: 430px; margin-right: auto; margin-left: auto">
	        <tr class="popup_row header_row">
	            <td class="popup_title" style="width: 150px; padding-left: 10px;">Nome</td>
	            <td style="width: 350px; ">
	            	<input class="form_input" type="text" name="nome_status" style="width: 350px" value="<?php if(isset($status)) print $status ?>"  />
	            </td>
	        </tr>
	        <tr class="popup_row">
	        	<td class="popup_title" align="left" style="width: 150px; padding-left: 10px">Permessi</td>
	            <td style="width: 350px">
	                <?php
	                while($_uf = $res_uffici->fetch_assoc()){
	                    $checked = "";
	                    if(isset($_GET['id']) && $_GET['id'] != 0){
	                        if($_uf['codice_permessi']&$perms)
	                        //if($user['gruppi']&$gr['codice'])
	                            $checked = "checked";
	                    }
	                ?>
	                <input class="form_input" type="checkbox" style="margin: auto" value="<?php print $_uf['codice_permessi'] ?>" name="permessi[]" <?php print $checked ?> />&nbsp;&nbsp;&nbsp;<?php print $_uf['nome'] ?>&nbsp;&nbsp;&nbsp;
	                <?php } ?>
	            </td>
	        </tr>
	        <tr class="popup_row">
	            <td colspan="2">
	            <input type="hidden" name="action" id="action" />
    			<input type="hidden" name="_i" id="_i" />
	            </td>
	        </tr>
	    </table>
	    </fieldset>
	    <div style="width: 485px; text-align: right; margin-top: 20px">
	    	<a href="#" onclick="go(<?php if(isset($_GET['id']) && $_GET['id'] != 0) print("3, ".$_REQUEST['id']); else print("1, 0"); ?>)" class="nav_link_first">Registra</a>|
	    	<?php if(isset($_GET['id']) && $_GET['id'] != 0){ ?>
	        <a href="#" onclick="go(2, <?php print $_REQUEST['id'] ?>)" class="nav_link">Cancella lo status</a>|
	        <?php } ?>
	        <a href="#" onclick="parent.win.close()" class="nav_link_last">Chiudi</a>        
	    </div>
    </div>
    </form>
</body>
</html>