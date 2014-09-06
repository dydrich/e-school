<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Gestione record CDC</title>
<link href="../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
<link href="../css/general.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/themes/default.css" type="text/css"/>
<link rel="stylesheet" href="../css/themes/alphacube.css" type="text/css"/>
<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/scriptaculous.js"></script>
<script type="text/javascript" src="../js/controls.js"></script>
<script type="text/javascript" src="../js/window.js"></script>
<script type="text/javascript" src="../js/window_effects.js"></script>
<script type="text/javascript" src="../js/page.js"></script>
<script type="text/javascript">
var IE = document.all?true:false;
if (!IE) document.captureEvents(Event.MOUSEMOVE);
var tempX = 0;
var tempY = 0;

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
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {action: action, cls: cls, school_order: <?php echo $_GET['school_order'] ?>},
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
			    		dati = response.split(";");
			    		if(dati[0] == "kosql"){
				    		_alert("Si e` verificato un errore.");
			    			console.log("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
			    			return;
			    		}
			    		else if (dati[0] == "ko") {
							_alert("Impossibile completare l'operazione richiesta: "+dati[1]);
							return;
			    		}
			    		else{
							_alert("Operazione conclusa con successo");
							window.setTimeout("document.location.href = document.location.href", 2000);
			    		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
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
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {action: action, subject: subject, cls: cls, school_order: <?php echo $_GET['school_order'] ?>},
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
			    		dati = response.split(";");
			    		if(dati[0] == "kosql"){
				    		_alert("Si e` verificato un errore.");
			    			console.log("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
			    			return;
			    		}
			    		else if (dati[0] == "ko") {
							_alert("Impossibile completare l'operazione richiesta: "+dati[1]);
							console.log(dati[2]);
							return;
			    		}
			    		else{
							_alert("Operazione conclusa con successo");
							window.setTimeout("document.location.href = document.location.href", 2000);
			    		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
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
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {action: action, subject: subject, cls: cls, school_order: <?php echo $_GET['school_order'] ?>},
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
			    		dati = response.split(";");
			    		if(dati[0] == "kosql"){
				    		_alert("Si e` verificato un errore.");
			    			console.log("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
			    			return;
			    		}
			    		else if (dati[0] == "ko") {
							_alert("Impossibile completare l'operazione richiesta su add_subject: "+dati[1]);
							console.log(dati[2]);
							return;
			    		}
			    		else{
							_alert("Operazione conclusa con successo");
							window.setTimeout("document.location.href = document.location.href", 2000);
			    		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};

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

var need_class = false;
var selected_class = new Object;
selected_class.id = 0;
selected_class.desc = "";

var populate_div = function(event){
	var url = "get_add_subjects.php";
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {cls: selected_class.id, source: "cdc", school_order: <?php echo $_GET['school_order'] ?>},
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
			    		dati = response.split(";");
			    		if(dati[0] == "kosql"){
				    		_alert("Si e` verificato un errore.");
			    			console.log("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
			    			return;
			    		}
			    		else if (dati[0] == "ko") {
							_alert("Impossibile completare l'operazione richiesta su populate: "+dati[1]);
							console.log(dati[2]);
							return;
			    		}
			    		else{
							links = dati[1].split("#");
							$('cl_add_div').update();
							_p = document.createElement("P");
							//_p.setAttribute("style", "text-align: center; padding: 2px 0 2px 0; width: 100%; font-weight: bold; margin-bottom: 8px; border-bottom: 1px solid rgba(231, 231, 231, 0.9); background-color: rgba(231, 231, 231, 0.4)");
							_p.setAttribute("id", "menu_label");
						    _p.appendChild(document.createTextNode("Aggiungi una materia"));
							$('cl_add_div').appendChild(_p);
							for(i = 0; i < links.length; i++){
								dt = links[i].split("|");
								_a = document.createElement("A");
								_a.setAttribute("class", "add_link");
								_a.setAttribute("href", "../shared/no_js.php");
								_a.setAttribute("id", "add_"+dt[0]);
								_a.appendChild(document.createTextNode(dt[1]));
								$('cl_add_div').appendChild(_a);
								$('cl_add_div').appendChild(document.createElement("BR"));
							}
							$('cl_add_div').observe("mouseleave", function(event){
								event.preventDefault();
						        $('cl_add_div').hide();
						    });
							$$('.add_link').invoke("observe", "click", function(event){
								event.preventDefault();
								var strs = this.id.split("_");
								add_subject(strs[1]);
							});
							show_div(event, 'cl_add_div');
			    		}
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};

document.observe("dom:loaded", function(){
	$('reins').observe("click", function(event){
		event.preventDefault();
		crea_cdc('reinsert');
	});
	$('del_sub').observe("mouseover", function(event){
		event.preventDefault();
		need_class = false;
		show_div(event, 'del_div');
	});
	$('add_sub').observe("mouseover", function(event){
		event.preventDefault();
		need_class = false;
		show_div(event, 'add_div');
	});
	$('del_div').observe("mouseleave", function(event){
		event.preventDefault();
        $('del_div').hide();
    });
	$('add_div').observe("mouseleave", function(event){
		event.preventDefault();
        $('add_div').hide();
    });
	$('menu_div').observe("mouseleave", function(event){
		event.preventDefault();
        $('menu_div').hide();
    });
	$$('.del_link').invoke("observe", "click", function(event){
		event.preventDefault();
		var strs = this.id.split("_");
		del_subject(strs[1]);
	});
	$$('.add_link').invoke("observe", "click", function(event){
		event.preventDefault();
		var strs = this.id.split("_");
		add_subject(strs[1]);
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
		show_div(event, 'menu_div');
	});
	$('cl_del').observe("click", function(event){
		event.preventDefault();
		need_class = true;
        crea_cdc("cl_delete");
    });
	$('cl_rei').observe("click", function(event){
		event.preventDefault();
		need_class = true;
		crea_cdc("cl_reinsert");
    });
	$('cl_add').observe("click", function(event){
		event.preventDefault();
		need_class = true;
		populate_div(event);
    });
	$('cl_sub').observe("click", function(event){
		event.preventDefault();
		need_class = true;
		show_div(event, 'del_div');
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
.del_link, .add_link{
	padding-left: 10px
}
</style>
<title>Registro elettronico</title>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "cdc_menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Gestione tabella Consigli di classe</div>
		<table style="width: 90%; margin: 20px auto 0 auto; border-collapse: collapse">
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
            	<td colspan="3">
            		<div class="admin_link_wrapper">
            			<a href="../shared/no_js.php" id="add_sub" style="margin-right: 10px">Aggiungi una materia per tutte le classi</a>|
            			<a href="../shared/no_js.php" id="del_sub" style="margin: 0 10px 0 10px">Elimina una materia per tutte le classi</a>|
            			<a href="../shared/no_js.php" id="reins" style="margin-left: 10px">Cancella e reinserisci tutto</a>
            		</div>
            	</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <?php
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
            <tr class="admin_row">
	            <td style="width:  5%; font-weight: bold"><?php echo $cl['anno_corso'],$cl['sezione'] ?></td>
	            <td style="width: 90%"><?php echo $cdc_str ?></td>
	            <td style="width:  5%">
	            	<p style="width: 25px; height: 25px; line-height: 41px; text-align: center; margin: 6px 0 0 0">
	            		<a href="../shared/no_js.php" class="img_link" id="imglink_<?php echo $k ?>_<?php echo $cl['anno_corso'],$cl['sezione'] ?>">
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
            <tr class="admin_menu">
                <td colspan="3">
                	<a href="index.php" class="nav_link_last">Torna menu</a>
                </td>
            </tr>
            <tr class="admin_void">
                <td colspan="3"></td>
            </tr>
        </table>
    </div>
    <div id="del_div" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #AAAAAA; border-radius: 8px 8px 8px 8px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888">
    	<p class="pop_title">Elimina una materia</p>
    <?php
    reset($materie);
    foreach ($materie as $idm => $mat) {
    ?>
    	<a href="../shared/no_js.php" class="del_link" id="del_<?php echo $idm ?>"><?php echo $mat ?></a><br />
    <?php
    }
    ?>
    </div>
    <div id="add_div" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #AAAAAA; border-radius: 8px 8px 8px 8px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888">
    	<p class="pop_title">Aggiungi una materia</p>
    <?php
    foreach ($materie_no_cdc as $mnc) {
    ?>
    	<a href="../shared/no_js.php" class="add_link" id="add_<?php echo $mnc ?>"><?php echo $materie[$mnc] ?></a><br />
    <?php
    }
    ?>
    </div>
    <div id="cl_add_div" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #1E4389; border-radius: 8px 8px 8px 8px; display: none; background-color: #FFFFFF"></div>
    <div id="menu_div" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #1E4389; border-radius: 8px 8px 8px 8px; display: none; background-color: #FFFFFF">
    	<p id="menu_label" style=""></p>
    	<a href="../shared/no_js.php" id="cl_del" style="padding-left: 10px;">Cancella il CdC</a><br />
    	<a href="../shared/no_js.php" id="cl_rei" style="padding-left: 10px;">Reinserisci il CdC</a><br />
    	<a href="../shared/no_js.php" id="cl_add" style="padding-left: 10px;">Aggiungi una materia</a><br />
    	<a href="../shared/no_js.php" id="cl_sub" style="padding-left: 10px;">Elimina una materia</a>
    </div>
	<p class="spacer"></p>
	</div>
<?php include "footer.php" ?>
</body>
</html>
