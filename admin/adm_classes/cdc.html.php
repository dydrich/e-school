<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Consiglio di classe</title>
<link href="../../css/site_themes/blue_red/reg.css" rel="stylesheet" />
<link href="../../css/general.css" rel="stylesheet" />
<link rel="stylesheet" href="../../modules/documents/theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
var sc_order = <?php echo $classe['ordine_di_scuola'] ?>;
var upd_cdc = function(materia, sel){
    var doc = $('#'+sel).val();
    //var text = sel.options[sel.selectedIndex].text;
    if(doc == 0){
        alert("Docente non selezionato");
        return;
    }
    var url = "update_cdc.php";
	$.ajax({
		type: "POST",
		url: url,
		data: {cls: <?php echo $classID ?>, mat: materia, doc: doc},
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

			}
		}
	});
};

var add_teacher = function(){
	$('#dialog').dialog({
		autoOpen: true,
		show: {
			effect: "appear",
			duration: 500
		},
		hide: {
			effect: "slide",
			duration: 300
		},
		buttons: [{
			text: "Chiudi",
			click: function() {
				$( this ).dialog( "close" );
			}
		}],
		modal: true,
		width: 450,
		title: 'Elenco docenti',
		open: function(event, ui){

		}
	});
};

var save_teacher = function(){
	var url = "update_cdc.php";

	var mat = 27;
	if (sc_order == 2){
		mat = 41;
	}
	$.ajax({
		type: "POST",
		url: url,
		data: {action: "add", cls: <?php echo $classID ?>, mat: mat, doc: $('#doc').val(), ore: $('#ore').val()},
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
				document.location.href = "cdc.php?id=<?php echo $classID ?>";
			}
		}
	});
};

var del_teacher = function(uid, cls){
	var mat = 27;
	if (sc_order == 2){
		mat = 41;
	}
	var url = "update_cdc.php";
	$.ajax({
		type: "POST",
		url: url,
		data: {action: "add", cls: <?php echo $classID ?>, mat: mat, doc: $('#doc').val()},
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
				document.location.href = "cdc.php?id=<?php echo $classID ?>";
			}
		}
	});
	$('#row_'+uid).hide()
	$('#row_'+uid).attr("id", "");
};

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
		<div class="group_head">Consiglio di classe: <?php print $classe['anno_corso'].$classe['sezione'] ?> - <?php echo $classe['nome'] ?></div>
	    <form action="cdc.php?upd=1" method="post" class="popup_form" style="width: 90%">
	    <div style="text-align: left">
	    <table style="width: 95%; margin: auto; border-spacing: 0" >
	    <?php
	    $res_mat->data_seek(0);
	    while($mat = $res_mat->fetch_assoc()){
	        if($mat['idpadre'] != "")
	            $mt = $mat['idpadre'];
	        else
	            $mt = $mat['id_materia'];
	        $sel_doc = "SELECT uid, nome, cognome FROM rb_utenti, rb_docenti WHERE uid = id_docente AND materia = $mt ORDER BY cognome, nome";
	        //print $sel_doc;
	        try{
	        	$res_doc = $db->executeQuery($sel_doc);
	        } catch (MySQLException $ex){
	        	$ex->alert();
	        }
	    ?>
	        <tr>
	            <td class="popup_title" style="width: 50%; padding-top: 1px; padding-bottom: 1px; font-weight: bold"><?php print $mat['materia'] ?></td>
	            <td style="width: 50%; padding-top: 3px; padding-bottom: 3px">
	                <select onchange="upd_cdc(<?php print $mat['id_materia'] ?>, this.id)" name="sel<?php print $mat['id_materia'] ?>" id="sel<?php print $mat['id_materia'] ?>" style="width: 90%; font-size: 11px">
	   	<?php if($res_doc->num_rows > 1){ ?>
	                    <option value="0">Nessuno</option>
	    <?php } ?>
	    <?php
	        while($dc = $res_doc->fetch_assoc()){
	            if($dc['uid'] == $consiglio[$mat['id_materia']]){
	    ?>
	                    <option value="<?php print $dc['uid'] ?>" selected><?php print $dc['cognome']." ".$dc['nome'] ?></option>
	    <?php
	            }
	            else{
	    ?>
	                    <option value="<?php print $dc['uid'] ?>"><?php print $dc['cognome']." ".$dc['nome'] ?></option>
	    <?php
	            }
	        }
	    ?>
	                </select>
		<?php if($res_doc->num_rows == 1){ ?>
					<script type="text/javascript">
					upd_cdc(<?php print $mat['id_materia'] ?>, 'sel<?php print $mat['id_materia'] ?>');
					</script>
		<?php } ?>
	            </td>
	        </tr>
	        <?php 
	    	} 
	    	
	        ?>
	        <tr>
	        	<td class="popup_title" style="width: 50%; padding-top: 1px; padding-bottom: 1px; font-weight: bold; border-left: 1px solid #BBB; border-top: 1px solid #BBB; border-bottom: 1px solid #BBB; border-top-left-radius: 10px; border-bottom-left-radius: 10px; ">Sostegno</td>
	        	<td style="width: 50%; padding-top: 3px; padding-bottom: 3px; border-right: 1px solid #BBBBBB; border-top: 1px solid #BBBBBB; border-bottom: 1px solid #BBBBBB; border-bottom-right-radius: 10px; border-top-right-radius: 10px;">
