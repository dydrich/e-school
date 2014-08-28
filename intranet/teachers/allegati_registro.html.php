<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area docenti</title>
<link rel="stylesheet" href="../../css/reg.css" type="text/css" media="screen,projection" />
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
var all = 0;
var ff = "";

var tempX = 0;
var tempY = 0;

var show_menu = function(e, _all, _ff){
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
    all = _all;
    ff = _ff;
    return false;
};

var download_file = function(){
	<?php if (isset($_GET['sub'])): ?>
	document.location.href = "../../modules/documents/download_manager.php?doc=teacherbook_att&area=teachers&f="+ff+"&cls=<?php echo $_GET['cls'] ?>&sub=<?php echo $_GET['sub'] ?>";
	<?php else: ?>
	document.location.href = "../../modules/documents/download_manager.php?doc=teacherbook_att&area=teachers&f="+ff+"&cls=<?php echo $_GET['cls'] ?>&std=<?php echo $_REQUEST['std'] ?>";
	<?php endif; ?>
	$('context_menu').style.display = "none";
};

var delete_file = function(){
	$('context_menu').style.display = "none";
	var req = new Ajax.Request('../../modules/documents/document_manager.php',
			  {
			    	method:'post',
			    	parameters: {cls: <?php echo $_GET['cls'] ?>, sub: <?php echo $_GET['sub'] ?>, std: <?php echo $_GET['std'] ?>, doc_type: 'teacherbook_att', action: 2, id: all, f: ff},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split("|");
			      		if (dati[0] == "ok"){
							_alert ("File cancellato");
			      		}
			      		$("att_"+all).style.display = "none";			      		
			    	},
			    	onFailure: function(){ _alert("Si e' verificato un errore..."); return; }
			  });
};

var loaded = false;
function loading(time){
	openInfoDialog("Attendere il caricamento del file...", time);
}

document.observe("dom:loaded", function(){
	$$('a.clog').invoke("observe", "click", function(event){
		//alert(this.id);
		event.preventDefault();
		var strs = this.id.split("_");
		createLog(strs[1], strs[2]);
	});
	$$('a.dlog').invoke("observe", "click", function(event){
		//alert(this.id);
		event.preventDefault();
		var strs = this.id.split("_");
		downloadLog(strs[1], strs[2]);
	});
});

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
		<div id="welcome">
			<p id="w_head">Registro di <?php echo $desc ?>, classe <?php echo $desc_cls ?></p>
			<div id="att_container">
			<?php 
			if (count($allegati) < 1){
			?>
			Nessun allegato presente.
			<?php
			}
			else{
				foreach ($allegati as $all){
					$ff = preg_replace("/ /", "_", $all['file']);
					$filesize = filesize($_SESSION['__config__']['document_root']."/rclasse/download/registri/".$_SESSION['__current_year__']->get_descrizione()."/{$school_order_directory}/docenti/{$user_directory}/".$ff);
					if($filesize < 1024)
						$filesize .= "B";
					else{
						$filesize /= 1024;
						$filesize = round($filesize, 0);
						$filesize .= "K";
					}
			?>
				<p id="att_<?php echo $all['id'] ?>"><a href="#" style="text-decoration: none" onclick="show_menu(event, <?php echo $all['id'] ?>, '<?php echo $ff ?>')"><?php echo $all['file'] ?> (<?php echo $filesize ?>)</a></p>
			<?php
				}
			}
			?>
			</div>
			<div style="width: 450px; margin-top: 20px">
				<iframe src="../../modules/documents/upload_manager.php?upl_type=teacherbook_att&tipo=null" style="border: none; width: 75%;  margin: 0px; height: 55px" id="aframe"></iframe>
			</div>
		</div>
	</div>
	<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<!-- menu contestuale -->
    <div id="context_menu" style="position: absolute; width: 210px; height: 50px; display: none; ">
    	<a style="font-weight: normal; text-decoration: none" href="#" onclick="download_file()">Scarica file</a><br />
    	<a style="font-weight: normal; text-decoration: none" href="#" onclick="delete_file()">Elimina file</a><br />
    </div>
<!-- fine menu contestuale -->
</body>
</html>
