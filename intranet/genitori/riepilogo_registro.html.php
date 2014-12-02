<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area genitori</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var IE = document.all?true:false;
		var stid = 0;

		var tempX = 0;
		var tempY = 0;

		function show_menu(e){
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
			$('#context_menu').css({top: parseInt(tempY)+"px"});
			//alert(hid.style.top);
			$('#context_menu').css({left: parseInt(tempX)+"px"});
			$('#context_menu').show(300);
		    return false;
		}

		var dettaglio_assenze = function(f_id, stid, q){
			$('#context_menu').hide();
			if (f_id == 0) {
				$('#iframe').attr("src", "../teachers/registro_classe/elenco_assenze.php?alunno=<?php print($_SESSION['__current_son__']) ?>");
				lab_title = "Elenco assenze";
			}
			else {
				$('#iframe').attr("src", "../teachers/registro_classe/dettaglio_rit_uscite.php?alunno=<?php print($_SESSION['__current_son__']) ?>&del=1&q="+q);
				lab_title = "Ritardi";
			}
			$('#abs_pop').dialog({
				autoOpen: true,
				show: {
					effect: "appear",
					duration: 500
				},
				hide: {
					effect: "slide",
					duration: 300
				},
				modal: true,
				width: 450,
				title: lab_title,
				open: function(event, ui){

				}
			});
			//var w = new Window({className: "mac_os_x",  width:400, zIndex: 100, resizable: true, title: "Elenco assenze", url: "elenco_assenze.php?alunno="+stid, showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
			//w.showCenter(true);
		}

		var dialogclose = function(){
			$('#abs_pop').dialog("close");
		};

		var _show = function(e, off) {
			if ($('#other_drawer').is(":visible")) {
				$('#other_drawer').hide('slide', 300);
				return;
			}
			var offset = $('#drawer').offset();
			var top = off.top;

			var left = offset.left + $('#drawer').width() + 1;
			$('#other_drawer').css({top: top+"px", left: left+"px", zIndex: 1000});
			$('#other_drawer').show('slide', 300);
			return true;
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('.card_title').click(function(){
				$(this).siblings('div.card_varcontent').toggle(500);
			});
			$('.card_title').css({
				cursor: "pointer"
			});
			$('#det_abs').click(function(event){
				event.preventDefault();
				dettaglio_assenze(0, 0);
			});
			$('#rit').click(function(event){
				event.preventDefault();
				dettaglio_assenze(1, <?php echo $q ?>);
			});
			$('#context_menu').mouseleave(function(event){
				$('#context_menu').hide();
			});
			$('#lnk').click(function(event){
				event.preventDefault();
				show_menu(event);
			});
			$('#overlay').click(function(event) {
				if ($('#overlay').is(':visible')) {
					show_drawer(event);
				}
				$('#other_drawer').hide();
			});
			$('#showsub').click(function(event){
				var off = $(this).parent().offset();
				_show(event, off);
			});
		});

	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "class_working.php" ?>
</div>
<div id="left_col">
<?php 
$limite_giorni = floor($totale['giorni'] / 4);
$secondi = $totale['ore']%60;
$tot_min = ($totale['ore'] - $secondi) / 60;
list($ore, $minuti) = explode(":", minutes2hours($tot_min, ""));
$tot_ore = "$ore:$minuti";
list($ore2, $minuti2, $secondi2) = explode(":", $totale['limite_ore']);
$limite_ore = $ore2.":".$minuti2;
$idx = 0;
$assenze = $res_assenze->num_rows;
$perc_assenze = "--";
if($totale['giorni'] > 0){
	$perc_assenze = round((($assenze / $totale['giorni']) * 100), 2);
}

