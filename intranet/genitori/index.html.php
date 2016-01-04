<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area genitori</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
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
			<?php if ($has_report) : ?>
			$('#ins').on('click', function(event) {
				event.preventDefault();
				create_report();
			});
			<?php endif; ?>
		});

		<?php if ($has_report) : ?>
		var create_report = function () {
			var st = <?php echo $_SESSION['__current_son__'] ?>;
			var month = <?php echo $has_report ?>;
			document.location.href = "../../shared/get_monthly_report.php?st="+st+"&m="+month;
		};
		<?php endif; ?>

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

		function ticker(){
			$('#ticker li:first').slideUp(
				function () {
					$(this).appendTo($('#ticker')).slideDown(); }
			);
		}
		setInterval(function(){ ticker () }, 3000);
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
		<?php if (count($avvisi) > 0): ?>
		<div class="welcome" style="padding-bottom: 25px;">
			<p class="beware">
				<i class="fa fa-info-circle main_700 _bold"></i>
				<span>Avvisi importanti</span>
			</p>
			<p class="w_text attention" style="">
				<?php
				foreach ($avvisi as $avviso) {
					if ($avviso[0] == "warning") {
				?>
				<i class="fa fa-warning _bold"></i>
				<?php
					}
					else {
				?>
				<i class="fa fa-exclamation-circle"></i>
				<?php
					}
					if ($avviso[2] == "ins") {
				?>
					<a href="#" style="margin-left: 15px" id="ins" class="attention"><?php echo $avviso[1] ?></a>
				<?php
					}
					else {
				?>
				<span style="margin-left: 15px" <?php if($avviso[2] != "") echo 'id="'.$avviso[2].'"'; ?>><?php echo $avviso[1] ?></span>
				<?php
					}
				}
				?>
			</p>
		</div>
		<?php endif; ?>
		<div class="welcome">
			<p id="w_head">Ultime attivit&agrave;</p>
			<div id="ticker_container" style="height: <?php echo $ticker_height ?>px">
				<ul id="ticker" class="ticker">
					<?php
					setlocale(LC_TIME, "it_IT.utf8");
					if(count($activities) > 0){
						$x = 0;
						foreach ($activities as $date => $act) {
							$giorno_str = strftime("%a %d/%m/%Y", strtotime($date));
							foreach ($act as $ac) {
								$class = "normal";
								if($ac['tipo'] == "Ritardo") {
									$class = "main_700";
								}
								else if ($ac['tipo'] == "Assenza") {
									$class = "main_700 _bold";
									$ac['tipo'] = "";
								}
								else if ($ac['tipo'] == "Voto" && $ac['voto'] < 6) {
									$class = "attention";
								}
								else if ($ac['tipo'] == "Nota disciplinare") {
									$class = "attention _bold";
									$ac['tipo'] = "";
								}
								else if ($ac['tipo'] == "Nota didattica") {
									if ($ac['idnota'] != 3) {
										$class = "attention _italic";
									}
									else {
										$class = "normal _italic";
									}
									$ac['tipo'] = "";
								}
								?>
								<li class="<?php echo $class ?>" style="font-size: 1.08em">
									<span style="font-family: monospace"><?php echo $giorno_str ?></span>
									<span style="margin-left: 20px"><?php echo $ac['tipo'].$ac['descrizione'] ?></span>
								</li>
								<?php
								$x++;
							}
						}
					}
					else {
						echo "<span>Nessuna attivit&agrave; recente</span>";
						$more_space = true;
					}
					?>
				</ul>
			</div>
		</div>
		<?php if ($student && $student->isActive()): ?>
		<div class="welcome" style="margin-top: 15px;">
			<p id="w_head">Riepiloghi</p>
			<p>
				<a href="riepilogo_registro.php?q=0" class="material_link">Assenze e ritardi</a>
			</p>
			<p>
				<a href="voti.php" class="material_link">Voti e note didattiche</a>
			</p>
			<p>
				<a href="riepilogo_note.php?q=0" class="material_link">Note disciplinari</a>
			</p>
		</div>
		<?php if(is_installed("com")) include $_SESSION['__path_to_root__'].'modules/communication/w_msg.php'; ?>
		<?php endif; ?>
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
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/communication/load_module.php?module=com&area=genitori"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=genitori"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<?php
if (count($_SESSION['__sons__']) > 1){
	$height = 36 * (count($_SESSION['__sons__']));
?>
<div id="other_drawer" class="drawer" style="height: <?php echo  $height ?>px; display: none; position: absolute">
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
</body>
</html>
