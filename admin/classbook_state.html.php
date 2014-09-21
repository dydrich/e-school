<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Gestione record registro di classe</title>
<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../js/page.js"></script>
<script type="text/javascript">
var IE = document.all?true:false;
var tempX = 0;
var tempY = 0;

var selected_class = {"id": 0, "desc": ""};
var need_class = false;
var student = 0;
var selected_day = 0;

var show_div = function(e, div){
	if (IE) { 
        tempX = e.clientX + document.body.scrollLeft;
        tempY = e.clientY + document.body.scrollTop;
    } else {  
        tempX = e.pageX;
        tempY = e.pageY;
    }
    if (div != "menu_div") {
    	tempY -= 10;
    }
    tempX -= 100;
    if (tempX < 0){tempX = 0;}
    if (tempY < 0){tempY = 0;}  
    $('#'+div).css({top: parseInt(tempY)+"px"});
    $('#'+div).css({left: parseInt(tempX)+"px"});
    $('#'+div).show();
};

var show_menu = function(el) {
	if($('#menu_div').is(":hidden")) {
	    position = getElementPosition(el);
	    ftop = position['top'] + $('#'+el).height();
	    fleft = position['left'] + $('#'+el).width();
	    console.log("top: "+ftop+"\nleft: "+fleft);
	    $('#menu_div').css({top: ftop+"px", left: fleft+"px", position: "absolute", zIndex: 100});
	    $('#menu_div').show();
	}
	else {
		$('#menu_div').hide();
	}
};

