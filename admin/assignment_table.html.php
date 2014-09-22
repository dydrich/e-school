<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Gestione record scrutini</title>
<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../js/page.js"></script>
<script type="text/javascript">
var IE = document.all?true:false;
var tempX = 0;
var tempY = 0;

var scr_records = [];
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

var mat = [];
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

	$.ajax({
		type: "POST",
		url: url,
		data: {quadrimestre: <?php echo $_REQUEST['quadrimestre'] ?>, action: action, cls: cls},
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
	var url = "popola_tabella_scrutini.php";

	$.ajax({
		type: "POST",
		url: url,
		data: {quadrimestre: <?php echo $_REQUEST['quadrimestre'] ?>, action: action, subject: subject, cls: cls},
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
	var url = "popola_tabella_scrutini.php";

	$.ajax({
		type: "POST",
		url: url,
		data: {quadrimestre: <?php echo $_REQUEST['quadrimestre'] ?>, action: action, subject: subject, cls: cls},
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
		data:  {cls: selected_class.id, source: "scr", quadrimestre: <?php echo $_REQUEST['quadrimestre'] ?> },
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
				$('#cl_add_div').html();
				$("<p style='text-align: center; padding: 2px 0 2px 0; width: 100%; font-weight: bold; margin-bottom: 8px; ' id='menu_label'>Aggiungi una materia</p>").appendTo($('#cl_add_div'));

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
			}
			/*
			 cl_del_div
			 */
			$('#cl_del_div').html();
			$("<p style='text-align: center; padding: 2px 0 2px 0; width: 100%; font-weight: bold; margin-bottom: 8px; ' id='menu_label'>Elimina una materia</p>").appendTo($('#cl_del_div'));

			ar = scr_records[selected_class.id].split(",");
			for(i = 0; i < ar.length; i++){
				$("<a href='../shared/no_js.php' id='del_"+ar[i]+"' class='del_link'></a>"+mat[ar[i]]+"<br />").appendTo($('#cl_del_div'));
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
	});
};

$(function(){
	load_jalert();
	$('#reins').click(function(event){
		event.preventDefault();
		assignment_marks('reinsert');
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
	$('#cl_rei').click(function(event){
		event.preventDefault();
		need_class = true;
		assignment_marks("cl_reinsert");
    });
	$('#cl_add').click(function(event){
		event.preventDefault();
		need_class = true;
		populate_div(event);
		show_div(event, 'cl_add_div');
    });
	$('#cl_sub').click(function(event){
		event.preventDefault();
		need_class = true;
		populate_div(event);
		show_div(event, 'cl_del_div');
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
	            <td style="width:  25%; font-weight: bold"><?php if (isset($cl)) echo $cl['anno_corso'],$cl['sezione'] ?><span style="font-weight: normal"> <?php echo " (",$cl['nome'],")" ?></span></td>
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
    	<p style="text-align: center; padding: 2px 0 2px 0; width: 100%; font-weight: bold; margin-bottom: 8px; background-color: #DB5355">Elimina una materia</p>
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
    	<p style="text-align: center; padding: 2px 0 2px 0; width: 100%; font-weight: bold; margin-bottom: 8px; background-color: #DB5355">Aggiungi una materia</p>
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
    	<p id="menu_label" style="text-align: center; padding: 2px 0 2px 0; width: 100%; font-weight: bold; margin-bottom: 8px; "></p>
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
