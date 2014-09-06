<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript">
var downloadLog = function(cls, cls_desc){
	file = "registro_<?php echo $_SESSION['__current_year__']->get_ID() ?>_"+cls_desc;
	//document.location.href = "../../lib/download_manager.php?dw_type=classbook&f="+file;
	document.location.href = "../../modules/documents/download_manager.php?doc=classbook&area=manager&f="+file;
};

var createLog = function(cls){
	alert(cls);
	var req = new Ajax.Request('../teachers/registro_classe/print_classbook.php',
			  {
			    	method:'post',
			    	parameters: {cls: cls},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split("|");
			      		if (dati[0] == "ok"){
							_alert ("Registro creato correttamente");
			      		}
			      		
			    	},
			    	onFailure: function(){ _alert("Si e' verificato un errore..."); return; }
			  });
};

document.observe("dom:loaded", function(){
	$$('a.clog').invoke("observe", "click", function(event){
		//alert(this.id);
		event.preventDefault();
		var strs = this.id.split("_");
		createLog(strs[1]);
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
<?php include $_SESSION['__administration_group__']."/navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include $_SESSION['__administration_group__']."/menu.php" ?>
</div>
<div id="left_col">
	<div class="group_head">
		Area amministrazione e segreteria::<?php echo $_SESSION['__school_level__'][$_SESSION['__school_order__']] ?>
	</div>
	<div class="welcome">
		<p id="w_head">Gestione registri di classe </p>
		<table style="width: 350px">
		<?php 
		while($cls = $res_classi->fetch_assoc()){
		?>
			<tr style="height: 25px">
				<td style="width: 50px"><?php echo $cls['anno_corso'].$cls['sezione'] ?></td>
				<td style="width: 150px"><a href="../../shared/no_js.php" class="clog" id="createLog_<?php echo $cls['id_classe'] ?>_<?php echo $cls['anno_corso'].$cls['sezione'] ?>">Crea il registro</a></td>
				<td style="width: 150px"><a href="../../shared/no_js.php" class="dlog" id="downloadLog_<?php echo $cls['id_classe'] ?>_<?php echo $cls['anno_corso'].$cls['sezione'] ?>">Scarica il registro</a></td>
		<?php  
		}
		?>
		</table>	
	</div>
</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
</body>
</html>
