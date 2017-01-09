<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
	$(function(){
		load_jalert();
		setOverlayEvent();
		$('table tbody > tr').mouseover(function(event){
			//alert(this.id);
			var strs = this.id.split("_");
			$('#link_'+strs[1]).show();
		});
		$('table tbody > tr').mouseout(function(event){
			//alert(this.id);
			var strs = this.id.split("_");
			$('#link_'+strs[1]).hide();
		});
		$('#pdf').click(function(event){
			event.preventDefault();
			filter('pdf');
		});
		$('#xls').click(function(event){
			event.preventDefault();
			filter('xls');
		});
		$('#ods').click(function(event){
			event.preventDefault();
			filter('ods');
		});
	});

	var filter = function(type){
		$('#drawer').hide();
		if (type == 'pdf') {
			$('#listfilter').dialog({
				autoOpen: true,
				show: {
					effect: "appear",
					duration: 200
				},
				hide: {
					effect: "slide",
					duration: 200
				},
				modal: true,
				width: 450,
				height: 300,
				title: 'Filtra elenco',
				open: function (event, ui) {

				},
				close: function (event) {
					$('#overlay').hide();
				}
			});
		}
		else if (type == 'xls') {
			$('#listfilter2').dialog({
				autoOpen: true,
				show: {
					effect: "appear",
					duration: 200
				},
				hide: {
					effect: "slide",
					duration: 200
				},
				modal: true,
				width: 450,
				height: 300,
				title: 'Filtra elenco',
				open: function (event, ui) {

				},
				close: function (event) {
					$('#overlay').hide();
				}
			});
		}
		else if (type == 'ods') {
			$('#listfilter3').dialog({
				autoOpen: true,
				show: {
					effect: "appear",
					duration: 200
				},
				hide: {
					effect: "slide",
					duration: 200
				},
				modal: true,
				width: 450,
				height: 300,
				title: 'Filtra elenco',
				open: function (event, ui) {

				},
				close: function (event) {
					$('#overlay').hide();
				}
			});
		}
	};
	</script>
	<style type="text/css">
	table tbody tr:hover {
		/*background-color: rgba(30, 67, 137, .1);*/
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
	<?php
	if ($_REQUEST['show'] == 'alunni') {
		?>
		<div style="position: absolute; top: 75px; margin-left: 575px; margin-bottom: 10px; " class="rb_button">
			<a href="#" id="ods">
				<img src="../../images/ods-32.png" style="padding: 4px 0 0 5px" />
			</a>
		</div>
		<div style="position: absolute; top: 75px; margin-left: 625px; margin-bottom: 10px; " class="rb_button">
			<a href="#" id="xls">
				<i class="fa fa-file-excel-o _center" style="font-size: 2.2em; margin: 6px 0 0 8px; color: black"></i>
			</a>
		</div>
		<div style="position: absolute; top: 75px; margin-left: 675px; margin-bottom: 10px; " class="rb_button">
			<a href="#" id="pdf">
				<img src="../../images/pdf-32.png" style="padding: 4px 0 0 7px"/>
			</a>
		</div>
		<?php
	}
	else if ($_REQUEST['show'] == 'cdc') {
		?>
		<div style="position: absolute; top: 75px; margin-left: 625px; margin-bottom: 10px; " class="rb_button">
			<a href="pdf_cdc.php?id=<?php echo $cls ?>" id="pdf_cdc">
				<i class="fa fa-file-pdf-o _center" style="font-size: 2.1em; margin: 6px 0 0 8px; color: black"></i>
			</a>
		</div>

		<?php
	}
	?>
   	<table style="width: 95%; margin: 20px auto 0 auto">
   		<tbody>
   		<?php 
   		reset($widths);
   		reset($fields);
	    reset($data);
   		$x = 1;
   		foreach ($data as $row) {
   			reset($widths);
   		?>
	 	<tr class="bottom_decoration">
	 		<td style="width: 5%; text-align: right; font-weight: bold"><?php if ($_REQUEST['show'] == "alunni") echo $x ?></td>
	 		<td style="width: <?php echo $widths[0] - 5 ?>%; text-align: left; padding-left: 20px"><?php if ($_REQUEST['show'] == "alunni"): ?><a href="scheda_alunno.php?stid=<?php echo $row['id'] ?>"><?php endif; ?><?php echo $row['nome'] ?><?php if ($_REQUEST['show'] == "alunni"): ?></a><?php endif; ?></td>
	 		<td style="width: <?php echo $widths[1] ?>%; text-align: center; padding-left: 20px"><?php echo implode(', ', $row['sec_f']) ?></td>
 	    </tr>
 	    <?php
 	    	$x++;
   		}
 	    ?>
 	    </tbody>
 	    <tfoot>
	 	<tr>
    		<td colspan="3" style="height: 25px"></td> 
    	</tr>
		</tfoot>
	</table>		
	</div>
<p class="spacer"></p>		
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_label"><span>Classe <?php echo $_REQUEST['desc'] ?></span></div>
		<div class="drawer_link submenu"><a href="classe.php?id=<?php echo $_REQUEST['id'] ?>&show=cdc&desc=<?php echo $_REQUEST['desc'] ?>&tp=<?php echo $_REQUEST['tp'] ?>"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />Elenco docenti</a></div>
		<div class="drawer_link submenu"><a href="classe.php?id=<?php echo $_REQUEST['id'] ?>&show=alunni&desc=<?php echo $_REQUEST['desc'] ?>&tp=<?php echo $_REQUEST['tp'] ?>"><img src="../../images/35.png" style="margin-right: 10px; position: relative; top: 5%" />Elenco alunni</a></div>
		<div class="drawer_link submenu separator"><a href="classe.php?id=<?php echo $_REQUEST['id'] ?>&show=orario&desc=<?php echo $_REQUEST['desc'] ?>&tp=<?php echo $_REQUEST['tp'] ?>"><img src="../../images/70.png" style="margin-right: 10px; position: relative; top: 5%" />Orario delle lezioni</a></div>
		<div class="drawer_link"><a href="index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
		<div class="drawer_link"><a href="utility.php"><img src="../../images/59.png" style="margin-right: 10px; position: relative; top: 5%" />Utility</a></div>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<div id="listfilter" style="display: none; width: 250px">
	<p><a href="../teachers/gestione_classe/pdf_elenco_alunni.php?t=1">Solo nomi</a></p>
	<p><a href="../teachers/gestione_classe/pdf_elenco_alunni.php?t=2">Anagrafica</a></p>
	<p><a href="../teachers/gestione_classe/pdf_elenco_alunni.php?t=3">Con indirizzi</a></p>
	<p><a href="../teachers/gestione_classe/pdf_elenco_alunni.php?t=4">Numeri di telefono</a></p>
	<p><a href="../teachers/gestione_classe/pdf_elenco_alunni.php?t=5">Numeri di telefono con descrizione</a></p>
	<p><a href="../teachers/gestione_classe/pdf_elenco_alunni.php?t=6">Completo</a></p>
</div>
<div id="listfilter2" style="display: none; width: 250px">
	<p><a href="../teachers/gestione_classe/xls_elenco_alunni.php?t=1&app=xls">Solo nomi</a></p>
	<p><a href="../teachers/gestione_classe/xls_elenco_alunni.php?t=2&app=xls">Anagrafica</a></p>
	<p><a href="../teachers/gestione_classe/xls_elenco_alunni.php?t=3&app=xls">Con indirizzi</a></p>
	<p><a href="../teachers/gestione_classe/xls_elenco_alunni.php?t=4&app=xls">Numeri di telefono</a></p>
	<p><a href="../teachers/gestione_classe/xls_elenco_alunni.php?t=5&app=xls">Numeri di telefono con descrizione</a></p>
	<p><a href="../teachers/gestione_classe/xls_elenco_alunni.php?t=6&app=xls">Completo</a></p>
</div>
<div id="listfilter3" style="display: none; width: 250px">
	<p><a href="../teachers/gestione_classe/xls_elenco_alunni.php?t=1&app=ods">Solo nomi</a></p>
	<p><a href="../teachers/gestione_classe/xls_elenco_alunni.php?t=2&app=ods">Anagrafica</a></p>
	<p><a href="../teachers/gestione_classe/xls_elenco_alunni.php?t=3&app=ods">Con indirizzi</a></p>
	<p><a href="../teachers/gestione_classe/xls_elenco_alunni.php?t=4&app=ods">Numeri di telefono</a></p>
	<p><a href="../teachers/gestione_classe/xls_elenco_alunni.php?t=5&app=ods">Numeri di telefono con descrizione</a></p>
	<p><a href="../teachers/gestione_classe/xls_elenco_alunni.php?t=6&app=ods">Completo</a></p>
</div>
</body>
</html>
