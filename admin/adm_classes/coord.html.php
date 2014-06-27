<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Coordinatore di classe</title>
<link href="../../css/reg.css" rel="stylesheet" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">

function upd_cdc(classe, sel){
    var doc = sel.value;
    if(doc == 0){
        alert("Docente non selezionato");
        return;
    }
    var url = "class_manager.php";
    var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {action: 'upgrade', cls: classe, field: sel.id, value: doc, is_char: 0},
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
			    		//alert(response);
			    		dati = response.split(";");
			    		if(dati[0] == "kosql"){
			    			sqlalert();
			                console.log("Aggiornamento non riuscito. Query: "+dati[1]+"\nErrore: "+dati[2]);
			                return;
			            }
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
	
}

document.observe("dom:loaded", function(){
	$('close_btn').observe("click", function(event){
		event.preventDefault();
		parent.win.close();
		parent.location.href = 'classi.php?second=1&school_order=<?php echo $classe['ordine_di_scuola'] ?>&offset=<?php print $_REQUEST['offset'] ?>';
	});
	$('coordinatore').observe("change", function(event){
		upd_cdc(<?php print $classID ?>, this);
	});
	$('segretario').observe("change", function(event){
		upd_cdc(<?php print $classID ?>, this);
	});
});		
</script>
<style type="text/css">TD {height: 11px}</style>
</head>
<body style="background-color: white; margin: 0; background-image: none">
    <p style="text-align: center; font-size: 1.1em; font-weight: bold; margin-top: 10px">Coordinatore di classe: <?php print $classe['anno_corso'].$classe['sezione'] ?> - <?php echo $classe['nome'] ?></p>
    <form action="cdc.php?upd=1" method="post">
    <div style="text-align: left">
    <table style="width: 420px; margin: auto">
    	<tr>
            <td class="popup_title" style="width: 230px; padding-top: 1px; padding-bottom: 1px; font-weight: bold">Coordinatore</td>
            <td style="width: 190px; padding-top: 5px; padding-bottom: 5px">
                <select name="coordinatore" id="coordinatore" style="width: 180px; font-size: 11px">
                    <option value="0">Nessuno</option>
    <?php
    	while($dc = $res_coord->fetch_assoc()){
        	if($classe['coordinatore'] == $dc['uid']){
    ?>
                    <option value="<?php print $dc['uid'] ?>" selected="selected"><?php print $dc['cognome']." ".$dc['nome'] ?></option>
    <?php
        	}
    		else{
    ?>
    				<option value="<?php print $dc['uid'] ?>"><?php print $dc['cognome']." ".$dc['nome'] ?></option>
    <?php 		}
            }
    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="popup_title" style="width: 230px; padding-top: 1px; padding-bottom: 1px; font-weight: bold">Segretario</td>
            <td style="width: 190px; padding-top: 5px; padding-bottom: 5px">
                <select name="segretario" id="segretario" style="width: 180px; font-size: 11px">
                    <option value="0">Nessuno</option>
    <?php
    	while($dc = $res_seg->fetch_assoc()){
        	if($classe['segretario'] == $dc['uid']){
    ?>
                    <option value="<?php print $dc['uid'] ?>" selected="selected"><?php print $dc['cognome']." ".$dc['nome'] ?></option>
    <?php
        	}
    		else{
    ?>
    				<option value="<?php print $dc['uid'] ?>"><?php print $dc['cognome']." ".$dc['nome'] ?></option>
    <?php 		}
            }
    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="height: 15px">&nbsp;&nbsp;&nbsp;</td>
        </tr>
    </table>
    <div style="margin: auto; width: 410px; text-align: right">
        <a href="../../shared/no_js.php" id="close_btn" class="standard_link">Chiudi</a>
    </div>
    </div>
   </form>
</body>
</html>