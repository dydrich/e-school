<!DOCTYPE html>
<html>
<head>
<title>Dettaglio modulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../../css/reg.css" rel="stylesheet" />
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
var messages = new Array('', 'Giorno inserito con successo', 'Giorno cancellato con successo', 'Modulo modificato con successo');
var cday = 1;
var next = 2;
var previous = 6;
var idm = <?php echo $idm ?>;

function go(){
    $('_i').setValue(idm);
    $('cday').setValue(cday);
    check = $('day_'+cday).checked;
    if(check){
    	$('action').setValue("update");
    	par = 3;
    }
    else {
    	$('action').setValue("insert");
    	par = 1;
    }
    $('day_'+cday).checked = true;
    var url = "module_manager.php";
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: $('site_form').serialize(true),
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		if(dati[0] == "kosql"){
				      		_alert("Impossibile completare l'operazione. Si prega di riprovare tra poco");
							console.log("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
							return;
			      		}
			      		$('ore_s').update(dati[1]);
			      		$('giorni_s').update(dati[2]);
			      		_alert(messages[par]);	
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
}

var get_module_day = function(module, day){
	var url = "get_day.php";
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {module: module, day: day},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		//alert(response);
			      		var dati = response.split("|");
			      		if(dati[0] == "kosql"){
				      		_alert("Impossibile completare l'operazione. Si prega di riprovare tra poco");
							console.log("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
							return;
			      		}
			      		$('durata').value = dati[1];
						$('ore').value = dati[2];
						$('start').value = dati[3];
						$('end').value = dati[4];
						$('day_legend').update(dati[5]);
						cday = dati[6];
						next = dati[7];
						previous = dati[8];
						$('previous_button').update("&lt;&lt; "+dati[10]);
						$('next_button').update(dati[9]+" &gt;&gt;");
						if (dati[11] == 1){
							$('mensa').checked = true;
							$('mensa_r1').show();
							$('mensa_r2').show();
							$('start_m').value = dati[12];
							$('end_m').value = dati[13];
						}
						else {
							$('mensa').checked = false;
							$('mensa_r1').hide();
							$('mensa_r2').hide();
						}
						
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
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
		req = new Ajax.Request(url,
				  {
				    	method:'post',
				    	parameters: {action: 'delete_day', idm: idm, cday: day_number},
				    	onSuccess: function(transport){
				      		var response = transport.responseText || "no response text";
				      		//alert(response);
				      		var dati = response.split("|");
				      		if(dati[0] == "kosql"){
					      		_alert("Impossibile completare l'operazione. Si prega di riprovare tra poco");
								console.log("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
								return;
				      		}
				      		$('ore_s').update(dati[1]);
				      		$('giorni_s').update(dati[2]);
				      		_alert(messages[2]);	
				    	},
				    	onFailure: function(){ alert("Si e' verificato un errore..."); }
				  });
	}
};

document.observe("dom:loaded", function(){
	if($('mensa').checked) {
		$('mensa_r1').show();
		$('mensa_r2').show();
	}
	$('mensa').observe("change", function(event){
		event.preventDefault();
		if(this.checked){
			$('mensa_r1').appear({duration: 1.0});
			$('mensa_r2').appear({duration: 1.0});
		}
		else {
			$('mensa_r1').fade({duration: 1.0});
			$('mensa_r2').fade({duration: 1.0});
		}
	});
	$('save_button').observe("click", function(event){
		event.preventDefault();
		go();
	});
	$('previous_button').observe("click", function(event){
		event.preventDefault();
		get_module_day(idm, previous);
	});
	$('next_button').observe("click", function(event){
		event.preventDefault();
		get_module_day(idm, next);
	});
	$$('.days').invoke("observe", "change", function(event){
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
		<div class="group_head">Gestione modulo orario</div>
    <form action="sites_manager.php" method="post" id="site_form" class="popup_form" style="width: 90%">
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
</body>
</html>