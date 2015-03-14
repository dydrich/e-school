<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Dettaglio modulo</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link href="../../css/general.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javaScript">
		var cday = 1;
		var next = 2;
		var previous = 6;
		var idm = <?php echo $idm ?>;

		var go = function(){
		    $('#_i').val(idm);
		    $('#cday').val(cday);
		    check = $('#day_'+cday).prop("checked");
		    if(check){
		        $('#action').val("update");
		        par = 3;
		    }
		    else {
		        $('#action').val("insert");
		        par = 1;
		    }
		    $('#day_'+cday).prop("checked", true);
		    var url = "module_manager.php";

			$.ajax({
				type: "POST",
				url: url,
				data:  $('#site_form').serialize(true),
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
						alert(json.message);
						console.log(json.dbg_message);
					}
					else {
						$('#ore_s').text(json.h);
						$('#giorni_s').text(json.d);
						j_alert("alert", json.message);
					}
				}
			});
		};

		var get_module_day = function(module, day){
			var url = "get_day.php";

			$.ajax({
				type: "POST",
				url: url,
				data:  {module: module, day: day},
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
						alert(json.message);
						console.log(json.dbg_message);
					}
					else {
						$('#durata').val(json.durata);
						$('#ore').val(json.ore);
						$('#start').val(json.start);
						$('#end').val(json.end);
						$('#day_legend').html(json.day_legend);
						cday = json.cday;
						next = json.next;
						previous = json.previous;
						$('#previous_button').html("&lt;&lt; "+json.previous_button);
						$('#next_button').html(json.next_button+" &gt;&gt;");
						if (json.has_canteen == 1){
							$('#mensa').prop("checked", true);
							$('#mensa_r1').show();
							$('#mensa_r2').show();
							$('#start_m').val(json.canteen_start);
							$('#end_m').val(json.canteen_end);
						}
						else {
							$('#mensa').prop("checked", false);
							$('#mensa_r1').hide();
							$('#mensa_r2').hide();
						}
					}
				}
			});
		};

		var update_days = function(elem){
			nm = elem.id;
			day_number = nm.substr(4, 1);
			value = 0;
			get_module_day(idm, day_number);
			if (elem.checked){
				value = 1;
			}
			else {
				var url = "module_manager.php";

				$.ajax({
					type: "POST",
					url: url,
					data:  {action: 'delete_day', idm: idm, cday: day_number},
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
							alert(json.message);
							console.log(json.dbg_message);
						}
						else {
							$('#ore_s').text(json.h);
							$('#giorni_s').text(json.d);
							j_alert("alert", json.message);
						}
					}
				});
			}
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#start').timepicker();
			$('#end').timepicker();
			if($('#mensa').prop("checked")) {
				$('#mensa_r1').show();
				$('#mensa_r2').show();
			}
			$('#mensa').change(function(event){
				event.preventDefault();
				if(this.checked){
					$('#mensa_r1').show(1000);
					$('#mensa_r2').show(1000);
				}
				else {
					$('#mensa_r1').hide(1000);
					$('#mensa_r2').hide(1000);
				}
			});
			$('#save_button').click(function(event){
				event.preventDefault();
				go();
			});
			$('#previous_button').click(function(event){
				event.preventDefault();
				get_module_day(idm, previous);
			});
			$('#next_button').click(function(event){
				event.preventDefault();
				get_module_day(idm, next);
			});
			$('.days').change(function(event){
				update_days(this);
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
    <form action="venues_manager.php" method="post" id="site_form" class="popup_form" style="width: 90%">
    <div style="width: 20%; margin: 10px auto; border: 1px solid gray; border-radius: 8px; padding: 10px 30px 10px 10px; float: left; margin-left: 5%">
    	<p style="margin: 0; height: 15px"><span style="font-weight: bold">Modulo n. </span><span style="float: right"><?php echo $_GET['idm'] ?></span></p>
    	<p style="margin: 0"><span style="font-weight: bold">Ore settimanali </span><span id="ore_s" style="float: right"><?php if ($module) echo substr($module->getClassDuration()->toString(RBTime::$RBTIME_SHORT), 0, 2) ?></span></p>
    	<p style="margin: 0; padding-bottom: 5px"><span style="font-weight: bold">Giorni di lezione </span><span id="giorni_s" style="float: right"><?php if ($module) echo $module->getNumberOfDays() ?></span></p>
    	<p style="margin: 0; padding-top: 5px; height: 25px">Luned&igrave;<span style="float: right"><input type="checkbox" name="day_1" id="day_1" class="days" <?php if($module && $module->getDay(1)) echo "checked" ?> /></span></p>
    	<p style="margin: 0; height: 25px">Marted&igrave;<span style="float: right"><input type="checkbox" name="day_2" id="day_2" class="days" <?php if($module && $module->getDay(2)) echo "checked" ?> /></span></p>
    	<p style="margin: 0; height: 25px">Mercoled&igrave;<span style="float: right"><input type="checkbox" name="day_3" id="day_3" class="days" <?php if($module && $module->getDay(3)) echo "checked" ?> /></span></p>
    	<p style="margin: 0; height: 25px">Gioved&igrave;<span style="float: right"><input type="checkbox" name="day_4" id="day_4" class="days" <?php if($module && $module->getDay(4)) echo "checked" ?> /></span></p>
    	<p style="margin: 0; height: 25px">Venerd&igrave;<span style="float: right"><input type="checkbox" name="day_5" id="day_5" class="days" <?php if($module && $module->getDay(5)) echo "checked" ?> /></span></p>
    	<p style="margin: 0; height: 25px">Sabato<span style="float: right"><input type="checkbox" name="day_6" id="day_6" class="days" <?php if($module && $module->getDay(6)) echo "checked" ?> /></span></p>
    </div>
    <fieldset style="width: 55%; margin: 0 auto; border: 1px solid gray; border-radius: 8px; padding: 10px 30px 10px 10px; float: right; margin-right: 5%">
    <legend id="day_legend">Luned&igrave;</legend>
    <table style="width: 90%; margin: auto">
        <tr class="popup_row">
            <td colspan="2">Durata ora di lezione</td>
            <td colspan="2"><input class="form_input" type="text" placeholder="durata in minuti" name="durata" id="durata" value="<?php if($day) echo ($day->getHourDuration()->getTime() / 60) ?>" style="width: 80%" /></td>
        </tr>
        <tr class="popup_row">
            <td colspan="2">Numero ore di lezione</td>
            <td colspan="2"><input class="form_input" type="text" name="ore" id="ore" value="<?php if($day) echo $day->getNumberOfHours() ?>" style="width: 80%" /></td>
        </tr>
        <tr class="popup_row">
            <td colspan="2">Inizio lezioni</td>
            <td colspan="2"><input class="form_input" type="text" placeholder="nel formato hh:mm" name="start" id="start" value="<?php if($day) echo $day->getEnterTime()->toString(RBTime::$RBTIME_SHORT) ?>" style="width: 80%" /></td>
        </tr>
        <tr class="popup_row">
            <td colspan="2">Termine lezioni</td>
            <td colspan="2"><input class="form_input" type="text" name="end" placeholder="nel formato hh:mm" id="end" value="<?php if($day) echo $day->getExitTime()->toString(RBTime::$RBTIME_SHORT) ?>" style="width: 80%" /></td>
        </tr>
        <tr class="popup_row">
            <td colspan="2">Mensa</td>
            <td colspan="2"><input class="form_input" type="checkbox" name="mensa" id="mensa" style="width: 80%" value="1" <?php if($day && $day->hasCanteen()) echo "checked" ?> /></td>
        </tr>
        <tr class="popup_row" id="mensa_r1" style="display: none">
            <td colspan="2">Inizio mensa</td>
            <td colspan="2"><input class="form_input" type="text" placeholder="nel formato hh:mm" name="start_m" id="start_m" value="<?php if($day && $day->hasCanteen()) echo $day->getCanteenStart()->toString(RBTime::$RBTIME_SHORT) ?>" style="width: 80%" /></td>
        </tr>
        <tr class="popup_row" id="mensa_r2" style="display: none">
            <td colspan="2">Durata mensa</td>
            <td colspan="2"><input class="form_input" type="text" name="end_m" placeholder="durata in minuti" id="end_m" value="<?php if($day && $day->hasCanteen()) echo ($day->getCanteenDuration()->getTime() / 60) ?>" style="width: 80%" /></td>
        </tr>
        <tr class="popup_row" style="height: 40px">
            <td colspan="4">
            	<input type="hidden" name="action" id="action" />
    			<input type="hidden" name="_i" id="_i" />
    			<input type="hidden" name="cday" id="cday" />
            </td>
        </tr>
        <tr>
        	<td style="width: 30%; text-align: left">
                <a href="../../shared/no_js.php" id="previous_button" class="standard_link nav_link">&lt;&lt; Sabato</a>
            </td>
            <td colspan="2" style="text-align: center">
                <a href="../../shared/no_js.php" id="save_button" class="standard_link nav_link _hover">Registra</a>
            </td>
            <td style="width: 30%; text-align: right">
                <a href="../../shared/no_js.php" id="next_button" class="standard_link nav_link">Marted&igrave; &gt;&gt;</a>
            </td>
        </tr>
    </table>
    </fieldset>
    <p style="clear: both"></p>
    <div style="width: 95%; text-align: right; margin-top: 25px">
    	<a  href="moduli_orario.php" id="close_button" class="standard_link nav_link">Torna ai moduli</a>
    </div>
   	</form>
   	</div>
	<p class="spacer"></p>
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
</body>
</html>
