<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript">
var IE = document.all?true:false;
if (!IE) document.captureEvents(Event.MOUSEMOVE);
var cls = 0;
var sub = 0;

var tempX = 0;
var tempY = 0;

var show_menu = function(e, _all, _ff){
	//alert(_all);
	if (IE) { // grab the x-y pos.s if browser is IE
        tempX = event.clientX + document.body.scrollLeft;
        tempY = event.clientY + document.body.scrollTop;
    } else {  // grab the x-y pos.s if browser is NS
        tempX = e.pageX;
        tempY = e.pageY;
    }  
    // catch possible negative values in NS4
    if (tempX < 0){tempX = 0;}
    if (tempY < 0){tempY = 0;}  
    $('context_menu').style.top = parseInt(tempY)+"px";
    //alert(hid.style.top);
    $('context_menu').style.left = parseInt(tempX)+"px";
    $('context_menu').style.display = "inline";
    cls = _all;
    sub = _ff;
    return false;
};

var downloadLog = function(){
	file = "registro_<?php echo $_SESSION['__current_year__']->get_ID() ?>_<?php echo $_SESSION['__user__']->getUid(true) ?>_"+cls+"_"+sub;
	document.location.href = "../../modules/documents/download_manager.php?doc=teacherbook&area=teachers&f="+file;
	$('context_menu').style.display = "none";
};

var downloadAll = function(){
	file = "registro_<?php echo $_SESSION['__current_year__']->get_ID() ?>_<?php echo $_SESSION['__user__']->getUid(true) ?>_"+cls+"_"+sub;
	document.location.href = "../../modules/documents/download_manager.php?doc=teacherbookall&area=teachers&f="+file;
	$('context_menu').style.display = "none";
};

var attach = function(){
	document.location.href = "allegati_registro.php?cls="+cls+"&sub="+sub;
};

var createLog = function(){
	loading(20);
	var req = new Ajax.Request('print_log.php',
			  {
			    	method:'post',
			    	parameters: {cls: cls, sub: sub},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split("|");
			      		if (dati[0] == "ok"){
							$('msg_div').update("Il registro e` stato creato");
							timeout = 3;
			      		}
			    	},
			    	onFailure: function(){ _alert("Si e' verificato un errore..."); return; }
			  });
	$('context_menu').style.display = "none";
};

document.observe("dom:loaded", function(){
	$$('a.dlog').invoke("observe", "click", function(event){
		//alert(this.id);
		event.preventDefault();
		var strs = this.id.split("_");
		show_menu(event, strs[1], strs[2]);
	});
	$('context_menu').observe("mouseleave", function(event){
		event.preventDefault();
		this.hide();
	})
});

var loaded = false;
function loading(time){
	openInfoDialog("Attendere la creazione del registro...", time);
}

</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
	<?php include "profile_menu.php" ?>
	</div>
	<div id="left_col">
	<?php
	if ($ordine_scuola == 1){
		if ($_SESSION['__user__']->getSubject() == 12 || $_SESSION['__user__']->getSubject() == 9){
			foreach ($classi as $k => $classe){
				if (in_array($k, $ids)){
	?>
		<div id="welcome">
			<p id="w_head">CLASSE <?php echo $classe['name'] ?></p>
			<table style="width: 100px">
			<?php 
				foreach ($classe['subjects'] as $i => $s){
			?>
				<tr style="height: 25px">
					<td style="width: 100px"><a href="../../shared/no_js.php" class="dlog" id="log_<?php echo $k ?>_<?php echo $i ?>"><?php echo $s['mat'] ?></a></td>
				</tr>
			<?php 
				}
			?>
			</table>
		</div>
	<?php
				}
			}
		}	
		else {
	?>
		<div id="welcome">
			<p id="w_head"><?php echo strtoupper($materia) ?></p>
			<table style="width: 300px">
	<?php 
			foreach ($classi as $k => $classe){
				if (in_array($k, $ids)){
	?>
				<tr style="height: 25px">
					<td style="width: 100px"><a href="../../shared/no_js.php" class="dlog" id="log_<?php echo $k ?>_<?php echo $_SESSION['__user__']->getSubject() ?>">CLASSE <?php echo $classe['name'] ?></a></td>
				</tr>
			<?php
				}
			}
			?>
			</table>
		</div>
	<?php
		}
	}
	else if ($ordine_scuola == 2){
		foreach ($classi as $k => $classe){
			if (in_array($k, $ids)){
	?>
		<div id="welcome">
			<p id="w_head">CLASSE <?php echo $classe['name'] ?></p>
			<table style="width: 200px">
			<?php 
				foreach ($classe['subjects'] as $i => $s){
			?>
				<tr style="height: 25px">
					<td style="width: 200px"><a href="../../shared/no_js.php" class="dlog" id="log_<?php echo $k ?>_<?php echo $i ?>"><?php echo $s['mat'] ?></a></td>
				</tr>
			<?php
				}
			}
			?>
			</table>
		</div>
	<?php
		}
	}	
	?>
	</div>
	<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<!-- menu contestuale -->
    <div class="context_menu" id="context_menu" style="position: absolute; width: 210px; height: 60px; display: none; ">
    	<a style="font-weight: normal; text-decoration: none" href="#" onclick="attach()">Gestisci allegati</a><br />
    	<a style="font-weight: normal; text-decoration: none" href="#" onclick="downloadLog()">Scarica solo il registro</a><br />
    	<a style="font-weight: normal; text-decoration: none" href="#" onclick="downloadAll()">Scarica registro e allegati</a><br />
    	<a style="font-weight: normal; text-decoration: none" href="#" onclick="createLog()">Crea o ricrea il registro</a><br />
    </div>
<!-- fine menu contestuale -->
</body>
</html>
