<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Gestione record registro di classe</title>
<link href="../css/reg.css" rel="stylesheet" />
<link href="../css/general.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/skins/aqua/theme.css" type="text/css"  />
<link rel="stylesheet" href="../css/themes/default.css" type="text/css"/>
<link rel="stylesheet" href="../css/themes/alphacube.css" type="text/css"/>
<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/scriptaculous.js"></script>
<script type="text/javascript" src="../js/controls.js"></script>
<script type="text/javascript" src="../js/window.js"></script>
<script type="text/javascript" src="../js/window_effects.js"></script>
<script type="text/javascript" src="../js/calendar.js"></script>
<script type="text/javascript" src="../js/lang/calendar-it.js"></script>
<script type="text/javascript" src="../js/calendar-setup.js"></script>
<script type="text/javascript" src="../js/page.js"></script>
<script type="text/javascript">
var IE = document.all?true:false;
if (!IE) document.captureEvents(Event.MOUSEMOVE);
var tempX = 0;
var tempY = 0;

var selected_class = new Object;
selected_class.id = 0;
selected_class.desc = "";
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
    $(div).style.top = parseInt(tempY)+"px";
    $(div).style.left = parseInt(tempX)+"px";
    $(div).show();
};

function show_menu(el) {
	if($('menu_div').style.display == "none") {
	    position = getElementPosition(el);
	    dimensions = $(el).getDimensions();
	    ftop = position['top'] + dimensions.height;
	    fleft = position['left'] - 50 + dimensions.width;
	    console.log("top: "+ftop+"\nleft: "+fleft);
	    $('menu_div').setStyle({top: ftop+"px", left: fleft+"px", position: "absolute", zIndex: 100});
	    $('menu_div').show();
	}
	else {
		$('menu_div').hide();
	}
}

var tm = 0;
var complete = false;
var timer;

var do_action = function(action){
	var url = "classbook_manager.php";
	leftS = (screen.width - 200) / 2;
	$('wait_label').setStyle({left: leftS+"px"});
	$('wait_label').setStyle({top: "300px"});
	$('wait_label').update("Operazione in corso");
	$('over1').show();
	$('wait_label').appear({duration: 0.8});
	req = new Ajax.Request(url,
		  {
		    	method:'post',
		    	parameters: {action: action, cls: selected_class.id, std: student, day: selected_day, school_order: <?php echo $school_order ?>},
		    	onSuccess: function(transport){
			    	complete = true;
			    	clearTimeout(timer);
		    		var response = transport.responseText || "no response text";
		    		dati = response.split(";");
		    		//$('wait_label').style.display = "none";
		    		if(dati[0] == "kosql"){
		    			$('over1').hide();
		    			setTimeout("sqlalert()", 100);
		    			console.log("Errore: "+dati[1]+" in: "+dati[2]);
		    			return false;
		    		}
		    		else if (dati[0] == "ko"){
		    			$('over1').hide();
		    			setTimeout("_alert(dati[1])", 100);
						return;
		    		}
		    		else{
		    			$('wait_label').update("Operazione conclusa");
						setTimeout("$('wait_label').fade({duration: 2.0})", 2000);
						setTimeout("document.location.href = document.location.href", 3800);
		    		}
		    	},
		    	onFailure: function(){ alert("Si e' verificato un errore..."); }
		  });
	upd_str();
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
		_alert("Data non valida");
		return false;
	}
	do_action(action);
};