<?php 
if ($res_sost->num_rows < 1){
?>	        	
					<span>Nessuno</span>
<?php 
}
else if ($res_sost->num_rows > 0){
	while ($row = $res_sost->fetch_assoc()){
?>
					<div id="row_<?php echo $row['uid'] ?>">
					<span><?php echo $row['cognome']." ".$row['nome'] ?> (<?php echo $row['ore'] ?> ore)</span>
					<span style="float: right; margin-right: 40px"><a href="#" onclick="del_teacher(<?php echo $row['uid'] ?>)" style="color: red; font-weight: bold">x</a></span>
					</div>
<?php
	}
}
?>
					<span style="float: right; margin-right: 30px"><a href="#" onclick="add_teacher()">Aggiungi</a></span><br />
	        	</td>
	        </tr>
	        <tr>
	            <td colspan="2" style="height: 15px">&nbsp;&nbsp;&nbsp;</td>
	        </tr>
	    </table>

	    </div>
		</form>
		<div style="margin: 10px 10px 0 0 ; width: 95%; text-align: right">
			<a href="classi.php?school_order=<?php echo $classe['ordine_di_scuola'] ?>" id="close_btn" class="standard_link">Torna all'elenco classi</a>
		</div>
	</div>
	<p class="spacer"></p>
</div>
<div id="dialog" style="display: none; width: 400px">
	<form action="update_cdc.php" method="post" id="doc_form">
		<div style="margin: 10px auto 0 auto; width: 95%">
			<fieldset style="width: 100%; border: 1px solid #BBBBBB; padding: 10px 0 ; margin: 0 auto 0 auto; position: relative">
				<legend style="font-weight: bold; margin-left: 10px">Docenti di sostegno</legend>
				<table style="margin: 0 auto 0 auto; width: 90%">
					<tr class="popup_row header_row">
						<td class="popup_title" style="width: 30%;padding-left: 10px">Docente *</td>
						<td style="width: 70%; " colspan="3">
							<select class="form_input" name="doc" id="doc" autofocus style="width: 200px">
								<option value="0">.</option>
								<?php
								while ($row = $res_teac->fetch_assoc()){
								?>
								<option value="<?php echo $row['uid'] ?>"><?php echo $row['cognome']." ".$row['nome'] ?>
									<?php } ?>
							</select>
						</td>
					</tr>
					<tr class="popup_row header_row">
						<td class="popup_title" style="width: 30%;padding-left: 10px">Ore</td>
						<td style="width: 70%; " colspan="3">
							<select class="form_input" name="ore" id="ore" style="width: 200px">
								<?php
								for ($i = 0; $i < 23; $i++) {
									?>
									<option value="<?php echo $i ?>"><?php echo $i ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
				</table>
			</fieldset>
			<div style="width: 100%; text-align: right; margin-top: 30px">
				<a href="#" onclick="save_teacher(<?php echo $cls ?>)" class="standard_link nav_link_last">Registra</a>
				<input type="hidden" name="action" id="action" />
				<input type="hidden" name="_i" id="_i" />
				<input type="hidden" name="tipo" id="tipo" value="<?php print $_REQUEST['tipo'] ?>" />
				<input type="hidden" name="server_file" value="" id="server_file" />
				<input type="hidden" name="oldfile" value="<?php if(isset($doc)) print $doc['file'] ?>"/>
				<input type="hidden" name="progressivo_atto" id="progressivo_atto" value="<?php if(isset($doc)) print $doc['progressivo_atto'] ?>"/>
			</div>
		</div>
	</form>
</div>
<?php include "../footer.php" ?>
</body>
</html>
