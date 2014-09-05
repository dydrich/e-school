<!DOCTYPE html>
<html>
<head>
<title>Registro di classe</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../../../css/site_themes/blue_red/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link href="../../../css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="../../../css/themes/alphacube.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../../js/prototype.js"></script>
<script type="text/javascript" src="../../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript" src="../../../js/window.js"></script>
<script type="text/javascript" src="../../../js/window_effects.js"></script>
<script type="text/javascript">
function go_to(){
	var dt = $F('date_to');
	ar = dt.split("/");
	nw_dt = ar[2]+"-"+ar[1]+"-"+ar[0];
	document.location.href = "registro_classe.php?data="+nw_dt;
}

function nbutton(){
	//$('dt').innerHTML = "Vai al "+$F('date_to');
	//$('dt').setAttribute('onclick', 'go_to()');
	$('delete').show();
}

function reset(){
	$('dt').update("Giorno...");
	$('delete').hide();
}

function show_note(element){ 
	element.getElementsByTagName("span")[0].style.display = "block";
}

var hide_note = function(element){
	element.getElementsByTagName("span")[0].style.display = "none";
};

var downloadCB = function(){
	dis = $("dlog").readAttribute("data_disabled");
	dis = 0;
	if (dis == 1){
		_alert("Registro non disponibile: crealo prima di scaricarlo");
		return false; 
	}
	else {
		file = "registro_<?php echo $_SESSION['__current_year__']->get_ID() ?>_<?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?>";
		document.location.href = "../../../modules/documents/download_manager.php?doc=classbook&f="+file;
	}
};

var createCB = function(){
	loading(20);
	var req = new Ajax.Request('print_classbook.php',
			  {
			    	method:'post',
			    	parameters: {cls: <?php echo $_SESSION['__classe__']->get_ID() ?>},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split("|");
			      		if (dati[0] == "ok"){
			      			timeout = 0;
			      		}
			      		else if (dati[0] == "no_permission"){
							document.location.href = "../no_permission.php";
			      		}
			      		$("dlog").setAttribute("data_disabled", 0);
			      		
			    	},
			    	onFailure: function(){ _alert("Si e' verificato un errore imprevisto..."); return; }
			  });
};

var loaded = false;
function loading(time){
	openInfoDialog("Attendere la creazione del registro...", time);
}
</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "navigation.php" ?>
<div id="main" style="clear: both; ">
<div id="right_col">
<?php include "../working.php" ?>
</div>
<div id="left_col">
<?php 
if($summer){
	//siamo nelle vacanze estive
	$_SESSION['no_file'] = array("referer" => "intranet/teachers/registro_classe/registro_classe.php?data=".date("Y-m-d")."&cls=".$_REQUEST['cls'], "path" => "intranet/teachers/", "relative" => "documenti/documenti.php");
	setlocale(LC_TIME, "it_IT.utf8");
	$giorno_str = strftime("%A", strtotime($today)) ." ". format_date($fine_lezioni, SQL_DATE_STYLE, IT_DATE_STYLE, "-");
?>
	<div id="welcome" style="margin-top: 30px">
		<p id="w_head">Classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?>: registro non disponibile</p>
		<p class="w_text" style="">Le lezioni sono terminate <?php echo $giorno_str ?>. Puoi ancora accedere al registro di classe, utilizzando i link seguenti:</p>
		<p class="w_text">
			<span style="padding-left: 5px; ">&middot;</span>
			<a href="registro_classe.php?data=<?php echo format_date($fine_lezioni, IT_DATE_STYLE, SQL_DATE_STYLE, "-") ?>">Vai al registro dell'ultimo giorno di scuola</a><br /><br />
			<span style="padding-left: 5px">&middot;</span>
			<a href="stats.php">Visualizza le statistiche</a>
		</p>
	</div>
	<?php
	if (!$_SESSION['__user__']->isSupplyTeacher()) {
	?>
	<div id="welcome" style="margin-top: 30px">
		<p id="w_head">Registro PDF</p>
		<p class="w_text" style="">Da qui puoi gestire la creazione e il download del registro in formato PDF.</p>
		<p class="w_text">
			<span style="padding-left: 5px; ">&middot;</span>
			<a href="#" onclick="createCB()" id="dlog">Crea (o ricrea) il PDF del registro elettronico</a><br /><br />
			<span style="padding-left: 5px">&middot;</span>
			<a href="#" onclick="downloadCB()">Scarica il PDF del registro elettronico</a>
		</p>		
	</div>
	<?php
	}
	?>
	<div id="schedule">
		<p id="s_head">Buone vacanze!</p>
	</div>
<?php 
}
else if($today < format_date($inizio_lezioni, IT_DATE_STYLE, SQL_DATE_STYLE, "-")){
	// le lezioni non sono ancora iniziate
	setlocale(LC_TIME, "it_IT.utf8");
	$giorno_str = strftime("%A", strtotime($inizio_lezioni)) ." ". format_date($inizio_lezioni, SQL_DATE_STYLE, IT_DATE_STYLE, "/");
?>
	<div id="welcome" style="margin-top: 30px">
		<p id="w_head">Registro non disponibile</p>
		<p class="w_text" style="">Le lezioni inizieranno <?php echo $giorno_str ?>. </p>		
	</div>
<?php 
}
else{
?>
	<div id="welcome" style="margin-top: 30px">
		<p id="w_head">Registro non disponibile</p>
		<p class="w_text" style="">In questo giorno non sono previste lezioni. Puoi accedere al registro di classe dai seguenti link:</p>
		<p class="w_text">
			<span style="padding-left: 5px; ">&middot;</span>
			<a href="registro_classe.php?data=<?php echo $check_day ?>">Vai al registro dell'ultimo giorno di scuola</a><br /><br />
			<span style="padding-left: 5px">&middot;</span>
			<a href="stats.php">Visualizza le statistiche</a>
		</p>		
	</div>
<?php 
}
?>
</div>
<p class="spacer"></p>
</div>
<?php include "../footer.php" ?>
</body>
</html>
