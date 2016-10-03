<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Gestione record CDC</title>
	<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../js/page.js"></script>
	<script type="text/javascript">
		var crea_cdc = function(action){
			and_class = "";
			cls = null;
			if (action != "reinsert") {
				and_class = "per la classe";
				cls = selected_class.id;
			}

			if (!confirm("Sei sicuro di voler reinserire i record nella tabella? Questa operazione cancellera` tutti i dati inseriti "+and_class+".")) {
				return false;
			}
			var url = "crea_cdc.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {action: action, cls: cls, school_order: <?php echo $_GET['school_order'] ?>},
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
						return;
					}
					else if (json.status == "ko") {
						j_alert("error", json.message);
						return;
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
			cls = null;
			if (need_class) {
				action = "cl_del_subject";
				cls = selected_class.id;
			}
			var url = "crea_cdc.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {action: action, subject: subject, cls: cls, school_order: <?php echo $_GET['school_order'] ?>},
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
						return;
					}
					else if (json.status == "ko") {
						j_alert("error", json.message);
						return;
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
			cls = null;
			if (need_class) {
				action = "cl_ins_subject";
				cls = selected_class.id;
			}
			var url = "crea_cdc.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {action: action, subject: subject, cls: cls, school_order: <?php echo $_GET['school_order'] ?>},
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
						return;
					}
					else if (json.status == "ko") {
						j_alert("error", json.message);
						return;
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

		var need_class = false;
		var selected_class = {};
		selected_class.id = 0;
		selected_class.desc = "";

		var populate_div = function(event, param){
			var url = "get_add_subjects.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {cls: selected_class.id, source: "cdc", school_order: <?php echo $_GET['school_order'] ?>, act: param},
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
						console.log(json.message);
						return;
					}
					else {
						links = json.data;
						if (param == "add") {
							$('#cl_add_div').html("");
							$("<p id='menu_label'>Aggiungi una materia</p>").appendTo($('#cl_add_div'));

							for (i in links) {
								dt = links[i];
								$("<a href='../shared/no_js.php' style='padding-left: 8px' id='add_" + dt.id_materia + "' class='add_link'>" + dt.materia + "</a><br />").appendTo($('#cl_add_div'));
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

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#reins').click(function(event){
				event.preventDefault();
				crea_cdc('reinsert');
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
				$('#cls_label').text("Classe "+selected_class.desc);
				var off = $(this).parent().parent().offset();
				off.top += $(this).parent().parent().height();
				show_div(event, off);
			});
			$('#cl_del').click(function(event){
				event.preventDefault();
				need_class = true;
		        crea_cdc("cl_delete");
		    });
			$('#cl_rei').click(function(event){
				event.preventDefault();
				need_class = true;
				crea_cdc("cl_reinsert");
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
				populate_div(event, 'del');
				var off = $(this).parent().offset();
				off.left -= 30;
				show_list(event, off, '#cl_del_div');
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
		<?php include "cdc_menu.php" ?>
	</div>
	<div id="left_col">
		<div style="position: absolute; top: 75px; margin-left: 605px; margin-bottom: -5px" class="rb_button">
			<a href="../shared/no_js.php" id="reins" class="do_link">
				<img src="../images/45.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<table style="width: 90%; margin: 0 auto 0 auto; border-collapse: collapse">
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <?php
            reset($cls);
            foreach ($cls as $k => $cl) {
            	$mt = array();
            	$cdc_str = "Nessun record presente";
            	if (count($cl['cdc']) > 0) {
	            	foreach ($cl['cdc'] as $c) {
	            		$mt[] = $materie[$c];
	            	}
	            	$cdc_str = join(", ", $mt);
            	}
            	
            ?>
            <tr class="admin_row" id="tr<?php echo $k ?>">
	            <td style="width:  5%; font-weight: bold"><?php echo $cl['anno_corso'],$cl['sezione'] ?></td>
	            <td style="width: 90%"><?php echo $cdc_str ?></td>
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
            <tr class="admin_void">
                <td colspan="3"></td>
            </tr>
        </table>
    </div>
    <div id="del_div" style="width: 240px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #AAAAAA; display: none; background-color: #FFFFFF">
    	<p class="pop_label">Elimina una materia</p>
    <?php
    reset($materie);
    foreach ($materie as $idm => $mat) {
    ?>
    	<a href="../shared/no_js.php" class="del_link" id="del_<?php echo $idm ?>"><?php echo $mat ?></a><br />
    <?php
    }
    ?>
    </div>
    <div id="add_div" style="width: 240px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #AAAAAA; display: none; background-color: #FFFFFF">
    	<p class="pop_label">Aggiungi una materia</p>
    <?php
    foreach ($materie_no_cdc as $mnc) {
    ?>
    	<a href="../shared/no_js.php" class="add_link" id="add_<?php echo $mnc ?>"><?php echo $materie[$mnc] ?></a><br />
    <?php
    }
    ?>
    </div>
    <div id="cl_add_div" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #1E4389; border-radius: 4px 4px 4px 4px; display: none; background-color: #FFFFFF"></div>
	<div id="cl_del_div" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #1E4389; border-radius: 4px 4px 4px 4px; display: none; background-color: #FFFFFF"></div>
    <div id="menu_div" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #1E4389; border-radius: 4px 4px 4px 4px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px #888"">
    	<p class="pop_label" id="cls_label" style=""></p>
    	<a href="../shared/no_js.php" id="cl_del" style="padding-left: 10px;">Cancella il CdC</a><br />
    	<a href="../shared/no_js.php" id="cl_rei" style="padding-left: 10px;">Reinserisci il CdC</a><br />
    	<a href="../shared/no_js.php" id="cl_add" style="padding-left: 10px;">Aggiungi una materia</a><br />
    	<a href="../shared/no_js.php" id="cl_sub" style="padding-left: 10px;">Elimina una materia</a>
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
		<div class="drawer_link"><a href="https://www.istitutoiglesiasserraperdosa.gov.it"><img src="../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../shared/do_logout.php"><img src="../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
