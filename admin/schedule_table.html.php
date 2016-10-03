<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Gestione record orario</title>
	<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../css/general.css" type="text/css" />
	<link href="../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../js/page.js"></script>
	<script type="text/javascript">
		var selected_class = {};
		selected_class.id = 0;
		selected_class.desc = "";
		var need_class = false;

		var show_div = function(e, off){
			if ($('#menu_cls').is(":visible")) {
				$('#menu_cls').slideUp(500);
				$('#tr'+selected_class.id).removeClass("accent_decoration");
				return;
			}
			off.left -= $('#menu_cls').width() - 30;
			$('#menu_cls').css({top: off.top+"px"});
		    $('#menu_cls').css({left: off.left+"px"});
		    $('#menu_cls').slideDown(500);
			$('#tr'+selected_class.id).addClass("accent_decoration");
		};

		var do_action = function(action){
			$('#drawer').hide();
			var url = "schedule_manager.php";
			if (action == "delete") {
				if (!confirm("Questa operazione cancella tutti i dati, relativi all'orario, in archivio: sei sicuro di voler continuare?")) {
					$('#overlay').hide();
					return false;
				}
			}
			else if (action == "reinsert") {
				if (!confirm("Questa operazione azzera tutti i dati, relativi all'orario, in archivio: sei sicuro di voler continuare?")) {
					$('#overlay').hide();
					return false;
				}
			}
			background_process("Operazione in corso", 20, true);
			$.ajax({
				type: "POST",
				url: url,
				data: {action: action, cls: selected_class.id},
				dataType: 'json',
				error: function() {
					clearTimeout(bckg_timer);
					$('#background_msg').text("Errore di trasmissione dei dati");
					setTimeout(function() {
						$('#background_msg').dialog("close");
					}, 2000);
					console.log(json.dbg_message);
					//j_alert("error", "Errore di trasmissione dei dati");
				},
				succes: function() {

				},
				complete: function(data){
					clearTimeout(bckg_timer);
					r = data.responseText;
					if(r == "null"){
						return false;
					}
					var json = $.parseJSON(r);
					if (json.status == "kosql"){
						$('#background_msg').text(json.message);
						setTimeout(function() {
							$('#background_msg').dialog("close");
						}, 2000);
						console.log(json.dbg_message);
					}
					else {
						$('#background_msg').text("Operazione conclusa");
						setTimeout(function() {
							document.location.href = document.location.href;
						}, 1500);
					}
				}
			});
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#imglink').click(function(event){
				event.preventDefault();
				show_menu('imglink');
			});
			$('#menu_div').mouseleave(function(event){
				event.preventDefault();
		        $('#menu_div').hide();
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
				selected_class.desc = $(this).attr("data-cls");
				selected_class.order = $(this).attr("data-order");
				$('#menu_label').text("Classe "+selected_class.desc+" "+selected_class.order);
				var off = $(this).parent().parent().offset();
				off.top += $(this).parent().parent().height();
				show_div(event, off);
			});
			$('#menu_cls').mouseleave(function(event){
				event.preventDefault();
		        $('#menu_cls').hide();
				$('#tr'+selected_class.id).removeClass("accent_decoration");
		    });

			$('.do_link').click(function(event){
				event.preventDefault();
				do_action(this.id);
		    });

		});
	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "new_year_menu.php" ?>
	</div>
	<div id="left_col">
		<div style="position: absolute; top: 75px; margin-left: 625px; margin-bottom: -5px" class="rb_button">
			<a href="../shared/no_js.php" id="reinsert" class="do_link">
				<img src="../images/45.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<div style="position: absolute; top: 75px; margin-left: 555px; margin-bottom: -5px" class="rb_button">
			<a href="../shared/no_js.php" id="delete" class="do_link">
				<img src="../images/52.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<table style="width: 90%; margin: 0 auto 0 auto; border-collapse: collapse">
            <tr>
                <td colspan="5">&nbsp;</td>
            </tr>
            <?php
            foreach ($cls as $k => $cl) {
				$module = $cl->get_modulo_orario();
				$sel_h = "SELECT COUNT(*) FROM rb_orario WHERE classe = {$cl->get_ID()} AND anno = {$_SESSION['__current_year__']->get_ID()}";
				$h = $db->executeCount($sel_h);
	            $sc_order = "SM";
	            if ($cl->getSchoolOrder() == 2){
		            $sc_order = "SP";
	            }
	            else if ($cl->getSchoolOrder() == 3){
		            $sc_order = "SI";
	            }
            ?>
            <tr class="admin_row_small" id="tr<?php echo $k ?>">
	            <td style="width:  10%; font-weight: bold"><?php echo $cl->get_anno(),$cl->get_sezione(),' ',$sc_order ?></td>
	            <td style="width: 30%; padding-left: 15px">Numero di giorni settimanali: <span style="font-weight: bold"><?php echo $module->getNumberOfDays() ?></span></td>
	            <td style="width: 30%; padding-left: 15px">Numero di ore settimanali: <span style='font-weight: bold'><?php echo $module->getClassDuration()->toString(RBTime::$RBTIME_SHORT) ?></span></td>
	            <td style="width: 25%; padding-left: 15px">In archivio: <span style='font-weight: bold'><?php echo $h ?> ore</span></td>
	            <td style="width:  5%">
	            	<p style="width: 15px; height: 15px; text-align: center; margin: 1px 0 0 0">
	            		<a href="../shared/no_js.php" class="img_link" id="imglink_<?php echo $k ?>" data-id="<?php echo $k ?>" data-cls="<?php echo $cl->get_anno(),$cl->get_sezione() ?>" data-order="<?php echo $sc_order ?>">
	            			<img src="../images/click.png" style="margin: 0; opacity: 0.5" class="img_click" />
	            		</a>
	            	</p>
	            </td>
	        </tr>
	        <?php
            }
	        ?>
	        <tr class="admin_void">
                <td colspan="4"></td>
            </tr>
            <tr class="admin_void">
                <td colspan="4"></td>
            </tr>
        </table>
    </div>
    <div id="list_div" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #AAAAAA; border-radius: 4px 4px 4px 4px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888"></div>
    <div id="menu_cls" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #AAAAAA; border-radius: 4px 4px 4px 4px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888">
    	<p id="menu_label" style=""></p>
    	<a href="../shared/no_js.php" id="class_reinsert" class="do_link" style="padding-left: 10px;">Reinserisci la classe</a><br />
    	<a href="../shared/no_js.php" id="class_delete" class="do_link" style="padding-left: 10px;">Cancella la classe</a><br />
    	<a href="../shared/no_js.php" id="class_insert" class="do_link" style="padding-left: 10px;">Inserisci la classe</a><br />
    </div>
    <div class="overlay" id="over1" style="display: none">
        <div id="wait_label" style="position: absolute; display: none; padding-top: 25px">Operazione in corso</div>
    </div>
    <input type="hidden" id="day" />
	<p class="spacer"></p>
	</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="../index.php"><img src="../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="index.php"><img src="../images/31.png" style="margin-right: 10px; position: relative; top: 5%" />Admin</a></div>
		<div class="drawer_link"><a href="https://www.istitutoiglesiasserraperdosa.gov.it"><img src="../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../shared/do_logout.php"><img src="../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
