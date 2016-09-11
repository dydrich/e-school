<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Classi</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link href="../../css/general.css" rel="stylesheet" />
	<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var cls = 0;
		var coord = function(){
			//cls = classe;
			$('#hid').hide();
			var url = "get_cdc.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {cls: cls},
				dataType: 'json',
				error: function() {
					j_alert("error", "Errore di trasmissione dei dati");
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
						j_alert("error", json.message);
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
						j_alert("error", json.message);
						console.log(json.dbg_message);
					}
					else if (json.status == "no_del"){
						j_alert("error", json.message);
						return false;
					}
					else {
						j_alert("alert", "Classe cancellata correttamente");
						$('#row_'+class_id).hide();
					}
				}
			});
		};

		var upd_cdc = function(sel){
			var doc = $('#'+sel).val();
			var url = "class_manager.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {action: 'upgrade', cls: cls, field: sel, value: doc, is_char: 0},
				dataType: 'json',
				error: function() {
					j_alert("error", "Errore di trasmissione dei dati");
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
						j_alert("error", json.message);
						console.log(json.dbg_message);
					}
					else {
						//$('#coord').hide();
					}
				}
			});
		};

		var show_menu = function(id) {
			if ($('#hid').is(":visible")) {
				$('#hid').slideUp(400);
				return false;
			}
			cls = id;
			var offset = $('#menu_'+id).offset();
			var top = offset.top + 18;
			var left = offset.left - $('#hid').width() + ($('#menu_'+id).width() / 2);
			$('#hid').css({top: top+"px", left: left+"px"});
			$('#classname').text($('#ren_'+id).text());
			$('#hid').slideDown();
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('a.coord_link').click(function(event){
				event.preventDefault();
				coord();
			});
			$('a.sched_link').click(function(event){
				event.preventDefault();
				document.location.href="orario.php?cls="+cls;
			});
			$('a.stud_link').click(function(event){
				event.preventDefault();
				document.location.href="alunni.php?id_classe="+cls;
			});
			$('a.cdc_link').click(function(event){
				event.preventDefault();
				document.location.href="cdc.php?id="+cls;
			});
			$('a.del_link').click(function(event){
				event.preventDefault();
				var strs = this.parentNode.id.split("_");
				del_class(strs[1]);
			});
			$('a.parent_link').click(function(event){
				event.preventDefault();
				document.location.href="rappresentanti_di_classe.php?id="+cls;
			});
			$('#close_btn').click(function(event){
				event.preventDefault();
				$('#coord').hide();

			});
			$('a.showmenu').click(function(event){
				event.preventDefault();
				var strs = this.parentNode.id.split("_");
				show_menu(strs[1]);
			});
			$('#coordinatore').change(function(event){
				upd_cdc('coordinatore');
			});
			$('#segretario').change(function(event){
				upd_cdc('segretario');
			});
			$('#top_btn').click(function() {
				$('html,body').animate({
					scrollTop: 0
				}, 700);
				return false;
			});

			var amountScrolled = 200;

			$(window).scroll(function() {
				if ($(window).scrollTop() > amountScrolled) {
					$('#plus_btn').fadeOut('slow');
					$('#float_btn').fadeIn('slow');
					$('#top_btn').fadeIn('slow');
				} else {
					$('#float_btn').fadeOut('slow');
					$('#plus_btn').fadeIn();
					$('#top_btn').fadeOut('slow');
				}
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
			<a href="classe.php?id=0&school_order=<?php echo $_GET['school_order'] ?>">
				<img src="../../images/39.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<div class="card_container" style="margin-top: 20px">
            <?php
            $res_cls->data_seek(0);
            $x = 1;

            while($class = $res_cls->fetch_assoc()){
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
            <div class="card" id="row_<?php echo $class['id_classe'] ?>">
	            <div class="card_title">
		            <a href="classe.php?id=<?php echo $class['id_classe'] ?>&school_order=<?php echo $school_order ?>" class="ren_link">
			            <span id="ren_<?php echo $class['id_classe'] ?>"><?php print $class['anno_corso']." ".$class['sezione'] ?></span> - <span id="" style="margin-left: 0px; margin-right: 30px"><?php echo $class['nome'] ?></span>
		            </a>
		            <div style="float: right; margin-right: 20px" id="del_<?php echo $class['id_classe'] ?>">
			            <a href="../../shared/no_js.php" class="del_link">
				            <img src="../../images/51.png" style="position: relative; bottom: 2px" />
			            </a>
		            </div>
		            <div style="float: right; margin-right: 220px; text-align: center; width: 100px" class="normal">Alunni: <?php print $stud_count ?></div>
	            </div>
	            <div class="card_content">
		            <div style="width: 90%; float: left"><?php if($cdc != "") print("$cdc"); else print "Non presente" ?></div>
		            <div id="menu_<?php echo $class['id_classe'] ?>" style="width: 7.5%; float: left; text-align: right">
			            <a href="../../shared/no_js.php" class="showmenu"><img src="../../images/menu.png" /></a>
		            </div>
	            </div>
            </div>
            <?php
                $x++;
            }
            ?>
		</div>
    </div>
	<p class="spacer"></p>
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
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="../../index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="../index.php"><img src="../../images/31.png" style="margin-right: 10px; position: relative; top: 5%" />Admin</a></div>
		<div class="drawer_link"><a href="http://www.istitutoiglesiasserraperdosa.it"><img src="../../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<div id="hid" style="position: absolute; width: 200px; height: 160px; display: none; ">
	<p id="classname" style="width: 100%; margin: auto; text-align: center" class="pop_label"></p>
	<p style="line-height: 12px; margin-bottom: 5px"><a href="../../shared/no_js.php" class="cdc_link">Consiglio di classe</a></p>
	<p style="line-height: 12px; margin-bottom: 5px"><a href="../../shared/no_js.php" class="coord_link">Coordinatore</a></p>
	<p style="line-height: 12px; margin-bottom: 5px"><a href="../../shared/no_js.php" class="sched_link">Orario</a></p>
	<p style="line-height: 12px; margin-bottom: 5px"><a href="../../shared/no_js.php" class="stud_link">Alunni</a></p>
	<p style="line-height: 12px; margin-bottom: 5px"><a href="../../shared/no_js.php" class="parent_link">Rappresentanti di classe</a></p>

</div>
<a href="classe.php?id=0&school_order=<?php echo $_GET['school_order'] ?>" id="float_btn" class="rb_button float_button">
	<i class="fa fa-pencil"></i>
</a>
<a href="#" id="top_btn" class="rb_button float_button top_button">
	<i class="fa fa-arrow-up"></i>
</a>
</body>
</html>
