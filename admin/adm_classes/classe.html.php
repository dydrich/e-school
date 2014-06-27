<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Dettaglio classe</title>
	<link href="../../css/reg.css" rel="stylesheet" />
	<link href="../../css/general.css" rel="stylesheet" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
function upd_cls(){
	if($F('anno_corso') < 0 || $F('sezione') == -1 || $F('sede') == -1){
		alert("I campi anno, sezione e sede sono obbligatori");
		return false;
	}
    var url = "class_manager.php";
    $('action').value = "insert";
    var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: $('myform').serialize(true),
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
			    		//alert(response);
			    		dati = response.split("|");
			    		if(dati[0] != "ko"){
							alert("Operazione conclusa con successo");
			            }
			            else{
			                alert("Operazione non riuscita. Si prega di riprovare tra qualche minuto.");
			                console.log(" Query: "+dati[1]+"\nErrore: "+dati[2]);
			                return;
			            }
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
	
}

var upd_field = function(field){
	is_char = 0;
	name = field.name;
	if(name == "sezione"){
		is_char = 1;
	}
	value = field.value;
	if(value < 1){
		return;
	}
	if(field.type == "checkbox"){
		if(field.checked){
			value = 1;
		}
		else{
			value = 0;
		}
	}
	var url = "class_manager.php";
    var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {action: 'upgrade', field: name, value: value, is_char: is_char, cls: <?php echo $_REQUEST['id'] ?>},
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
			    		dati = response.split("|");
			    		if(dati[0] != "ko"){
							//alert(response);
			            }
			            else{
			                alert("Aggiornamento non riuscito. Si prega di riprovare tra qualche minuto.");
			                console.log(" Query: "+dati[1]+"\nErrore: "+dati[2]);
			                return;
			            }
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};

