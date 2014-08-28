<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Classi</title>
<link href="../../css/reg.css" rel="stylesheet" />
<link href="../../css/general.css" rel="stylesheet" />
<link rel="stylesheet" href="../../modules/documents/theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
var cls = 0;
var coord = function(classe){
	cls = classe;
	var url = "get_cdc.php";
	$.ajax({
		type: "POST",
		url: url,
		data: {cls: classe},
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
				$('#cls_desc').text(json.cls.classe);
				$('#coordinatore').empty();
				$('#coordinatore').append("<option value='0'>.</option>");
				for (var i = 0; i < json.data.coordinatore.length; i++){
					var t = json.data.coordinatore[i];
					var selected = '';
					if (t.uid == json.cls.coordinatore){
						selected = "selected";
					}
					$('#coordinatore').append("<option value='"+ t.uid+"'  "+selected+">"+ t.cognome+" "+ t.nome+"</option>");
				}
				$('#segretario').empty();
				$('#segretario').append("<option value='0'>.</option>");
				for (var i = 0; i < json.data.segretario.length; i++){
					var t = json.data.segretario[i];
					var selected = '';
					if (t.uid == json.cls.segretario){
						selected = "selected";
					}
					$('#segretario').append("<option value='"+ t.uid+"' "+selected+">"+ t.cognome+" "+ t.nome+"</option>");
				}
				$('#coord').dialog({
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
					title: 'Coordinatore e segretario',
					open: function(event, ui){

					}
				});
			}
		}
	});
};

var del_class = function(class_id){
	var url = "class_manager.php";
	$.ajax({
		type: "POST",
		url: url,
		data: {cls: class_id, action: "delete"},
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
			else if (json.status == "no_del"){
				alert(json.message);
				return false;
			}
			else {
				_alert("Classe cancellata correttamente");
				$('#row_'+class_id).hide();
			}
		}
	});
};

var upd_cdc = function(sel){
	var doc = $('#'+sel).val();
	if(doc == 0){
		alert("Docente non selezionato");
		return;
	}
	var url = "class_manager.php";
	$.ajax({
		type: "POST",
		url: url,
		data: {action: 'upgrade', cls: cls, field: sel, value: doc, is_char: 0},
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
				$('#coord').hide();
			}
		}
	});
};

<?php echo $page_menu->getJavascript() ?>

