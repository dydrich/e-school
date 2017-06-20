<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Registro di classe</title>
	<link rel="stylesheet" href="../../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">
	var go_to = function() {
		var dt = $('#date_to').val();
		ar = dt.split("/");
		nw_dt = ar[2]+"-"+ar[1]+"-"+ar[0];
		document.location.href = "registro_classe.php?data="+nw_dt;
	};

	var nbutton = function() {
		$('#delete').show();
	};

	var reset = function() {
		$('#dt').text("Giorno...");
		$('#delete').hide();
	};

	var show_note = function(element){
		element.getElementsByTagName("span")[0].style.display = "block";
	};

	var hide_note = function(element){
		element.getElementsByTagName("span")[0].style.display = "none";
	};

	var downloadCB = function(){
		dis = $("#dlog").attr("data_disabled");
		dis = 0;
		if (dis == 1){
			j_alert("error", "Registro non disponibile: crealo prima di scaricarlo");
			return false;
		}
		else {
			file = "registro_<?php echo $_SESSION['__current_year__']->get_descrizione() ?>_<?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?>";
			document.location.href = "../../../modules/documents/download_manager.php?doc=classbook&f="+file+"&sc=<?php echo $_SESSION['__classe__']->getSchoolOrder() ?>";
		}
	};

	var createCB = function(){
		background_process("Attendere la creazione del registro...", 20, false);
		$.ajax({
			type: "POST",
			url: 'print_classbook.php',
			data: {cls: <?php echo $_SESSION['__classe__']->get_ID() ?>},
			dataType: 'json',
			error: function() {
				timeout = 0;
				j_alert("error", "Errore di trasmissione dei dati");
			},
			succes: function() {

			},
			complete: function(data){
				r = data.responseText;
				if(r == "null"){
					return false;
				}
				timeout = 0;
				var json = $.parseJSON(r);
				if (json.status == "kosql"){
					j_alert("error", json.message);
					console.log(json.dbg_message);
				}
				else if(json.status == "no_permission") {
					document.location.href = "../no_permission.php";
				}
				else {
					$("#dlog").attr("data_disabled", 0);
					loaded("Operazione completata. Ora puoi scaricare il registro");
				}
			}
		});
	};

	$(function () {
		load_jalert();
		setOverlayEvent();
	});

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
	$giorno_str = strftime("%A", strtotime($today)) ." ". format_date($last_day, SQL_DATE_STYLE, IT_DATE_STYLE, "-");
?>
	<div class="welcome">
		<p id="w_head">Classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?>: registro non disponibile</p>
		<p class="w_text" style="">Le lezioni sono terminate <?php echo $giorno_str ?>. Puoi ancora accedere al registro di classe, utilizzando i link seguenti:</p>
		<p class="w_text">
			<span style="padding-left: 5px; ">&middot;</span>
			<a href="registro_classe.php?data=<?php echo format_date($last_day, IT_DATE_STYLE, SQL_DATE_STYLE, "-") ?>">Vai al registro dell'ultimo giorno di scuola</a><br /><br />
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
	<div class="welcome">
		<p id="w_head">Registro non disponibile</p>
		<p class="w_text" style="">Le lezioni inizieranno <?php echo $giorno_str ?>. </p>		
	</div>
<?php 
}
else{
?>
	<div class="welcome">
		<p id="w_head">Registro non disponibile</p>
		<p class="w_text" style="">In questo giorno non sono previste lezioni. Puoi accedere al registro di classe dai seguenti link:</p>
		<p class="w_text">
			<span style="padding-left: 5px; ">&middot;</span>
			<a href="registro_classe.php?data=<?php echo $check_day ?>">Vai al registro dell'ultimo giorno di scuola</a><br /><br />
			<span style="padding-left: 5px; ">&middot;</span>
			<a href="riepilogo_registro_classe.php?data=<?php echo $check_day ?>">Vai al riepilogo della settimana</a><br /><br />
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
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_label"><span>Classe <?php echo $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></span></div>
		<div class="drawer_link submenu">
			<a href="stats.php"><img src="../../../images/18.png" style="margin-right: 10px; position: relative; top: 5%"/>Statistiche</a>
		</div>
		<div class="drawer_link submenu separator">
			<a href="notes.php"><img src="../../../images/26.png" style="margin-right: 10px; position: relative; top: 5%"/>Note</a>
		</div>
		<div class="drawer_link submenu"><a href="../registro_personale/index.php"><img src="../../../images/4.png" style="margin-right: 10px; position: relative; top: 5%" />Registro personale</a></div>
		<div class="drawer_link submenu separator"><a href="../gestione_classe/classe.php"><img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />Gestione classe</a></div>
		<div class="drawer_link"><a href="../index.php"><img src="../../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="../profile.php"><img src="../../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../../modules/documents/load_module.php?module=docs&area=teachers"><img src="../../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=teachers"><img src="<?php echo $_SESSION['__path_to_root__'] ?>images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../../shared/do_logout.php"><img src="../../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
