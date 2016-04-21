<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area studenti</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
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
		});

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
setlocale(LC_ALL, "it_IT.utf8");

if($res_note->num_rows < 1){
?>
	<div style="width: 90%; margin: auto; font-weight: bold; text-align: center; height: 75px; padding-top: 30px; font-size: 1.2em">Nessuna nota presente</div>
<?php 	
}
else{
?>
	<div class="card_container">
<?php
	while($row = $res_note->fetch_assoc()){
		$giorno_str = strftime("%A %d %B %Y", strtotime($row['data']));
?>
		<div class="card">
			<div class="card_title">
				<?php echo $row['tipo_nota'] ?><?php if($row['id_tiponota'] > 1) echo ""; else echo " - ".$row['cognome']." ".$row['nome'] ?>
				<div style="float: right; margin-right: 20px; width: 180px">
					<?php echo $giorno_str ?>
				</div>
			</div>
			<div class="card_minicontent">
				<?php echo $row['descrizione'] ?>
			</div>
		</div>
<?php
	}
?>
	</div>
<?php
}
?>
	<div class="navigate">
		<a href="riepilogo_note.php?q=1" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 0" src="../../images/24.png" /><span style="top: -3px">1 Quadrimestre</span>
		</a>
		<a href="riepilogo_note.php?q=2" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px; margin-left: 8px">
			<img style="margin-right: 5px; position: relative; top: 0" src="../../images/24.png" /><span style="top: -3px">2 Quadrimestre</span>
		</a>
		<a href="riepilogo_note.php?q=0" style="color: #000000; vertical-align: middle; text-transform: uppercase; text-decoration: none; margin-right: 8px;">
			<img style="margin-right: 5px; position: relative; top: 0" src="../../images/24.png" /><span style="top: -3px">Totale</span>
		</a>
	</div>
</div>
<p class="spacer"></p>
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<?php if ($area == "genitori" && count($_SESSION['__sons__']) > 1): ?>
			<div class="drawer_link separator">
				<a href="#" id="showsub"><img src="../../images/69.png" style="margin-right: 10px; position: relative; top: 5%"/>Seleziona alunno</a>
			</div>
		<?php endif; ?>
		<div class="drawer_link separator"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>riepilogo_registro.php?q=0"><img src="../../images/10.png" style="margin-right: 10px; position: relative; top: 5%" />Riepilogo assenze</a></div>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>profile.php"><img src="../../images/35.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=<?php echo $area ?>"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
		<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=alunni"><img src="<?php echo $_SESSION['__path_to_root__'] ?>images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<?php if ($area == "genitori" && count($_SESSION['__sons__']) > 1){
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
</body>
</html>