var do_action = function(action){
	var url = "classbook_manager.php";
	//leftS = (screen.width - 200) / 2;
	//$('wait_label').setStyle({left: leftS+"px"});
	//$('wait_label').setStyle({top: "300px"});
	//$('wait_label').update("Operazione in corso");
	//$('over1').show();
	//$('wait_label').appear({duration: 0.8});
	background_process("Operazione in corso", 20, true);

	$.ajax({
		type: "POST",
		url: url,
		data: {action: action, cls: selected_class.id, std: student, day: selected_day, school_order: <?php echo $school_order ?>},
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

var get_day = function(action){
	if(action == "day_insert" || action == "day_class_insert"){
		_prompt = "Inserisci il giorno da aggiungere nel formato gg/mm/aaaa:";
	}
	else if (action == "day_delete" || action == "day_class_delete"){
		_prompt = "Inserisci il giorno da cancellare nel formato gg/mm/aaaa:";
	}
	else{
		_prompt = "Inserisci il giorno da reimpostare nel formato gg/mm/aaaa:";
	}
	selected_day = prompt(_prompt);
	if(!valida_data(selected_day)){
		j_alert("error", "Data non valida");
		return false;
	}
	do_action(action);
};

var get_student = function(action){
	var url = "get_students.php";

	$.ajax({
		type: "POST",
		url: url,
		data: {cls: selected_class.id, action: action},
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
			else if (json.status == "ko") {
				j_alert("error", json.message);
			}
			else {
				links = json.data;
				$('#list_div').html();

				if(action == "student_delete") {
					sstr = "Elimina uno studente";
					_cl = "del_link";
				}
				else if(action == "student_insert"){
					sstr = "Inserisci uno studente";
					_cl = "ins_link";
				}
				else if(action == "student_reinsert"){
					sstr = "Reinserisci uno studente";
					_cl = "reins_link";
				}

				$("p style='text-align: center; padding: 2px 0 2px 0; width: 100%; font-weight: bold; margin-bottom: 8px; border-bottom: 1px solid rgba(231, 231, 231, 0.9); background-color: rgba(231, 231, 231, 0.4)'>"+sstr+"</p>").appendTo($('#list_div'));

				for(i in links){
					dt = links[i];
					$("<a href='../shared/no_js.php' id='std_"+dt.id+"' class='st_link' style='padding-left: 10px'>"+dt.name+"</a><br />").appendTo($('#list_div'));
				}

				$('#list_div').mouseleave(function(event){
					event.preventDefault();
					$('#list_div').hide();
				});
				$('.st_link').click(function(event){
					event.preventDefault();
					var strs = this.id.split("_");
					student = strs[1];
					do_action(action);
				});
			}
		}
	});
};

$(function(){
	load_jalert();
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
		var strs = this.id.split("_");
		selected_class.id = strs[1];
		selected_class.desc = strs[2];
		$('#menu_label').text("Classe "+selected_class.desc);
		show_div(event, 'menu_cls');
	});
	$('#menu_cls').mouseleave(function(event){
		event.preventDefault();
        $('#menu_cls').hide();
    });
	
	$('.do_link').click(function(event){
		event.preventDefault();
		do_action(this.id);
    });
	$('.day_link').click(function(event){
		event.preventDefault();
		get_day(this.id);
    });
	$('.student_link').click( function(event){
		event.preventDefault();
		get_student(this.id);
		show_div(event, 'list_div');
    });
    
});
</script>
<style>
.small{
	height: 20px
}
</style>
<title>Registro elettronico</title>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "reg_menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">
			<div style="float: left; padding-left: 10px">
				<a href="../shared/no_js.php" id="imglink" style="">
					<img src="../images/19.png" id="ctx_img" style="margin: 0 0 4px 0; opacity: 0.5; vertical-align: bottom" />
				</a>
			</div>
			Gestione tabella registro di classe <?php echo $school_orders[$school_order] ?>
		</div>
        <table style="width: 90%; margin: 20px auto 0 auto; border-collapse: collapse">
            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>
            <?php
            foreach ($cls as $k => $cl) {
            	$num_days = 0;
            	$check_students = true;
            	$check_days = true;
            	if($cl['count_reg_stud'] > 0){
            		$num_days = $cl['count_reg_rec'] / $cl['count_reg_stud'];
            	}
            	if ($cl['count_reg_stud'] != $cl['c_alunni']){
            		$check_students = false;
            	}
            	if (!is_int($num_days)){
            		$check_days = false;
            	}
            	$supposed_days = 0;
            ?>
            <tr class="admin_row small">
	            <td style="width:  5%; font-weight: bold"><?php echo $cl['anno_corso'],$cl['sezione'] ?></td>
	            <td style="width: 45%; padding-left: 15px">Numero di alunni in registro: <span style="font-weight: bold<?php if(!$check_students) echo "; color: red" ?>"><?php echo $cl['count_reg_stud']." di {$cl['c_alunni']}" ?></span></td>
	            <td style="width: 45%; padding-left: 15px">Record totali: <span style='font-weight: bold<?php if(!$check_days) echo "; color: red" ?>'><?php echo "{$cl['count_reg_rec']} in {$num_days} giorni" ?></span></td>
	            <td style="width:  5%">
	            	<p style="width: 15px; height: 15px; text-align: center; margin: 1px 0 0 0">
	            		<a href="../shared/no_js.php" class="img_link" id="imglink_<?php echo $k ?>_<?php echo $cl['anno_corso'],$cl['sezione'] ?>">
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
                <td colspan="4">&nbsp;</td>
            </tr>
        </table>
    </div>
    <div id="list_div" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border-radius: 8px 8px 8px 8px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888"></div>
    <div id="menu_div" style="width: 200px; position: absolute; padding: 10px 0 10px 0px; border-radius: 8px 8px 8px 8px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888">
    	<a href="../shared/no_js.php" id="reinsert" class="do_link" style="padding-left: 10px;">Reinserisci tutto</a><br />
    	<a href="../shared/no_js.php" id="delete" class="do_link" style="padding-left: 10px;">Cancella tutto</a><br />
    	<hr style="width: 95%; margin: auto; padding: 0 10px 0 10px; color: rgba(250, 250, 250, 0.2)" />
    	<a href="../shared/no_js.php" id="delete_vacation" class="do_link" style="padding-left: 10px;">Elimina i giorni di vacanza</a><br />
    	<hr style="width: 95%; margin: auto; padding: 0 10px 0 10px; color: rgba(250, 250, 250, 0.2)" />
    	<a href="../shared/no_js.php" id="verify" class="do_link" style="padding-left: 10px;">Verifica i dati</a><br />
    	<a href="../shared/no_js.php" id="correct" class="do_link" style="padding-left: 10px;">Verifica e correggi i dati</a><br />
    	<hr style="width: 95%; margin: auto; padding: 0 10px 0 10px; color: rgba(250, 250, 250, 0.2)" />
    	<a href="../shared/no_js.php" id="day_reinsert" class="day_link" style="padding-left: 10px;">Reinserisci un giorno</a><br />
    	<a href="../shared/no_js.php" id="day_delete" class="day_link" style="padding-left: 10px;">Cancella un giorno</a><br />
    	<a href="../shared/no_js.php" id="day_insert" class="day_link" style="padding-left: 10px;">Inserisci un giorno</a><br />
    </div>
    <div id="menu_cls" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border-radius: 8px 8px 8px 8px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888">
    	<p id="menu_label" style="text-align: center; padding: 2px 0 2px 0; width: 100%; font-weight: bold; margin-bottom: 8px; border-bottom: 1px solid rgba(231, 231, 231, 0.9); background-color: rgba(231, 231, 231, 0.4)"></p>
    	<a href="../shared/no_js.php" id="class_reinsert" class="do_link" style="padding-left: 10px;">Reinserisci la classe</a><br />
    	<a href="../shared/no_js.php" id="class_delete" class="do_link" style="padding-left: 10px;">Cancella la classe</a><br />
    	<a href="../shared/no_js.php" id="class_insert" class="do_link" style="padding-left: 10px;">Inserisci la classe</a><br />
    	<hr style="width: 95%; margin: auto; padding: 0 10px 0 10px; color: rgba(250, 250, 250, 0.2)" />
    	<a href="../shared/no_js.php" id="student_reinsert" class="student_link" style="padding-left: 10px;">Reinserisci uno studente</a><br />
    	<a href="../shared/no_js.php" id="student_delete" class="student_link" style="padding-left: 10px;">Elimina uno studente</a><br />
    	<a href="../shared/no_js.php" id="student_insert" class="student_link" style="padding-left: 10px;">Aggiungi uno studente</a><br />
    	<hr style="width: 95%; margin: auto; padding: 0 10px 0 10px; color: rgba(250, 250, 250, 0.2)" />
    	<a href="../shared/no_js.php" id="day_class_delete" class="day_link" style="padding-left: 10px;">Elimina un giorno</a><br />
    	<a href="../shared/no_js.php" id="day_class_insert" class="day_link" style="padding-left: 10px;">Aggiungi un giorno</a><br />
    </div>
    <div class="overlay" id="over1" style="display: none">
        <div id="wait_label" style="position: absolute; display: none; padding-top: 25px">Operazione in corso</div>
    </div>
    <input type="hidden" id="day" />

	</div>
<?php include "footer.php" ?>
</body>
</html>