document.observe("dom:loaded", function(){
	$('go_link').observe("click", function(event){
		event.preventDefault();
		upd_cls();
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
		<div class="group_head"><?php echo $label ?></div>
	    <form action="class_manager.php" method="post" class="popup_form" id="myform">
	    <div style="text-align: left">
	    <table style="width: 95%; margin: auto">
	    	<tr>
	        	<td class="popup_title" style="width: 50%">Anno corso</td>
	            <td style="width: 50%; padding-top: 3px; padding-bottom: 3px">
	                <select name="anno_corso" id="anno_corso" style="width: 90%; font-size: 11px" <?php if(isset($cls)){ ?>onchange="upd_field(this)"<?php } ?>>
	                    <option value="-1">.</option>
	                    <option value="1" <?php if($cls && $cls->get_anno() == 1) echo "selected" ?>>1</option>
	                    <option value="2" <?php if($cls && $cls->get_anno() == 2) echo "selected" ?>>2</option>
	                    <option value="3" <?php if($cls && $cls->get_anno() == 3) echo "selected" ?>>3</option>
	                    <?php if($admin_level != MIDDLE_SCHOOL){ ?>
	                    <option value="4" <?php if($cls && $cls->get_anno() == 4) echo "selected" ?>>4</option>
	                    <option value="5" <?php if($cls && $cls->get_anno() == 5) echo "selected" ?>>5</option>
	                    <?php } ?>
	    		    </select>
	            </td>
	        </tr>
	    	<tr>
	        	<td class="popup_title" style="width: 50%">Sezione</td>
	            <td style="width: 50%; padding-top: 3px; padding-bottom: 3px">
	                <select name="sezione" id="sezione" style="width: 90%; font-size: 11px" <?php if(isset($cls)){ ?>onchange="upd_field(this)"<?php } ?>>
	                    <option value="-1">.</option>
	                    <?php 
	                    foreach ($sezioni as $sez){
	                    ?>
	                    <option value="<?php echo $sez ?>" <?php if($cls && $cls->get_sezione() == $sez) echo "selected" ?>><?php echo $sez ?></option>
	                    <?php 
	                    }
	                    ?>
	    		    </select>
	            </td>
	        </tr>
	        <tr>
	        	<td class="popup_title" style="width: 50%">Sede</td>
	            <td style="width: 50%; padding-top: 3px; padding-bottom: 3px">
	                <select name="sede" id="sede" style="width: 90%; font-size: 11px" <?php if(isset($cls)){ ?>onchange="upd_field(this)"<?php } ?>>
	                    <?php if($res_sedi->num_rows > 1){ ?>
	                    <option value="-1">.</option>
	                    <?php } ?>
	                    <?php 
	                    while($sede = $res_sedi->fetch_assoc()){
	                    ?>
	                    <option value="<?php echo $sede['id_sede'] ?>" <?php if($cls && $cls->get_sede() == $sede['id_sede']) echo "selected" ?>><?php echo $sede['nome'] ?></option>
	                    <?php 
	                    }
	                    ?>
	    		    </select>
	            </td>
	        </tr>
	        <tr>
	        	<td class="popup_title" style="width: 50%">Ordine di scuola </td>
	            <td style="width: 50%; padding-top: 3px; padding-bottom: 3px">
	                <select name="ordine_di_scuola" id="ordine_di_scuola" style="width: 90%; font-size: 11px" <?php if(isset($cls)){ ?>onchange="upd_field(this)"<?php } ?>>
	                    <?php if($res_ordini->num_rows > 1){ ?>
	                    <option value="-1">.</option>
	                    <?php } ?>
	                    <?php 
	                    while($ordine = $res_ordini->fetch_assoc()){
	                    ?>
	                    <option value="<?php echo $ordine['id_tipo'] ?>" <?php if($cls && $cls->getSchoolOrder() == $ordine['id_tipo']) echo "selected" ?>><?php echo $ordine['tipo'] ?></option>
	                    <?php 
	                    }
	                    ?>
	    		    </select>
	            </td>
	        </tr>
	        <tr>
	        	<td class="popup_title" style="width: 50%">Modulo orario</td>
	            <td style="width: 50%; padding-top: 3px; padding-bottom: 3px">
	                <select name="modulo_orario" id="modulo_orario" style="width: 90%; font-size: 11px" <?php if(isset($cls)){ ?>onchange="upd_field(this)"<?php } ?>>
	                    <?php if($res_modules->num_rows > 1){ ?>
	                    <option value="-1">.</option>
	                    <?php } ?>
	                    <?php 
	                    while($module = $res_modules->fetch_assoc()){
	                    ?>
	                    <option value="<?php echo $module['id_modulo'] ?>" <?php if($cls && $cls->get_modulo_orario()->getID() == $module['id_modulo']) echo "selected" ?>>Mod. <?php echo $module['id_modulo'] ?> (<?php echo $module['giorni'] ?> giorni - <?php echo $module['ore_settimanali'] ?> ore)</option>
	                    <?php 
	                    }
	                    ?>
	    		    </select>
	    		    <?php if($res_modules->num_rows == 1){ ?>
					<script type="text/javascript">
					upd_field($('modulo_orario'));
					</script>
					<?php } ?>
	            </td>
	        </tr>
	        <tr>
	        	<td class="popup_title" style="width: 50%">Tempo prolungato</td>
	            <td style="width: 50%; padding-top: 3px; padding-bottom: 3px">
	                <input type="checkbox" id="tempo_prolungato" name="tempo_prolungato" value="1" <?php if($cls && $cls->isFullTime()) echo "checked" ?>  <?php if(isset($cls)){ ?>onchange="upd_field(this)"<?php } ?>/>
	            </td>
	        </tr>
	        <tr>
	        	<td class="popup_title" style="width: 50%">Corso musicale</td>
	            <td style="width: 50%; padding-top: 3px; padding-bottom: 3px">
	                <input type="checkbox" id="musicale" name="musicale" value="1" <?php if($cls && $cls->isMusicale()) echo "checked" ?>  <?php if(isset($cls)){ ?>onchange="upd_field(this)"<?php } ?>/>
	            </td>
	        </tr>
	        <tr>
	            <td colspan="2" style="height: 15px">&nbsp;&nbsp;&nbsp;
	            	<input type="hidden" id="cls" name="cls" value="<?php echo $_REQUEST['id'] ?>" />
	            	<input type="hidden" id="action" name="action" value="" />
	            </td>
	        </tr>
	    </table>
	    <div style="margin-right: 10px; width: 95%; text-align: right">
	    	<?php if($_REQUEST['id'] == 0){ ?><a href="../../shared/no_js.php" id="go_link" class="standard_link nav_link_first">Salva le modifiche</a>|<?php } ?>
	        <a href="classi.php?school_order=<?php echo $_GET['school_order'] ?><?php if($offset != 0) echo "&second=1&offset={$offset}" ?>" id="close_btn" class="nav_link_last standard_link">Torna all'elenco classi</a>
	    </div>
	    </div>
		</form>
	</div>
	<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
</body>
</html>