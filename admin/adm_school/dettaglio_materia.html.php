<!DOCTYPE html>
<html>
<head>
<title>Dettaglio materia</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
<link href="../../css/general.css" rel="stylesheet" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javaScript">
var messages = new Array('', 'Materia inserita con successo', 'Materia cancellata con successo', 'Materia modificata con successo');
function go(par, sede){
    if(par == 2){
        if(!confirm("Sei sicuro di voler cancellare questa materia?"))
            return false;
    }
    $('_i').setValue(sede);
    $('action').setValue(par);
    var url = "subject_manager.php";
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: $('site_form').serialize(true),
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		if(dati[0] == "ko"){
				      		_alert("Impossibile completare l'operazione. Si prega di riprovare tra poco");
							console.log("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
							return;
			      		}
			      		
			      		link = "sedi.php?msg="+par;
			      		if(par != 1){
							link += "&second=1&offset=<?php print $offset ?>";
			      		}
			      		_alert(messages[par]);	
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

<?php include "../popup_dom.php" ?>

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
	<div class="group_head">Gestione materia</div>
    <form action="sites_manager.php" method="post" id="site_form" class="popup_form">
    <table style="width: 90%; margin: auto">
        <tr class="popup_row header_row">
            <td style="width: 30%"><label for="materia" class="popup_title">Nome</label></td>
            <td style="width: 70%">
                <input class="form_input" type="text" name="materia" id="materia" style="width: 100%" <?php if(isset($subject)) print("value='".utf8_decode($subject['materia'])."'"); else print "autofocus" ?> />
            </td>
        </tr>
        <tr class="popup_row">
            <td style="width: 30%"><label for="parent" class="popup_title">Sottomateria di</label></td>
            <td style="width: 70%">     
                <select class="form_input" name="parent" id="parent" style="width: 100%" <?php if(isset($subject) && $subject['has_sons'] == 1) echo "disabled" ?>>
                	<option value="0" selected >Nessuna</option>
                	<?php 
                	foreach ($materie as $mat) {
                		if($mat['idpadre'] == "" && $mat['id_materia'] != $subject['id_materia']){
                	?>
                	<option value="<?php echo $mat['id_materia'] ?>" <?php if($mat['id_materia'] == $subject['idpadre']) echo "selected" ?>><?php echo $mat['materia'] ?></option>
                	<?php
                		}
                	}
                	?>
                </select>
            </td>
        </tr>
        <tr class="popup_row">
            <td style="width: 30%"><label for="testo" class="popup_title">Tipologia di scuola</label></td>
            <td style="width: 70%">     
                <select class="form_input" name="tipo" id="tipo" style="width: 100%">
                	<option value="0">.</option>
                <?php 
                while ($tipo = $res_tipologie->fetch_assoc()){
                ?>
                	<option value="<?php echo $tipo['id_tipo'] ?>" <?php if(isset($subject) && ($tipo['id_tipo'] == $subject['tipologia_scuola'])) echo "selected"  ?>><?php echo $tipo['tipo'] ?></option>
                <?php } ?>
                </select>
            </td>
        </tr>
        <tr class="popup_row">
            <td style="width: 30%"><label for="report" class="popup_title">In pagella</label></td>
            <td style="width: 70%">     
                <select class="form_input" name="report" id="report" style="width: 100%">
                	<option value="0" <?php if(isset($subject) && $subject['pagella'] == 0) echo "selected" ?>>NO</option>
                	<option value="1" <?php if(isset($subject) && $subject['pagella'] == 1) echo "selected" ?>>SI</option>
                </select>
            </td>
        </tr>
        <tr class="popup_row">
            <td colspan="2">
            	<input type="hidden" name="action" id="action" />
    			<input type="hidden" name="_i" id="_i" />
            </td>
        </tr>
        <tr>
            <td colspan="2" style="margin-right: 30px; text-align: right">
                <a href="../../shared/no_js.php" id="save_button" class="standard_link nav_link">Registra</a>
            </td>
        </tr>
    </table>
   	</form>
	<p class="spacer"></p>
   	</div>
</div>
<?php include "../footer.php" ?>
</body>
</html>
