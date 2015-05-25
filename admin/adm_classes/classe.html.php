<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Dettaglio classe</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link href="../../css/general.css" rel="stylesheet" />
	<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var upd_cls = function(){
			if($('#anno_corso').val() < 0 || $('#sezione').val() == -1 || $('#sede').val() == -1){
				alert("I campi anno, sezione e sede sono obbligatori");
				return false;
			}
		    var url = "class_manager.php";
		    $('#action').val("insert");
			$.ajax({
				type: "POST",
				url: url,
				data: $('#myform').serialize(true),
				dataType: 'json',
				error: function() {
					show_error("Errore di trasmissione dei dati");
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
						alert(json.message);
						console.log(json.dbg_message);
					}
					else {
						alert("Operazione conclusa con successo");
					}
				}
			});
		};

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
			$.ajax({
				type: "POST",
				url: url,
				data: {action: 'upgrade', field: name, value: value, is_char: is_char, cls: <?php echo $_REQUEST['id'] ?>},
				dataType: 'json',
				error: function() {
					show_error("Errore di trasmissione dei dati");
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
						alert(json.message);
						console.log(json.dbg_message);
					}
					else {
						alert("Operazione conclusa con successo");
					}
				}
			});
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#go_link').button();
			$('#go_link').click(function(event){
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
		<div style="position: absolute; top: 75px; margin-left: 625px; margin-bottom: -5px" class="rb_button">
			<a href="classi.php?offset=<?php echo $offset ?>&school_order=<?php echo $_GET['school_order'] ?>">
				<img src="../../images/47bis.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<form action="class_manager.php" method="post" class="popup_form" id="myform">
	    <div style="text-align: left">
	    <table style="width: 95%; margin: auto">
	    	<tr>
	        	<td class="popup_title" style="width: 50%">Anno corso</td>
	            <td style="width: 50%; padding-top: 3px; padding-bottom: 3px">
	                <select name="anno_corso" id="anno_corso" style="width: 90%; font-size: 11px" <?php if(isset($cls)){ ?>onchange="upd_field(this)"<?php } ?>>
	                    <option value="-1">.</option>
	                    <option value="1" <?php if(isset($cls) && $cls->get_anno() == 1) echo "selected" ?>>1</option>
	                    <option value="2" <?php if(isset($cls) && $cls->get_anno() == 2) echo "selected" ?>>2</option>
	                    <option value="3" <?php if(isset($cls) && $cls->get_anno() == 3) echo "selected" ?>>3</option>
	                    <?php if($admin_level != MIDDLE_SCHOOL){ ?>
	                    <option value="4" <?php if(isset($cls) && $cls->get_anno() == 4) echo "selected" ?>>4</option>
	                    <option value="5" <?php if(isset($cls) && $cls->get_anno() == 5) echo "selected" ?>>5</option>
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
	                    <option value="<?php echo $sez ?>" <?php if(isset($cls) && $cls->get_sezione() == $sez) echo "selected" ?>><?php echo $sez ?></option>
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
	                    <option value="<?php echo $sede['id_sede'] ?>" <?php if(isset($cls) && $cls->get_sede() == $sede['id_sede']) echo "selected" ?>><?php echo $sede['nome'] ?></option>
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
	                    <option value="<?php echo $ordine['id_tipo'] ?>" <?php if(isset($cls) && $cls->getSchoolOrder() == $ordine['id_tipo']) echo "selected" ?>><?php echo $ordine['tipo'] ?></option>
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
	                    <option value="<?php echo $module['id_modulo'] ?>" <?php if(isset($cls) && $cls->get_modulo_orario()->getID() == $module['id_modulo']) echo "selected" ?>>Mod. <?php echo $module['id_modulo'] ?> (<?php echo $module['giorni'] ?> giorni - <?php echo $module['ore_settimanali'] ?> ore)</option>
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
	                <input type="checkbox" id="tempo_prolungato" name="tempo_prolungato" value="1" <?php if(isset($cls) && $cls->isFullTime()) echo "checked" ?>  <?php if(isset($cls)){ ?>onchange="upd_field(this)"<?php } ?>/>
	            </td>
	        </tr>
	        <tr>
	        	<td class="popup_title" style="width: 50%">Corso musicale</td>
	            <td style="width: 50%; padding-top: 3px; padding-bottom: 3px">
	                <input type="checkbox" id="musicale" name="musicale" value="1" <?php if(isset($cls) && $cls->isMusicale()) echo "checked" ?>  <?php if(isset($cls)){ ?>onchange="upd_field(this)"<?php } ?>/>
	            </td>
	        </tr>
	        <tr>
	            <td colspan="2" style="height: 15px">&nbsp;&nbsp;&nbsp;
	            	<input type="hidden" id="cls" name="cls" value="<?php echo $_REQUEST['id'] ?>" />
	            	<input type="hidden" id="action" name="action" value="" />
	            </td>
	        </tr>
	    </table>
	    </div>
		</form>
		<div style="margin: 10px 10px 0 0; width: 88%; text-align: right">
			<button id="go_link">Registra</button>
		</div>
	</div>
	<p class="spacer"></p>
</div>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="../../index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="../index.php"><img src="../../images/31.png" style="margin-right: 10px; position: relative; top: 5%" />Admin</a></div>
		<div class="drawer_link"><a href="http://www.istitutoiglesiasserraperdosa.it"><img src="../../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<?php include "../footer.php" ?>
</body>
</html>
