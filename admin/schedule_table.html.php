<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Gestione record orario</title>
	<link href="../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
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
	    fleft = position['left'] - 150 + dimensions.width;
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
	var url = "schedule_manager.php";
	leftS = (screen.width - 200) / 2;
	$('wait_label').setStyle({left: leftS+"px"});
	$('wait_label').setStyle({top: "300px"});
	$('wait_label').update("Operazione in corso");
	$('over1').show();
	$('wait_label').appear({duration: 0.8});
	req = new Ajax.Request(url,
		  {
		    	method:'post',
		    	parameters: {action: action, cls: selected_class.id},
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
		selected_class.order = strs[3];
		$('menu_label').update("Classe "+selected_class.desc+" "+selected_class.order);
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
.group_head{
	padding-top: 5px; 
	padding-bottom: 5px; 
	text-align: center; 
	font-weight: bold; 
	background-color: #E7E7E7; 
	border-radius: 5px 5px 5px 5px
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
		<?php include "new_year_menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">
			<div style="float: left; padding-left: 15px">
			<a href="../shared/no_js.php" id="imglink" style="">
				<img src="../images/19.png" id="ctx_img" style="margin: 0 0 4px 0; opacity: 0.5; vertical-align: bottom" />
			</a>
			</div>
			Gestione tabella orario
		</div>
        <table style="width: 90%; margin: 20px auto 0 auto; border-collapse: collapse">
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
            <tr class="admin_row_small">
	            <td style="width:  10%; font-weight: bold"><?php echo $cl->get_anno(),$cl->get_sezione(),' ',$sc_order ?></td>
	            <td style="width: 30%; padding-left: 15px">Numero di giorni settimanali: <span style="font-weight: bold"><?php echo $module->getNumberOfDays() ?></span></td>
	            <td style="width: 30%; padding-left: 15px">Numero di ore settimanali: <span style='font-weight: bold'><?php echo $module->getClassDuration()->toString(RBTime::$RBTIME_SHORT) ?></span></td>
	            <td style="width: 25%; padding-left: 15px">In archivio: <span style='font-weight: bold'><?php echo $h ?> ore</span></td>
	            <td style="width:  5%">
	            	<p style="width: 15px; height: 15px; text-align: center; margin: 1px 0 0 0">
	            		<a href="../shared/no_js.php" class="img_link" id="imglink_<?php echo $k ?>_<?php echo $cl->get_anno(),$cl->get_sezione() ?>_<?php echo $sc_order ?>">
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
            <tr class="admin_menu">
                <td colspan="4">
                	<a href="index.php" class="standard_link nav_link_last">Torna menu</a>
                </td>
            </tr>
            <tr class="admin_void">
                <td colspan="4"></td>
            </tr>
        </table>
    </div>
    <div id="list_div" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #AAAAAA; border-radius: 8px 8px 8px 8px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888"></div>
    <div id="menu_div" style="width: 200px; position: absolute; padding: 10px 0 10px 0px; border: 1px solid #AAAAAA; border-radius: 8px 8px 8px 8px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888">
    	<a href="../shared/no_js.php" id="reinsert" class="do_link" style="padding-left: 10px;">Reinserisci tutto</a><br />
    	<a href="../shared/no_js.php" id="delete" class="do_link" style="padding-left: 10px;">Cancella tutto</a><br />
    	<hr style="width: 95%; margin: auto; padding: 0 10px 0 10px; color: rgba(250, 250, 250, 0.2)" />
    </div>
    <div id="menu_cls" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #AAAAAA; border-radius: 8px 8px 8px 8px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888">
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
</body>
</html>
