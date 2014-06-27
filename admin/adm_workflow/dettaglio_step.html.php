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
        if(!confirm("Sei sicuro di voler cancellare questo step?"))
            return false;
    }
    $('_i').value = id;
    $('action').value = par;
    var url = "<?php print $_SESSION['__config__']['root_site'] ?>admin/adm_workflow/step_manager.php";
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
			      		link = "step.php?msg="+par;
			      		parent.document.location.href = link;
			      		//parent.win.close();
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}
</script>
</head>
<body onload="document.forms[0].nome_step.focus()">
    <p class="popup_header">Gestione step workflow</p>
    <form action="dettaglio_step.php?upd=1" method="post" id="wf_form">
    <div style="margin-right: auto; margin-left: auto; margin-top: 00px; width: 450px">
    	<fieldset style="width: 450px; border: 1px solid; padding-top: 10px; ">
	    <legend style="font-weight: bold;">Step workflow</legend>
	    <table style="width: 430px; margin-right: auto; margin-left: auto">
	        <tr class="popup_row header_row">
	            <td class="popup_title" style="width: 150px;padding-left: 10px;">Nome</td>
	            <td style="width: 280px; ">
	            	<input class="form_input" type="text" name="nome_step" style="width: 280px" value="<?php if(isset($step)) print utf8_decode($step['descrizione']) ?>"  />
	            </td>
	        </tr>
	        <tr class="popup_row">
	        	<td class="popup_title" style="width: 150px; padding-left: 10px">Ufficio</td>
	            <td style="width: 280px">
	                <select class="form_input" name="ufficio" style="width: 280px; color: #777777;">
	                <?php
					while($uf = $res_uffici->fetch_assoc()){
	                ?>	
	                <option value="<?php print $uf['id_ufficio'] ?>" <?php if($uf['id_ufficio'] == $step['ufficio']) print "selected='selected'" ?>><?php print $uf['nome'] ?></option>
	                <?php
					}
	                ?>
	                </select>
	            </td>
	        </tr>
	        <tr class="popup_row header_row">
	            <td colspan="2">
	            	<input type="hidden" name="action" id="action" />
    				<input type="hidden" name="_i" id="_i" />
	            </td>
	        </tr>
	    </table>
	    </fieldset>
	    <div style="width: 450px; text-align: right; margin-top: 20px">
	    	<a href="#" onclick="go(<?php if(isset($_GET['id']) && $_GET['id'] != 0) print("3, ".$_REQUEST['id']); else print("1, 0"); ?>)" class="nav_link_first">Registra</a>|
	    	<?php if(isset($_GET['id']) && $_GET['id'] != 0){
	        ?>
	        <a href="#" onclick="go(2, <?php print $_REQUEST['id'] ?>)" class="nav_link">Cancella lo step</a>|
	        <?php
	        }
	        ?>
	        <a href="#" onclick="parent.win.close()" class="nav_link_last">Chiudi</a>
	    </div>
    </div>
    </form>
</body>
</html>