<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
<link href="../../css/skins/aqua/theme.css" type="text/css" rel="stylesheet"  />
<link href="../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript" src="../../js/calendar.js"></script>
<script type="text/javascript" src="../../js/lang/calendar-it.js"></script>
<script type="text/javascript" src="../../js/calendar-setup.js"></script>
<script type="text/javascript">
var timestamp;
var tm = 0;
var complete = false;
var timer;

var publish = function(q){
	quad = q;
	alert(quad);
	if(quad == 3) quad = 2;
	var url = "registra_pagelle.php";
	var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {q: quad, data: timestamp},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split("#");
			      		if(dati[0] == "kosql"){
			      			sqlalert();
							//console.log(dati[1]+"\n"+dati[2]);
							return false;
			     		}
			     		else{
				     		$('q'+q+'_text').update("Online dal "+$F('q'+q+'_val')+" alle ore "+$F('q'+q+'_h'));
				     		yellow_fade('upd_tr'+q);	
			     		}
			     		
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
};

var create_report = function(q){
	var url = "report_manager.php";
	leftS = (screen.width - 200) / 2;
	$('wait_label').setStyle({left: leftS+"px"});
	$('wait_label').setStyle({top: "300px"});
	$('wait_label').update("Operazione in corso");
	$('over1').show();
	$('wait_label').appear({duration: 0.8});
	var req = new Ajax.Request(url,
			  {
			    	method:'post',
			    	parameters: {action: "create_final_report", y: <?php echo $_SESSION['__current_year__']->get_ID() ?>},
			    	onSuccess: function(transport){
			    		complete = true;
				    	clearTimeout(timer);
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split("#");
			      		if(dati[0] == "kosql"){
			      			$('over1').hide();
			    			setTimeout("sqlalert()", 100);
							//console.log(dati[1]+"\n"+dati[2]);
							return false;
			     		}
			     		else{
			     			$('wait_label').update("Operazione conclusa");
							setTimeout("$('wait_label').fade({duration: 2.0})", 2000);
							setTimeout("$('over1').hide()", 3700);
							//setTimeout("_alert('Pagelle create')", 3800);
			     		}
			     		
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore..."); }
			  });
	upd_str();
};

var do_backup = function(year, session, area){
	//alert("doing backup for area "+ area+", session "+session+" on year "+year);
	//document.location.href = "report_manager.php?action=do_backup&q="+session+"&y="+year;
	//return;
	tm = 0;
	complete = false;
	var url = "report_manager.php";
	leftS = (screen.width - 200) / 2;
	$('wait_label').setStyle({left: leftS+"px"});
	$('wait_label').setStyle({top: "300px"});
	$('wait_label').update("Operazione in corso");
	$('over1').show();
	$('wait_label').appear({duration: 0.8});
	upd_str();
	var req = new Ajax.Request(url,
		{
			method:'post',
			parameters: {action: "do_backup", y: <?php echo $_SESSION['__current_year__']->get_ID() ?>, q: session},
			onSuccess: function(transport){
				complete = true;
				clearTimeout(timer);
				var response = transport.responseText || "no response text";
				var json = response.evalJSON();
				console.log(json.status);
				if(json.status == "kosql"){
					$('over1').hide();
					setTimeout("sqlalert()", 100);
					//console.log(dati[1]+"\n"+dati[2]);
					return false;
				}
				else{
					$('tdbck_'+session).update("<a href='../../modules/documents/download_manager.php?doc=report_backup&area=manager&f="+json.zip+"&sess="+session+"&y="+year+"&area="+area+"' style=''>Scarica il backup</a>");
					console.log(json.zip);
					$('wait_label').update(json.message);
					setTimeout("$('wait_label').fade({duration: 1.0})", 4000);
					setTimeout("$('over1').hide()", 5100);
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

var yellow_fade = function(elem){
	var trasp = 1;
	$(elem).setStyle({backgroundColor: "rgba(238, 238, 76, 1)"});
	var i = 0;
	var intv = window.setInterval(function(){trasp -= 0.1; i++; $(elem).setStyle({backgroundColor: "rgba(238, 238, 76, "+trasp+")"});}, 200);
	if(i > 10)
		window.clearInterval(intv);	
};

var split_date = function(q){
	timestamp = $F('q'+q+'_val');
	date_time = $('q'+q+'_val').value;
	date = date_time.substr(0, 10);
	time = date_time.substr(11, 5);
	$('q'+q+'_val').value = date;
	$('q'+q+'_h').value = time;
};

document.observe("dom:loaded", function(){
	<?php if($pagelle[$_SESSION['__current_year__']->get_ID()][1]['disponibili_docenti'] == "" || (isset($_REQUEST['force_modification']) && $_REQUEST['force_modification'] == 1)): ?>
	$('publisher1').observe("click", function(event){
		event.preventDefault();
		publish(1);
	});
	<?php
	 else:
	 ?>
	$$('.backup').invoke("observe", "click", function(event){
		//alert(this.id);
		var strs = this.id.split("_");
		y = strs[1];
		q = strs[2];
		event.preventDefault();
		do_backup(y, q, <?php echo $_SESSION['__school_order__'] ?>);
	});
	<?php endif; ?>
	<?php if($pagelle[$_SESSION['__current_year__']->get_ID()][2]['disponibili_docenti'] == "" || (isset($_REQUEST['force_modification']) && $_REQUEST['force_modification'] == 3)){ ?>
	$('publisher').observe("click", function(event){
		event.preventDefault();
		alert(3);
		publish(3);
	});
	<?php } ?>
	$('gen_2').observe("click", function(event){
		event.preventDefault();
		create_report(2);
	});
});	

</script>
<style>
#wait_label{
	width: 200px;
	height: 80px;
	text-align: center;
	background-color: #000000; 
	border: 1px solid #CCCCCC; 
	border-radius: 8px 8px 8px 8px;
	color: white;
	font-weight: bold;
	vertical-align: middle;
}
.index_link {#border-bottom: 1px solid #CCCCCC;}
.group_head{
	padding-top: 5px; 
	padding-bottom: 5px; 
	text-align: center; 
	font-weight: bold; 
	background-color: #E7E7E7; 
	border-radius: 5px 5px 5px 5px
}
div.overlay{
    background-image: url(../../images/overlay.png);
    position: absolute;
    top: 0px;
    left: 0px;
    z-index: 90;
    width: 100%;
    height: 100%;
}

</style>
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
		Pagelle online <?php echo $school ?>
	</div>
	<div class="welcome">
		<p id="w_head"><?php echo $_SESSION['__current_year__']->to_string() ?></p>
		<table style="width: 550px">
			<tr id="upd_tr3">
				<td style="width: 30%; font-weight: bold">Pagella finale </td>
				<td style="width: 70%" id="q3_text">
				<?php if($pagelle[$_SESSION['__current_year__']->get_ID()][2]['data_pubblicazione'] == "" || (isset($_REQUEST['force_modification']) && $_REQUEST['force_modification'] == 3)){ ?>
				<a href="#" id="sel3" style="text-decoration: none">Pubblica le pagelle il </a>
				<input type="text" onchange="split_date(3)" style="margin-left: 8px; width: 65px; border: 1px solid #DAE5CE; border-radius: 5px; font-size: 0.9em" name="q3_val" id="q3_val" />
				<label for="f_h" style="margin-left: 15px">alle ore </label>
				<input type="text" style="margin-left: 8px; width: 35px; border: 1px solid #DAE5CE; border-radius: 5px; font-size: 0.9em" name="q3_h" id="q3_h" />
				<a href="../../shared/no_js.php" id="publisher" style="margin-left: 10px">Registra</a>
				<script type="text/javascript">
		            Calendar.setup({
		                date		: new Date(),
						inputField	: "q3_val",
						ifFormat	: "%d/%m/%Y %H:%M",
						showsTime	: true,
						firstDay	: 1,
						timeFormat	: "24"				
					});
		        </script>
				<?php } 
				else{ 
					$d = format_date($pagelle[$_SESSION['__current_year__']->get_ID()][2]['data_pubblicazione'], SQL_DATE_STYLE, IT_DATE_STYLE, "/");
					$h = substr($pagelle[$_SESSION['__current_year__']->get_ID()][2]['ora_pubblicazione'], 0, 5);
				?>
				Online dal <?php echo $d ?> alle ore <?php echo $h ?> (<a href="pagelle.php?force_modification=3" id="mod">modifica</a>)
				<?php } ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding-left: 40px">
					
					<?php if($pagelle[$_SESSION['__current_year__']->get_ID()][2]['disponibili_docenti'] != "" && $pagelle[$_SESSION['__current_year__']->get_ID()][2]['disponibili_docenti'] <= date("Y-m-d")){ ?>
					<a href="../../shared/no_js.php" id="gen_2">Genera o rigenera pagelle</a><br />
					<a href="cerca_pagella.php?y=<?php echo $_SESSION['__current_year__']->get_ID() ?>&q=2">Cerca una pagella</a><br />
					<a href="" class="backup" id="backup_<?php echo $_SESSION['__current_year__']->get_ID() ?>_2">Crea il backup pagelle</a><br />
					<?php
					$folder = "scuola-secondaria";
					if ($_SESSION['__school_order__'] == 2){
						$folder = "scuola-primaria";
					}
					$year_desc = $db->executeCount("SELECT descrizione FROM rb_anni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID());
					$file_zip = $folder."-".$year_desc."-2Q.zip";

					if(file_exists($_SESSION['__config__']['html_root']."/download/pagelle/{$year_desc}/{$file_zip}")){
						$time = filemtime($_SESSION['__config__']['html_root']."/download/pagelle/{$year_desc}/{$file_zip}");
					?>
						<a href='../../modules/documents/download_manager.php?doc=report_backup&area=manager&f=<?php echo $file_zip ?>&sess=2&y=<?php echo $_SESSION['__current_year__']->get_ID() ?>&area=<?php echo $_SESSION['__school_order__'] ?>' style=''>Scarica il backup (ultima modifica <?php echo date("d/m/Y H:i:s", $time) ?>)</a>
					<?php
						}
					}
					?>
				</td>
			</tr>

			<tr>
				<td colspan="2" style="height: 20px"></td>
			</tr>
			<tr>
				<td colspan="2" style="font-weight: bold">Schede di valutazione quadrimestrali</td>
			</tr>
			<tr id="upd_tr1">
				<td style="width: 30%">Primo Quadrimestre</td>
				<td style="width: 70%" id="q1_text">
				<?php if($pagelle[$_SESSION['__current_year__']->get_ID()][1]['data_pubblicazione'] == "" || (isset($_REQUEST['force_modification']) && $_REQUEST['force_modification'] == 1)){ ?>
				<a href="#" id="sel" style="text-decoration: none">Pubblica le pagelle il </a>
				<input type="text" onchange="split_date(1)" style="margin-left: 8px; width: 65px; border: 1px solid #DAE5CE; border-radius: 5px; font-size: 0.9em" name="q1_val" id="q1_val" />
				<label for="q1_h" style="margin-left: 15px">alle ore </label>
				<input type="text" style="margin-left: 8px; width: 35px; border: 1px solid #DAE5CE; border-radius: 5px; font-size: 0.9em" name="q1_h" id="q1_h" />
				<a href="#" id="publisher1" style="margin-left: 10px">Registra</a>
				<script type="text/javascript">
		            Calendar.setup({
		                date		: new Date(),
						inputField	: "q1_val",
						ifFormat	: "%d/%m/%Y %H:%M",
						showsTime	: true,
						firstDay	: 1,
						timeFormat	: "24"				
					});
		        </script>
				<?php
				}
				else{ 
					$d = format_date($pagelle[$_SESSION['__current_year__']->get_ID()][1]['data_pubblicazione'], SQL_DATE_STYLE, IT_DATE_STYLE, "/");
					$h = substr($pagelle[$_SESSION['__current_year__']->get_ID()][1]['ora_pubblicazione'], 0, 5);
				?>
				Online dal <?php echo $d ?> alle ore <?php echo $h ?> (<a href="pagelle.php?force_modification=1" id="mod">modifica</a>)
				<?php } ?>
				</td>
			</tr>
			<?php if($pagelle[$_SESSION['__current_year__']->get_ID()][1]['disponibili_docenti'] != "" && $pagelle[$_SESSION['__current_year__']->get_ID()][1]['disponibili_docenti'] <= date("Y-m-d")): ?>
			<tr>
				<td colspan="2" style="padding-left: 40px">
					<a href="cerca_pagella.php?y=<?php echo $_SESSION['__current_year__']->get_ID() ?>&q=1">Cerca una scheda</a><br />
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding-left: 40px">
					<a href="" class="backup" id="backup_<?php echo $_SESSION['__current_year__']->get_ID() ?>_1">Crea il backup pagelle</a>
				</td>
			</tr>
			<tr>
				<td id="tdbck_1" colspan="2" style="padding-left: 40px">
					<?php
					$folder = "scuola_secondaria";
					if ($_SESSION['__school_order__'] == 2){
						$folder = "scuola_primaria";
					}
					$year_desc = $db->executeCount("SELECT descrizione FROM rb_anni WHERE id_anno = ".$_SESSION['__current_year__']->get_ID());
					$file_zip = $folder."-".$year_desc."-1Q.zip";

					if(file_exists($_SESSION['__config__']['html_root']."/tmp/{$year_desc}/1/{$folder}/{$file_zip}")){
						$time = filemtime($_SESSION['__config__']['html_root']."/tmp/{$year_desc}/1/{$folder}/{$file_zip}");
					?>
						<a href='../../modules/documents/download_manager.php?doc=report_backup&area=manager&f=<?php echo $file_zip ?>&sess=1&y=<?php echo $_SESSION['__current_year__']->get_ID() ?>&area=<?php echo $_SESSION['__school_order__'] ?>' style=''>Scarica il backup (ultima modifica <?php echo date("d/m/Y H:i:s", $time) ?>)</a>
					<?php
					}
					?>
				</td>
			</tr>
			<?php endif; ?>
			<tr>
				<td colspan="2" style="height: 20px"></td>
			</tr>
		</table>
	</div>
	<div class="welcome">
		<p id="w_head">Anni precedenti</p>
		<?php if(count($pagelle) < 2){ ?>
		<p class="w_text">Nessuna pagella presente</p>
		<?php 
		} 
		else{ 
			foreach($pagelle as $k => $pagella){
				if($k != $_SESSION['__current_year__']->get_ID()){
					$desc = $db->executeCount("SELECT descrizione FROM rb_anni WHERE id_anno = {$k}");
		?>
			<a href="cerca_pagella.php?y=<?php echo $k ?>&q=2" class="search">Anno scolastico <?php echo $desc ?></a>
		<?php
				}
			}
		} 
		?>
	</div>
</div>
<p class="spacer"></p>
</div>
<div class="overlay" id="over1" style="display: none">
    <div id="wait_label" style="position: absolute; display: none; padding-top: 25px">Operazione in corso</div>
</div>
<?php include "footer.php" ?>
</body>
</html>
