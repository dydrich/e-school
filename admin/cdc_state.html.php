<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Gestione record CDC</title>
<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../js/page.js"></script>
<script type="text/javascript">
var IE = document.all?true:false;
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

var need_class = false;
var selected_class = {};
selected_class.id = 0;
selected_class.desc = "";

var populate_div = function(event){
	var url = "get_add_subjects.php";

	$.ajax({
		type: "POST",
		url: url,
		data: {cls: selected_class.id, source: "cdc", school_order: <?php echo $_GET['school_order'] ?>},
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
				$('#cl_add_div').html("");
				$("<p id='menu_label'>Aggiungi una materia</p>").appendTo($('#cl_add_div'));

				for(i in links){
					dt = links[i];
					$("<a href='../shared/no_js.php' id='add_"+dt.id_materia+" class='add_link''>"+dt.materia+"</a><br />").appendTo($('#cl_add_div'));
				}
				$('#cl_add_div').mouseleave(function(event){
					event.preventDefault();
					$('#cl_add_div').hide();
				});
				$('.add_link').click(function(event){
					event.preventDefault();
					var strs = this.id.split("_");
					add_subject(strs[1]);
				});
				show_div(event, 'cl_add_div');
		}
	});
};

$(function(){
	$('#reins').click(function(event){
		event.preventDefault();
		crea_cdc('reinsert');
	});
	$('#del_sub').mouseover(function(event){
		event.preventDefault();
		need_class = false;
		show_div(event, 'del_div');
	});
	$('#add_sub').mouseover(function(event){
		event.preventDefault();
		need_class = false;
		show_div(event, 'add_div');
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
		var strs = this.id.split("_");
		selected_class.id = strs[1];
		selected_class.desc = strs[2];
		$('#menu_label').text("Classe "+selected_class.desc);
		show_div(event, 'menu_div');
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
		populate_div(event);
    });
	$('#cl_sub').click(function(event){
		event.preventDefault();
		need_class = true;
		show_div(event, 'del_div');
    });
});
</script>
<style>

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
