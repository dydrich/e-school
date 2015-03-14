<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Admin home page</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link href="../../css/general.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
		});
	</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col" class="cardbody">
		<div style="position: absolute; top: 75px; margin-left: 625px; margin-bottom: -5px" class="rb_button">
			<a href="dettaglio_modulo.php?id=0">
				<img src="../../images/39.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<div class="card_container" style="margin-top: 20px">
        <?php
        $giorni = array(1 => "LUN", 2 => "MAR", 3 => "MER", 4 => "GIO", 5 => "VEN", 6 => "SAB");
        $use_module = true;
        foreach ($modules as $k => $module){
			$no_less_days = $module->getNoLessonDays();
			$dd = array();
			foreach ($giorni as $t => $g){
				if (!in_array($t, $no_less_days)){
					$dd[] = $g;
				}
			}
			if ($module->getNumberOfDays() < 1){
				$use_module = false;
			}
			$string_days = join(", ", $dd);
        ?>
			<a href="dettaglio_modulo.php?idm=<?php echo $k ?>" class="mod_link">
	        <div class="card" id="row_<?php echo $k ?>">
		        <div class="card_title">Modulo #<?php echo $k ?>
			        <div class="normal" style="float: right; margin-right: 20px" id="">
				        Mensa: <?php echo ($module->hasCanteen()) ?  "SI" : "NO" ?>
			        </div>
		        </div>
		        <div class="card_content">
			        <div style="width: 60%; float: left">Giorni: <?php if ($use_module) echo $module->getNumberOfDays()," (".$string_days.")"; else echo $data[$k]['giorni'] ?></div>
			        <div style="width: 30%; float: left">Ore: <?php if ($use_module) echo $module->getClassDuration()->toString(RBTime::$RBTIME_SHORT)." (".$module->getNumberOfHours()." di lezione)"; else echo $data[$k]['ore_settimanali'].":00" ?></div>
		        </div>
	        </div>
			</a>
           	<?php 
           	}
           	?>
        </div>
	<p class="spacer"></p>
	</div>
</div>
<?php include "../footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="../../index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="../index.php"><img src="../../images/31.png" style="margin-right: 10px; position: relative; top: 5%" />Admin</a></div>
		<div class="drawer_link"><a href="http://www.istitutoiglesiasserraperdosa.it"><img src="../../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
