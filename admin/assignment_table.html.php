<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Gestione record scrutini</title>
<link href="../css/site_themes/blue_red/reg.css" rel="stylesheet" />
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

var scr_records = new Array();
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

var mat = new Array();
<?php
foreach ($materie as $k => $a) {
?>
mat[<?php echo $k ?>] = '<?php echo truncateString($a, 25) ?>';
<?php
}
?>

var assignment_marks = function(action){
	and_class = "";
	cls = null;
	if (action != "reinsert") {
		and_class = "per la classe";
		cls = selected_class.id;
	}
		
	if (!confirm("Sei sicuro di voler reinserire i record nella tabella? Questa operazione cancellera` tutti i dati inseriti "+and_class+".")) {
		return false;
	}
	var url = "popola_tabella_scrutini.php";
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {quadrimestre: <?php echo $_REQUEST['quadrimestre'] ?>, action: action, cls: cls},
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
			    		dati = response.split(";");
			    		if(dati[0] == "kosql"){
				    		sqlalert();
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
	var url = "popola_tabella_scrutini.php";
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {quadrimestre: <?php echo $_REQUEST['quadrimestre'] ?>, action: action, subject: subject, cls: cls},
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
	var url = "popola_tabella_scrutini.php";
	req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {quadrimestre: <?php echo $_REQUEST['quadrimestre'] ?>, action: action, subject: subject, cls: cls},
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
			    	parameters: {cls: selected_class.id, source: "scr", quadrimestre: <?php echo $_REQUEST['quadrimestre'] ?> },
			    	onSuccess: function(transport){
			    		var response = transport.responseText || "no response text";
			    		dati = response.split(";");
			    		//alert(response);
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
							links = dati[1].split("#");
							$('cl_add_div').update();
							_p = document.createElement("P");
							_p.setAttribute("style", "text-align: center; padding: 2px 0 2px 0; width: 100%; font-weight: bold; margin-bottom: 8px; border-bottom: 1px solid rgba(231, 231, 231, 0.9); background-color: rgba(231, 231, 231, 0.4)");
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
			    		}
			    		/*
							cl_del_div
			    		*/
			    		$('cl_del_div').update();
						_p = document.createElement("P");
						_p.setAttribute("style", "text-align: center; padding: 2px 0 2px 0; width: 100%; font-weight: bold; margin-bottom: 8px; border-bottom: 1px solid rgba(231, 231, 231, 0.9); background-color: rgba(231, 231, 231, 0.4)");
						_p.appendChild(document.createTextNode("Elimina una materia"));
						$('cl_del_div').appendChild(_p);
						ar = scr_records[selected_class.id].split(",");
						for(i = 0; i < ar.length; i++){
							_a = document.createElement("A");
							_a.setAttribute("class", "del_link");
							_a.setAttribute("href", "../shared/no_js.php");
							_a.setAttribute("id", "del_"+ar[i]);
							_a.appendChild(document.createTextNode(mat[ar[i]]));
							$('cl_del_div').appendChild(_a);
							$('cl_del_div').appendChild(document.createElement("BR"));
						}
						$('cl_del_div').observe("mouseleave", function(event){
							event.preventDefault();
					        $('cl_del_div').hide();
					    });
						$$('.del_link').invoke("observe", "click", function(event){
							event.preventDefault();
							var strs = this.id.split("_");
							del_subject(strs[1]);
						});
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};

document.observe("dom:loaded", function(){
	$('reins').observe("click", function(event){
		event.preventDefault();
		assignment_marks('reinsert');
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
	$('cl_rei').observe("click", function(event){
		event.preventDefault();
		need_class = true;
		assignment_marks("cl_reinsert");
    });
	$('cl_add').observe("click", function(event){
		event.preventDefault();
		need_class = true;
		populate_div(event);
		show_div(event, 'cl_add_div');
    });
	$('cl_sub').observe("click", function(event){
		event.preventDefault();
		need_class = true;
		populate_div(event);
		show_div(event, 'cl_del_div');
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
		<?php include "scr_menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Gestione tabella scrutini - <?php echo $_REQUEST['quadrimestre'] ?> quadrimestre</div>
        <table style="width: 90%; margin: 20px auto 0 auto; border-collapse: collapse">
            <tr>
            	<td colspan="3">
            		<div style="width: 100%; text-align: center; border-radius: 8px; background-color: rgba(211, 222, 199, 0.7); border: 1px solid rgba(211, 222, 199, 1); padding: 5px">
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
            	$scr_str = "Nessun record presente";
            	if (count($cl['scr']) > 0) {
	            	foreach ($cl['scr'] as $c) {
	            		$mt[] = $materie[$c];
	            	}
	            	$scr_str = join(", ", $mt);
            	}
            	
            ?>
            <tr class="admin_row">
	            <td style="width:  25%; font-weight: bold"><?php echo $cl['anno_corso'],$cl['sezione'] ?><span style="font-weight: normal"> <?php echo " (",$cl['nome'],")" ?></span></td>
	            <td style="width: 70%"><?php echo $scr_str ?></td>
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
                	<a href="index.php" class="standard_link nav_link_last">Torna menu</a>
                </td>
            </tr>
            <tr class="admin_void">
                <td colspan="3"></td>
            </tr>
        </table>
    </div>
    <div id="del_div" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #AAAAAA; border-radius: 8px 8px 8px 8px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888">
    	<p style="text-align: center; padding: 2px 0 2px 0; width: 100%; font-weight: bold; margin-bottom: 8px; border-bottom: 1px solid rgba(231, 231, 231, 0.9); background-color: rgba(231, 231, 231, 0.4)">Elimina una materia</p>
    <?php
    reset($materie);
    foreach ($materie_scr as $idm => $mat) {
    ?>
    	<a href="../shared/no_js.php" class="del_link" id="del_<?php echo $idm ?>"><?php echo truncateString($mat, 25) ?></a><br />
    <?php
    }
    ?>
    </div>
    <div id="add_div" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #AAAAAA; border-radius: 8px 8px 8px 8px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888">
    	<p style="text-align: center; padding: 2px 0 2px 0; width: 100%; font-weight: bold; margin-bottom: 8px; border-bottom: 1px solid rgba(231, 231, 231, 0.9); background-color: rgba(231, 231, 231, 0.4)">Aggiungi una materia</p>
    <?php
    foreach ($materie_no_scr as $mnc) {
    ?>
    	<a href="../shared/no_js.php" class="add_link" id="add_<?php echo $mnc ?>"><?php echo $materie[$mnc] ?></a><br />
    <?php
    }
    ?>
    </div>
    <div id="cl_add_div" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #AAAAAA; border-radius: 8px 8px 8px 8px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888"></div>
    <div id="cl_del_div" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #AAAAAA; border-radius: 8px 8px 8px 8px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888"></div>
    <div id="menu_div" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border: 1px solid #AAAAAA; border-radius: 8px 8px 8px 8px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888">
    	<p id="menu_label" style="text-align: center; padding: 2px 0 2px 0; width: 100%; font-weight: bold; margin-bottom: 8px; border-bottom: 1px solid rgba(231, 231, 231, 0.9); background-color: rgba(231, 231, 231, 0.4)"></p>
    	<a href="../shared/no_js.php" id="cl_rei" style="padding-left: 10px;">Reinserisci tutto</a><br />
    	<a href="../shared/no_js.php" id="cl_add" style="padding-left: 10px;">Aggiungi una materia</a><br />
    	<a href="../shared/no_js.php" id="cl_sub" style="padding-left: 10px;">Elimina una materia</a><br />
    	<!-- 
    	<a href="../shared/no_js.php" id="cl_inv" style="padding-left: 10px;">Alunni non validati</a><br />
    	<a href="../shared/no_js.php" id="cl_ver" style="padding-left: 10px;">Verifica i dati</a>
    	 -->
    </div>
	<p class="spacer"></p>
	</div>
<?php include "footer.php" ?>
</body>
</html>
