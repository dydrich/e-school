<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
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
	});
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
	<div style="width: 100%; height: 360px">
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
</body>
</html>