/**
 * calcolo della percentuale oraria di assenze 
*/
// numero totale di ore di lezione (in secondi)
$tot_hours = $totale['ore'];
// ore di assenza (in secondi)
$abs_hours = $al['ore_assenza'];
$perc_hours = "--";
if($tot_hours > 0){
	$perc_hours = round((($abs_hours / $tot_hours) * 100), 2);
}
// formattazione ore assenza
if ($abs_hours > 0) {
	$abs_sec = $abs_hours%60;
	$t_m = $abs_hours - $abs_sec;
	$t_m /= 60;
	$ore_assenza = minutes2hours($t_m, "-");
}
else {
	$ore_assenza = 0;
}
$danger = false;
if ($ore_assenza >= $limite_ore) {
	$danger = true;
}
setlocale(LC_TIME, "it_IT.utf8");
?>
	<div class="card_container">
		<div class="card">
			<div class="card_title<?php if ($danger) echo " attention" ?>">
				<?php echo $assenze ?> giorni su <?php echo $totale['giorni'] ?> (<?php echo $perc_assenze ?>%)
				<div style="float: right; margin-right: 20px">
					Limite: <?php print $limite_giorni ?>
				</div>
			</div>
			<div class="card_varcontent" style="display: none">
				<p style="line-height: 18px; margin: 0; color: #1E4389; position: relative; font-weight: bold; border-bottom: 1px solid #1E4389">Assenze</p>
				<?php
				while ($row = $res_assenze->fetch_assoc()) {
					$day = strftime("%A %d %B", strtotime($row['data']));
					?>
					<p style="line-height: 18px; margin: 0; color: #1E4389"><?php echo $day ?></p>
				<?php
				}
				?>
			</div>
		</div>
	</div>
	<div class="card_container">
		<div class="card">
			<div class="card_title<?php if ($danger) echo " attention" ?>">
				<?php echo $ore_assenza ?> ore su <?php echo $tot_ore ?> (<?php echo $perc_hours ?>%)
				<div style="float: right; margin-right: 20px">
					Limite: <?php print $limite_ore ?>
				</div>
			</div>
			<div class="card_varcontent" style="display: none; overflow: hidden">
				<div style="float: left; width: 48%; position: relative">
					<p style="line-height: 18px; margin: 0; color: #1E4389; position: relative; font-weight: bold; border-bottom: 1px solid #1E4389">Ritardi</p>
					<?php
					while ($row = $res_ritardi->fetch_assoc()) {
						$day = strftime("%A %d %B", strtotime($row['data']));
						?>
						<p style="line-height: 18px; margin: 0; color: #1E4389; position: relative"><?php echo $day ?>: ore <?php echo substr($row['ingresso'], 0, 5) ?></p>
					<?php
					}
					?>
				</div>
				<div style="float: right; width: 48%; position: relative">
					<p style="line-height: 18px; margin: 0; color: #1E4389; position: relative; font-weight: bold; border-bottom: 1px solid #1E4389">Uscite anticipate</p>
					<?php
					while ($row = $res_uscite->fetch_assoc()) {
						$day = strftime("%A %d %B", strtotime($row['data']));
						?>
						<p style="line-height: 18px; margin: 0; color: #1E4389; position:relative;"><?php echo $day ?>: ore <?php echo substr($row['uscita'], 0, 5) ?></p>
					<?php
					}
					?>
				</div>
				<p style="clear: both; height: 0"></p>
			</div>
		</div>
	</div>
	<div class="navigate" style="">
		<a href="riepilogo_registro.php?q=1" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 0" src="../../images/24.png" /><span style="top: -3px">1 Quadrimestre</span>
		</a>
		<a href="riepilogo_registro.php?q=2" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
			<img style="margin-right: 5px; position: relative; top: 0" src="../../images/24.png" /><span style="top: -3px">2 Quadrimestre</span>
		</a>
		<a href="riepilogo_registro.php?q=0" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 0" src="../../images/24.png" /><span style="top: -3px">Totale</a></span>
		</a>
	</div>
</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<?php if (count($_SESSION['__sons__']) > 1): ?>
		<div class="drawer_link separator">
			<a href="#" id="showsub"><img src="../../images/69.png" style="margin-right: 10px; position: relative; top: 5%"/>Seleziona alunno</a>
		</div>
		<?php endif; ?>
		<div class="drawer_link separator"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>riepilogo_note.php?q=0"><img src="../../images/12.png" style="margin-right: 10px; position: relative; top: 5%" />Riepilogo note</a></div>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/communication/load_module.php?module=com&area=genitori"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<?php if (count($_SESSION['__sons__']) > 1){
	$height = 36 * (count($_SESSION['__sons__']));
?>
	<div id="other_drawer" class="drawer" style="height: <?php echo $height ?>px; display: none; position: absolute">
		<?php
		$indice = 1;
		reset($_SESSION['__sons__']);
		while(list($key, $val) = each($_SESSION['__sons__'])){
			$cl = "";
			if ($key == $_SESSION['__current_son__']) {
				$cl = " _bold";
			}
			?>
			<div class="drawer_link">
				<a href="<?php print $page ?>?son=<?php print $key ?>" clas="<?php echo $cl ?>"><?php print $val[0] ?></a>
			</div>
		<?php
		}
		?>
	</div>
<?php
}
?>
<div id="abs_pop" style="display: none">
	<iframe id="iframe" src="../teachers/registro_classe/elenco_assenze.php" style="width: 100%; height: 450px; margin: 0 auto; padding: 0"></iframe>
</div>
</body>
</html>
