<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Gestione record scrutini</title>
	<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../js/page.js"></script>
	<script type="text/javascript">
		var IE = document.all?true:false;
		var tempX = 0;
		var tempY = 0;

		var check_count = <?php echo $count_data ?>;
		var inserted_data = <?php echo $inserted_data ?>;

		var school_order = <?php echo $school_order ?>;
		var scr_records = [];
		<?php
		$res_scr->data_seek(0);
		$_clss = array();
		while ($_scr = $res_scr->fetch_assoc()) {
			if (!isset($_clss[$_scr['classe']])){
				$_clss[$_scr['classe']] = array();
			}
			$_clss[$_scr['classe']][] = $_scr['materia'];
		}
		foreach ($cls as $k => $a) {
		?>
		scr_records[<?php echo $k ?>] = '<?php echo join(",", $a['scr']) ?>';
		<?php
		}
		?>

		var class_data = [];
		<?php
		foreach ($class_data as $k => $a) {
		?>
		class_data[<?php echo $k ?>] = '<?php echo $a ?>';
		<?php
		}
		?>

		var mat = [];
		<?php
		foreach ($materie as $k => $a) {
		?>
		mat[<?php echo $k ?>] = '<?php echo truncateString($a, 25) ?>';
		<?php
		}
		?>

		var assignment_marks = function(action){
			and_class = "";
			cls = 0;

			if (action != "reinsert") {
				and_class = "per la classe";
				cls = selected_class.id;
			}

			if (action == 'reinsert') {
				if (inserted_data > 0) {
					if (!confirm("Attenzione! Sono presenti dei voti. Vuoi davvero cancellarli e reinserire tutto?")) {
						return false;
					}
				}
				else if (!confirm("Reinserire i dati?")) {
					return false;
				}
			}
			else if (action == 'cl_reinsert') {
				if (class_data[cls] > 0) {
					if (!confirm("Attenzione! Sono presenti dei voti " + and_class + ". Vuoi davvero cancellarli e reinserire tutto?")) {
						return false;
					}
				}
				else if (!confirm("Reinserire i dati " + and_class + "?")) {
					return false;
				}
			}

			var url = "eoyevaluation_manager.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {quadrimestre: <?php echo $_REQUEST['quadrimestre'] ?>, action: action, cls: cls, school_order: school_order},
				dataType: 'json',
				error: function() {
					console.log(json.dbg_message);
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
						console.log(json.dbg_message);
						console.log(json.query);
						j_alert("error", json.message);
					}
					else {
						j_alert("alert", "Operazione conclusa");
						setTimeout(function() {
							document.location.href = document.location.href;
						}, 1500);
					}
				}
			});
		};

		var del_subject = function(subject){
			action = "del_subject";
			cls = 0;
			if (need_class) {
				action = "cl_del_subject";
				cls = selected_class.id;
			}

			var url = "eoyevaluation_manager.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {quadrimestre: <?php echo $_REQUEST['quadrimestre'] ?>, action: action, subject: subject, cls: cls, school_order: school_order},
				dataType: 'json',
				error: function() {
					console.log(json.dbg_message);
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
						console.log(json.dbg_message);
						console.log(json.query);
						j_alert("error", json.message);
					}
					else {
						j_alert("alert", "Operazione conclusa");
						setTimeout(function() {
							document.location.href = document.location.href;
						}, 1500);
					}
				}
			});
		};

		var add_subject = function(subject){
			action = "ins_subject";
			cls = 0;
			if (need_class) {
				action = "cl_ins_subject";
				cls = selected_class.id;
			}

			var url = "eoyevaluation_manager.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {quadrimestre: <?php echo $_REQUEST['quadrimestre'] ?>, action: action, subject: subject, cls: cls, school_order: school_order},
				dataType: 'json',
				error: function() {
					console.log(json.dbg_message);
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
						console.log(json.dbg_message);
						console.log(json.query);
						j_alert("error", json.message);
					}
					else {
						j_alert("alert", "Operazione conclusa");
						setTimeout(function() {
							document.location.href = document.location.href;
						}, 1500);
					}
				}
			});
		};

		var show_div = function(e, off){
			if ($('#menu_div').is(":visible")) {
				$('#menu_div').slideUp(500);
				$('#tr'+selected_class.id).removeClass("accent_decoration");
				return;
			}
			off.left -= $('#menu_div').width() - 30;
			$('#menu_div').css({top: off.top+"px"});
			$('#menu_div').css({left: off.left+"px"});
			$('#menu_div').slideDown(500);
			$('#tr'+selected_class.id).addClass("accent_decoration");
		};

		var need_class = false;
		var selected_class = {};
		selected_class.id = 0;
		selected_class.desc = "";

		var populate_div = function(event, param){
			var url = "get_add_subjects.php";
			$.ajax({
				type: "POST",
				url: url,
				data:  {cls: selected_class.id, source: "scr", quadrimestre: <?php echo $_REQUEST['quadrimestre'] ?>, act: param },
				dataType: 'json',
				error: function() {
					console.log(json.dbg_message);
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
						console.log(json.dbg_message);
						console.log(json.query);
						j_alert("error", json.message);
					}
					else if (json.status == "ko") {
						j_alert("error", json.message);
						console.log(dati[2]);
						return;
					}
					else{
						links = json.data;
						if (param == 'add') {
							$('#cl_add_div').html("");
							_p = document.createElement("p");
							_p.appendChild(document.createTextNode("Aggiungi una materia"));
							$(_p).addClass("pop_label");

							$(_p).appendTo($('#cl_add_div'));
							for (i in links) {
								dt = links[i];
								$("<a href='../shared/no_js.php' style='padding-left: 5px' id='add_" + dt.id_materia + "' class='add_link'>" + dt.materia + "</a><br />").appendTo($('#cl_add_div'));
							}
							$('#cl_add_div').mouseleave(function (event) {
								event.preventDefault();
								$('#cl_add_div').hide();
							});
							$('.add_link').click(function (event) {
								event.preventDefault();
								var strs = this.id.split("_");
								add_subject(strs[1]);
							});
						}
						else {
							/*
							 cl_del_div
							 */
							$('#cl_del_div').html("");
							_p = document.createElement("p");
							_p.appendChild(document.createTextNode("Elimina una materia"));
							$(_p).addClass("pop_label");
							$(_p).appendTo($('#cl_del_div'));

							for (i in links) {
								ar = links[i];
								$("<a href='../shared/no_js.php' style='padding-left: 5px' id='del_" + ar.id_materia + "' class='del_link'>"+ar.materia+"</a><br />").appendTo($('#cl_del_div'));
							}
							$('#cl_del_div').mouseleave(function(event){
								event.preventDefault();
								$('#cl_del_div').hide();
							});
							$('.del_link').click(function(event){
								event.preventDefault();
								var strs = this.id.split("_");
								del_subject(strs[1]);
							});
						}
					}
				}
			});
		};

		var load_students = function(event, param){
			var url = "get_students.php";
			$.ajax({
				type: "POST",
				url: url,
				data:  {cls: selected_class.id, source: "scr", quadrimestre: <?php echo $_REQUEST['quadrimestre'] ?>, action: param },
				dataType: 'json',
				error: function() {
					console.log(json.dbg_message);
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
						console.log(json.dbg_message);
						console.log(json.query);
						j_alert("error", json.message);
					}
					else if (json.status == "ko") {
						j_alert("error", json.message);
						return;
					}
					else{
						links = json.data;
						if (param == 'add') {
							$('#st_add_div').html("");
							_p = document.createElement("p");
							_p.appendChild(document.createTextNode("Aggiungi uno studente"));
							$(_p).addClass("pop_label");

							$(_p).appendTo($('#st_add_div'));
							for (i in links) {
								dt = links[i];
								$("<a href='../shared/no_js.php' style='padding-left: 5px' id='stadd_" + dt.id + "' data-id='" + dt.id + "' class='stadd_link'>" + dt.name + "</a><br />").appendTo($('#st_add_div'));
							}
							$('.stadd_link').mouseover(function(event) {
								$(this).css({fontSize: '16px', fontWeight: 'bold'});
							});
							$('.stadd_link').mouseleave(function(event) {
								$(this).css({fontSize: '13px', fontWeight: 'normal'});
							});
							$('#st_add_div').mouseleave(function (event) {
								event.preventDefault();
								$('#st_add_div').hide();
							});
							$('.stadd_link').click(function (event) {
								event.preventDefault();
								var _id = $(this).attr("data-id");
								change_student('add', _id);
							});
						}
						else if (param == 'sub') {
							/*
							 st_del_div
							 */
							$('#st_del_div').html("");
							_p = document.createElement("p");
							_p.appendChild(document.createTextNode("Elimina uno studente"));
							$(_p).addClass("pop_label");
							$(_p).appendTo($('#st_del_div'));

							for (i in links) {
								ar = links[i];
								$("<a href='../shared/no_js.php' style='padding-left: 5px' id='stdel_" + ar.id + "' data-id='" + ar.id + "' class='stdel_link'>"+ar.name+"</a><br />").appendTo($('#st_del_div'));
							}
							$('.stdel_link').mouseover(function(event) {
								$(this).css({fontSize: '16px', fontWeight: 'bold'});
							});
							$('.stdel_link').mouseleave(function(event) {
								$(this).css({fontSize: '13px', fontWeight: 'normal'});
							});
							$('#st_del_div').mouseleave(function(event){
								event.preventDefault();
								$('#st_del_div').hide();
							});
							$('.stdel_link').click(function(event){
								event.preventDefault();
								var _id = $(this).attr("data-id");
								change_student('del', _id);
							});
						}
						else if (param == 'reinsert') {
							/*
							 st_del_div
							 */
							$('#st_rei_div').html("");
							_p = document.createElement("p");
							_p.appendChild(document.createTextNode("Reinserisci uno studente"));
							$(_p).addClass("pop_label");
							$(_p).appendTo($('#st_rei_div'));

							for (i in links) {
								sar = links[i];
								$("<a href='../shared/no_js.php' style='padding-left: 5px' id='strei_" + sar.id + "' data-id='" + sar.id + "' class='strei_link'>"+sar.name+"</a><br />").appendTo($('#st_rei_div'));
							}
							$('.strei_link').mouseover(function(event) {
								$(this).css({fontSize: '16px', fontWeight: 'bold'});
							});
							$('.strei_link').mouseleave(function(event) {
								$(this).css({fontSize: '13px', fontWeight: 'normal'});
							});
							$('#st_rei_div').mouseleave(function(event){
								event.preventDefault();
								$('#st_rei_div').hide();
							});
							$('.strei_link').click(function(event){
								event.preventDefault();
								var _id = $(this).attr("data-id");
								change_student('reinsert', _id);
							});
						}
					}
				}
			});
		};

		var change_student = function (act, student) {
			cls = selected_class.id;

			action = act + "_student";
			var url = "eoyevaluation_manager.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {quadrimestre: <?php echo $_REQUEST['quadrimestre'] ?>, action: action, cls: cls, student: student, school_order: school_order},
				dataType: 'json',
				error: function() {
					console.log(json.dbg_message);
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
						console.log(json.dbg_message);
						console.log(json.query);
						j_alert("error", json.message);
					}
					else {
						j_alert("alert", "Operazione conclusa");
						setTimeout(function() {
							document.location.href = document.location.href;
						}, 1500);
					}
				}
			});
		};

		var fix_data = function() {
			var url = "eoyevaluation_manager.php";
			background_process("Operazione in corso", 200, true);
			$.ajax({
				type: "POST",
				url: url,
				data: {quadrimestre: <?php echo $_REQUEST['quadrimestre'] ?>, action: 'fix', school_order: school_order},
				dataType: 'json',
				error: function() {
					console.log(json.dbg_message);
					clearTimeout(bckg_timer);
					$('#background_msg').text("Errore di trasmissione dei dati");
					setTimeout(function() {
						$('#background_msg').dialog("close");
					}, 2000);
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
						console.log(json.dbg_message);
						console.log(json.query);
						clearTimeout(bckg_timer);
						$('#background_msg').text(json.message);
						setTimeout(function() {
							$('#background_msg').dialog("close");
						}, 2000);
					}
					else {
						clearTimeout(bckg_timer);
						loaded("Operazione conclusa");
						setTimeout(function() {
							document.location.href = document.location.href;
						}, 2000);
					}
				}
			});
		};

		var show_menu = function(e, div) {
			if (div == "add_div") {
				$('#del_div').hide();
			}
			else {
				$('#add_div').hide();
			}
			var offset = $('#drawer').offset();
			var top = offset.top;
			var left = offset.left + $('#drawer').width();
			$('#'+div).css({top: top+"px", left: left+"px", zIndex: 1000});
			$('#'+div).show('slide', 300);
		};

		var show_add = function(e, off) {
			if ($('#add_div').is(":visible")) {
				$('#add_div').hide('slide', 300);
				return;
			}
			$('#del_div').hide()
			var offset = $('#drawer').offset();
			var top = off.top;

			var left = offset.left + $('#drawer').width() + 1;
			$('#add_div').css({top: top+"px", left: left+"px", zIndex: 1000});
			$('#add_div').show('slide', 300);
			return true;
		};

		var show_sub = function(e, off) {
			if ($('#del_div').is(":visible")) {
				$('#del_div').hide('slide', 300);
				return;
			}
			$('#add_div').hide();
			var offset = $('#drawer').offset();
			var top = off.top;

			var left = offset.left + $('#drawer').width() + 1;
			$('#del_div').css({top: top+"px", left: left+"px", zIndex: 1000});
			$('#del_div').show('slide', 300);
			return true;
		};

		var show_list = function(e, off, div){

			if ($(div).is(":visible")) {
				$(div).slideUp(500);
				return;
			}
			off.left -= $(div).width() - 30;
			$(div).css({top: off.top+"px"});
			$(div).css({left: off.left+"px"});
			$(div).slideDown(500);
			$('#tr'+selected_class.id).addClass("accent_decoration");
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#reins').click(function(event){
				event.preventDefault();
				if (check_count > 0) {
					assignment_marks('reinsert');
				}
				else {
					assignment_marks('insert');
				}
			});
			$('#fix').on('click', function(event) {
				event.preventDefault();
				fix_data();
			});
			$('#del_sub').click(function(event){
				event.preventDefault();
				need_class = false;
				var off = $(this).parent().offset();
				show_sub(event, off);
			});
			$('#add_sub').click(function(event){
				event.preventDefault();
				need_class = false;
				var off = $(this).parent().offset();
				show_add(event, off);
			});
			$('#del_div').mouseleave(function(event){
				event.preventDefault();
		        $('#del_div').hide();
		    });
			$('#add_div').mouseleave(function(event){
				event.preventDefault();
		        $('#add_div').hide();
		    });
			$('#menu_div').mouseleave(function(event){
				event.preventDefault();
		        $('#menu_div').hide();
				//$('#cl_add_div').hide();
				//$('#cl_del_div').hide();
				$('#tr'+selected_class.id).removeClass("accent_decoration");
		    });
			$('.del_link').click(function(event){
				event.preventDefault();
				var strs = this.id.split("_");
				del_subject(strs[1]);
			});
			$('.add_link').click(function(event){
				event.preventDefault();
				var strs = this.id.split("_");
				add_subject(strs[1]);
			});
			$('.img_click').mouseover(function(event){
				event.preventDefault();
				p = this.parentNode.parentNode;
				$(p).css({backgroundColor: "rgba(231, 231, 231, 0.8)", border: "1px solid #AAAAAA", borderRadius: "5px"});
			});
			$('.img_click').mouseout(function(event){
				event.preventDefault();
				p = this.parentNode.parentNode;
				$(p).css({backgroundColor: "", border: "0", borderRadius: "5px"});
			});
			$('.img_link').click(function(event){
				event.preventDefault();
				selected_class.id = $(this).attr("data-id");
				selected_class.desc = $(this).attr("data-desc");
				$('#menu_label').text("Classe "+selected_class.desc);
				var off = $(this).parent().parent().offset();
				off.top += $(this).parent().parent().height();
				show_div(event, off);
			});
			$('#cl_rei').click(function(event){
				event.preventDefault();
				need_class = true;
				assignment_marks("cl_reinsert");
		    });
			$('#cl_add').click(function(event){
				event.preventDefault();
				need_class = true;
				populate_div(event, 'add');
				var off = $(this).parent().offset();
				off.left -= 30;
				show_list(event, off, '#cl_add_div');
		    });
			$('#cl_sub').click(function(event){
				event.preventDefault();
				need_class = true;
				populate_div(event, 'sub');
				var off = $(this).parent().offset();
				off.left -= 30;
				show_list(event, off, '#cl_del_div');
		    });
			$('#st_add').click(function(event){
				event.preventDefault();
				need_class = true;
				load_students(event, 'add');
				var off = $(this).parent().offset();
				off.left -= 30;
				show_list(event, off, '#st_add_div');
			});
			$('#st_sub').click(function(event){
				event.preventDefault();
				need_class = true;
				load_students(event, 'sub');
				var off = $(this).parent().offset();
				off.left -= 30;
				show_list(event, off, '#st_del_div');
			});
			$('#st_rei').click(function(event){
				event.preventDefault();
				need_class = true;
				load_students(event, 'reinsert');
				var off = $(this).parent().offset();
				off.left -= 30;
				show_list(event, off, '#st_rei_div');
			});
			$('#overlay').on("click", function(event){
				if ($('#overlay').is(':visible')) {
					show_drawer(event);
				}
				$('#add_div').hide();
				$('#del_div').hide();
			});
		});
	</script>
	<style>
	.del_link, .add_link{
		padding-left: 10px
	}

	</style>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "scr_menu.php" ?>
	</div>
	<div id="left_col">
		<div style="position: absolute; top: 75px; margin-left: 625px; margin-bottom: -5px" class="rb_button">
			<a href="../shared/no_js.php" id="reins">
				<?php if($count_data > 0){ ?>
				<img src="../images/45.png" style="padding: 12px 0 0 12px" />
				<?php  } else { ?>
				<i class="fa fa-plus" style="color: black; font-size: 1.6em; padding: 10px 0 0 12px"></i>
				<?php } ?>
			</a>
		</div>
		<div style="position: absolute; top: 75px; margin-left: 575px; margin-bottom: -5px" class="rb_button">
			<a href="../shared/no_js.php" id="fix" title="Ripara o completa l'archivio">
				<i class="fa fa-magic" style="color: black; font-size: 1.6em; padding: 10px 0 0 12px"></i>
			</a>
		</div>
		<table style="width: 90%; margin: 0 auto 0 auto; border-collapse: collapse">
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <?php
            foreach ($cls as $k => $cl) {
            	$mt = [];
            	$scr_str = "Nessun record presente";
            	if (count($cl['scr']) > 0) {
	            	foreach ($cl['scr'] as $c) {
	            		$mt[] = $materie[$c];
	            	}
	            	$scr_str = join(", ", $mt);
            	}
            	
            ?>
            <tr class="admin_row" id="tr<?php echo $k ?>">
	            <td style="width:  5%; font-weight: bold"><?php if (isset($cl)) echo $cl['anno_corso'],$cl['sezione'] ?></td>
	            <td style="width: 90%"><?php echo $scr_str ?></td>
	            <td style="width:  5%">
	            	<p style="width: 25px; height: 25px; line-height: 41px; text-align: center; margin: 6px 0 0 0">
	            		<a href="../shared/no_js.php" class="img_link" id="imglink_<?php echo $k ?>" data-id="<?php echo $k ?>" data-desc="<?php echo $cl['anno_corso'],$cl['sezione'] ?>">
	            			<img src="../images/click.png" style="margin: 0 0 4px 0; opacity: 0.5" class="img_click" />
	            		</a>
	            	</p>
	            </td>
	        </tr>
	        <?php
            }
	        ?>
	        <tr class="admin_void">
                <td colspan="3"></td>
            </tr>
        </table>
    </div>
    <div id="del_div" style="width: 240px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #AAAAAA; display: none; background-color: #FFFFFF">
    	<p class="pop_label">Elimina una materia</p>
    <?php
    reset($materie);
    foreach ($materie_scr as $idm => $mat) {
    ?>
    	<a href="../shared/no_js.php" class="del_link" id="del_<?php echo $idm ?>"><?php echo truncateString($mat, 25) ?></a><br />
    <?php
    }
    ?>
    </div>
    <div id="add_div" style="width: 240px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #AAAAAA; display: none; background-color: #FFFFFF">
    	<p class="pop_label">Aggiungi una materia</p>
    <?php
    foreach ($materie_no_scr as $mnc) {
    ?>
    	<a href="../shared/no_js.php" class="add_link" id="add_<?php echo $mnc ?>"><?php echo $materie[$mnc] ?></a><br />
    <?php
    }
    ?>
    </div>
    <div id="cl_add_div" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #AAAAAA; border-radius: 2px 2px 2px 2px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888"></div>
    <div id="cl_del_div" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #AAAAAA; border-radius: 2px 2px 2px 2px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888"></div>
	<div id="st_add_div" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #AAAAAA; border-radius: 2px 2px 2px 2px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888"></div>
	<div id="st_del_div" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #AAAAAA; border-radius: 2px 2px 2px 2px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888"></div>
	<div id="st_rei_div" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #AAAAAA; border-radius: 2px 2px 2px 2px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888"></div>
    <div id="menu_div" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #AAAAAA; border-radius: 2px 2px 2px 2px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888">
    	<p id="menu_label" style="text-align: center; padding: 2px 0 2px 0; width: 100%; font-weight: bold; margin-bottom: 8px; "></p>
    	<a href="../shared/no_js.php" id="cl_rei" style="padding-left: 10px;">Reinserisci tutto</a><br />
    	<a href="../shared/no_js.php" id="cl_add" style="padding-left: 10px;">Aggiungi una materia</a><br />
    	<a href="../shared/no_js.php" id="cl_sub" style="padding-left: 10px;">Elimina una materia</a><br />
	    <a href="../shared/no_js.php" id="st_rei" style="padding-left: 10px;">Reinserisci un alunno</a><br />
	    <a href="../shared/no_js.php" id="st_add" style="padding-left: 10px;">Aggiungi un alunno</a><br />
	    <a href="../shared/no_js.php" id="st_sub" style="padding-left: 10px;">Elimina un alunno</a><br />
    	<!-- 
    	<a href="../shared/no_js.php" id="cl_inv" style="padding-left: 10px;">Alunni non validati</a><br />
    	<a href="../shared/no_js.php" id="cl_ver" style="padding-left: 10px;">Verifica i dati</a>
    	 -->
    </div>
	<p class="spacer"></p>
	</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="../shared/no_js.php" id="add_sub" style="margin-right: 10px"><img src="../images/36.png" style="margin-right: 10px; position: relative; top: 5%" />Aggiungi una materia per tutte le classi</a></div>
		<div class="drawer_link separator"><a href="../shared/no_js.php" id="del_sub"><img src="../images/37.png" style="margin-right: 10px; position: relative; top: 5%" />Elimina una materia per tutte le classi</a></div>
		<div class="drawer_link"><a href="../index.php"><img src="../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="index.php"><img src="../images/31.png" style="margin-right: 10px; position: relative; top: 5%" />Admin</a></div>
		<div class="drawer_link"><a href="http://www.istitutoiglesiasserraperdosa.it"><img src="../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../shared/do_logout.php"><img src="../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