$(function(){
	$('table tbody > tr').mouseover(function(event){
		//alert(this.id);
		var strs = this.id.split("_");
		$('#link_'+strs[1]).show();
	});
	$('table tbody > tr').mouseout(function(event){
		//alert(this.id);
		var strs = this.id.split("_");
		$('#link_'+strs[1]).hide();
	});
	$('table tbody a.coord_link').click(function(event){
		event.preventDefault();
		var strs = this.parentNode.id.split("_");
		coord(strs[1], 0);
	});
	$('table tbody a.del_link').click(function(event){
		event.preventDefault();
		var strs = this.parentNode.id.split("_");
		del_class(strs[1]);
	});
	$('#close_btn').click(function(event){
		event.preventDefault();
		$('#coord').hide();

	});
	$('#coordinatore').change(function(event){
		upd_cdc('coordinatore');
	});
	$('#segretario').change(function(event){
		upd_cdc('segretario');
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
		<div class="group_head"><div style="float: left"><?php $page_menu->printLink() ?></div> Elenco classi <?php echo truncateString($ordini[$school_order]['tipo'], 57) ?> <?php if(isset($sede)) echo " [{$sede}]" ?>: pagina <?php print $page ?> di <?php print $pagine ?></div>
        <form method="post">
        <table class="admin_table">
        <thead>
            <tr>
            	<td style="padding-left: 10px; width: 23%" class="adm_titolo_elenco_first"></td>
                <td style="width: 70%; padding-left: 15px" class="adm_titolo_elenco">Consiglio di classe</td>
                <td style="padding-right: 5px; width: 7%" class="adm_titolo_elenco_last _center">Alunni</td>
            </tr>
            <tr class="admin_row_before_text">
                <td colspan="3"></td>
            </tr>
        </thead>
        <tbody>
            <?php
            $res_cls->data_seek(0);
            $x = 1;
            if($res_cls->num_rows > $limit)
                $max = $limit;
            else
                $max = $res_cls->num_rows;

            while($class = $res_cls->fetch_assoc()){
                if($x > $limit) break;
                $cdc = "";
                // estrazione consiglio di classe
                $sel_state = "SELECT count(*) AS count FROM rb_cdc WHERE id_anno = $anno AND id_classe = ".$class['id_classe']." AND id_docente IS NOT NULL AND id_materia <>11";
                //print $sel_state;
                $not_nulls = $db->executeCount($sel_state);
                
                $sel_cdc = "SELECT uid, nome, cognome FROM rb_utenti, rb_docenti, rb_cdc WHERE uid = rb_docenti.id_docente AND rb_docenti.id_docente = rb_cdc.id_docente AND rb_cdc.id_anno = $anno AND rb_cdc.id_classe = ".$class['id_classe'];
                $res_cdc = $db->executeQuery($sel_cdc);
                $num_docenti = $res_cdc->num_rows;
                $ids = array();
                if($num_docenti > 0){ 
                    while($doc = $res_cdc->fetch_assoc()){
						if(!in_array($doc['uid'], $ids)){
                        	$cdc .= $doc['cognome']." ".substr($doc['nome'], 0, 1)."., ";
                       	}
                       	$ids[] = $doc['uid'];
                    }
                }
                $sel_sos = "SELECT uid, nome, cognome FROM rb_utenti, rb_assegnazione_sostegno WHERE uid = rb_assegnazione_sostegno.docente AND rb_assegnazione_sostegno.anno = $anno AND rb_assegnazione_sostegno.classe = ".$class['id_classe'];
                $res_sos = $db->executeQuery($sel_sos);
                if($res_sos->num_rows > 0){
                	while($doc = $res_sos->fetch_assoc()){
                		if(!in_array($doc['uid'], $ids)){
                			$cdc .= $doc['cognome']." ".substr($doc['nome'], 0, 1)."., ";
                		}
                		$ids[] = $doc['uid'];
                	}
                }
                $cdc = substr($cdc, 0, (strlen($cdc) - 2));
                
                $sel_students_count = "SELECT COUNT(*) FROM rb_alunni WHERE id_classe = ".$class['id_classe'];
                $stud_count = $db->executeCount($sel_students_count);
            ?>
            <tr class="admin_row" id="row_<?php echo $class['id_classe'] ?>">
            	
            	<td><span style="font-weight: bold"><?php print $class['anno_corso']." ".$class['sezione'] ?></span><br /><span id="" style="margin-left: 0px; margin-right: 30px"><?php echo $class['nome'] ?></span></td>
            	<td style="padding-left: 10px; ">
                	<span id="span_<?php echo $class['id_classe'] ?>" class="ov_red" <?php if($cdc != "") print("style='font-weight: normal'") ?>><?php if($cdc != "") print("$cdc"); else print "Non presente" ?></span>
                	<div id="link_<?php echo $class['id_classe'] ?>" style="display: none; vertical-align: bottom">
                	<a href="classe.php?id=<?php echo $class['id_classe'] ?>&offset=<?php echo $offset ?>&school_order=<?php echo $school_order ?>" class="ren_link">Modifica</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="../../shared/no_js.php" class="del_link" st="<?php echo $stud_count ?>">Cancella</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="cdc.php?id=<?php echo $class['id_classe'] ?>&offset=<?php echo $offset ?>" class="cdc_link">Consiglio di classe</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="cdc.php?id=<?php echo $class['id_classe'] ?>&coord=1" class="coord_link">Coordinatore</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="orario.php?cls=<?php print $class['id_classe'] ?>&tp=<?php print $class['tempo_prolungato'] ?>&desc=<?php print $class['anno_corso']."".$class['sezione'] ?>&offset=<?php echo $offset ?>">Orario</a>
                	<span style="margin-left: 5px; margin-right: 5px">|</span>
                	<a href="alunni.php?id_classe=<?php print $class['id_classe'] ?>&offset=<?php echo $offset ?>">Alunni</a>
                	</div>
                </td>
                <td style="color: #003366; text-align: center"><span><?php print $stud_count ?></span></td>
                
            </tr>
            <?php
                $x++;
            }
            ?>
            </tbody>
            <tfoot>
            <?php
            include "../../shared/navigate.php";
            ?>
            <tr class="admin_menu">
                <td colspan="4">
                	<a href="classe.php?id=0&offset=<?php echo $offset ?>&school_order=<?php echo $_GET['school_order'] ?>" id="nc_link" class="nav_link standard_link">Nuova classe</a>
                </td>
            </tr>
            <tr class="admin_void">
                <td colspan="4">&nbsp;&nbsp;&nbsp;</td>
            </tr>
        </tfoot>
        </table>
        </form>
        </div>
        <?php include "../footer.php" ?>
	</div>
<div id="coord" style="display: none">
	<p style="text-align: center; font-size: 1.1em; font-weight: bold; margin-top: 10px">Coordinatore di classe: <span id="cls_desc"></span></p>
	<form action="cdc.php?upd=1" method="post">
		<div style="text-align: left">
			<table style="width: 420px; margin: auto">
				<tr>
					<td class="popup_title" style="width: 230px; padding-top: 1px; padding-bottom: 1px; font-weight: bold">Coordinatore</td>
					<td style="width: 190px; padding-top: 5px; padding-bottom: 5px">
						<select name="coordinatore" id="coordinatore" style="width: 180px; font-size: 11px">
							<option value="0">Nessuno</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="popup_title" style="width: 230px; padding-top: 1px; padding-bottom: 1px; font-weight: bold">Segretario</td>
					<td style="width: 190px; padding-top: 5px; padding-bottom: 5px">
						<select name="segretario" id="segretario" style="width: 180px; font-size: 11px">
							<option value="0">Nessuno</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="height: 15px">&nbsp;&nbsp;&nbsp;</td>
				</tr>
			</table>
		</div>
	</form>
</div>
<?php $page_menu->toHTML() ?>
</body>
</html>