var get_student = function(action){
	var url = "get_students.php";
	req = new Ajax.Request(url,
		  {
		    	method:'post',
		    	parameters: {cls: selected_class.id, action: action},
		    	onSuccess: function(transport){
			    	var response = transport.responseText || "no response text";
		    		dati = response.split(";");
		    		if(dati[0] == "kosql"){
		    			sqlalert();
		    			console.log("Errore: "+dati[1]+" in: "+dati[2]);
		    			return;
		    		}
		    		else if (dati[0] == "ko"){
		    			_alert(dati[1]);
						return;
		    		}
		    		else{
			    		links = dati[1].split("|");
						$('list_div').update();
						_p = document.createElement("P");
						_p.setAttribute("style", "text-align: center; padding: 2px 0 2px 0; width: 100%; font-weight: bold; margin-bottom: 8px; border-bottom: 1px solid rgba(231, 231, 231, 0.9); background-color: rgba(231, 231, 231, 0.4)");
						
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
						
						_p.appendChild(document.createTextNode(sstr));
						$('list_div').appendChild(_p);
						
						for(i = 0; i < links.length; i++){
							dt = links[i].split("#");
							_a = document.createElement("A");
							_a.setAttribute("class", "st_link");
							_a.setAttribute("href", "../shared/no_js.php");
							_a.setAttribute("id", "std_"+dt[0]);
							_a.setAttribute("style", "padding-left: 10px");
							_a.appendChild(document.createTextNode(dt[1]));
							$('list_div').appendChild(_a);
							$('list_div').appendChild(document.createElement("BR"));
						}
						
						$('list_div').observe("mouseleave", function(event){
							event.preventDefault();
					        $('list_div').hide();
					    });
						$$('.st_link').invoke("observe", "click", function(event){
							event.preventDefault();
							var strs = this.id.split("_");
							student = strs[1];
							do_action(action);
						});
		    		}
		    	},
		    	onFailure: function(){ alert("Si e' verificato un errore..."); }
		  });
};

var upd_str = function(){
	tm++;
	//alert(tm);
	if(tm > 5){ 
		tm = 0;
		$('wait_label').update("Operazione in corso");
	}
	else
		$('wait_label').innerHTML += ".";
	timer = setTimeout("upd_str()", 1000);
};

var _hide = function(){
	$('over1').hide();
	$('wait_label').hide();
};

document.observe("dom:loaded", function(){
	$('imglink').observe("click", function(event){
		event.preventDefault();
		show_menu('imglink');
	});
	$('menu_div').observe("mouseleave", function(event){
		event.preventDefault();
        $('menu_div').hide();
    });
	$$('.img_click').invoke("observe", "mouseover", function(event){
		event.preventDefault();
		p = this.parentNode.parentNode;
		p.setStyle({backgroundColor: "rgba(231, 231, 231, 0.8)", border: "1px solid #AAAAAA", borderRadius: "5px"});
	});
	$$('.img_click').invoke("observe", "mouseout", function(event){
		event.preventDefault();
		p = this.parentNode.parentNode;
		p.setStyle({backgroundColor: "", border: "0", borderRadius: "5px"});
	});
	$$('.img_link').invoke("observe", "click", function(event){
		event.preventDefault();
		var strs = this.id.split("_");
		selected_class.id = strs[1];
		selected_class.desc = strs[2];
		$('menu_label').update("Classe "+selected_class.desc);
		show_div(event, 'menu_cls');
	});
	$('menu_cls').observe("mouseleave", function(event){
		event.preventDefault();
        $('menu_cls').hide();
    });
	
	$$('.do_link').invoke("observe", "click", function(event){
		event.preventDefault();
		do_action(this.id);
    });
	$$('.day_link').invoke("observe", "click", function(event){
		event.preventDefault();
		get_day(this.id);
    });
	$$('.student_link').invoke("observe", "click", function(event){
		event.preventDefault();
		get_student(this.id);
		show_div(event, 'list_div');
    });
    
});
</script>
<style>
#wait_label{
	width: 200px;
	height: 40px;
	text-align: center;
	background-color: #000000; 
	border: 1px solid #CCCCCC; 
	border-radius: 8px 8px 8px 8px;
	color: white;
	font-weight: bold;
	vertical-align: middle;
}
div.overlay{
    background-image: url(../images/overlay.png);
    position: absolute;
    top: 0px;
    left: 0px;
    z-index: 90;
    width: 100%;
    height: 100%;
}
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
