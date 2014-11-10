<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>:: area genitori</title>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
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
		});
		var get = function(file){
			//document.location.href = "../../lib/download_manager.php?dw_type=report&f="+file+"&sess=1&y=<?php echo $_SESSION['__current_year__']->get_ID() ?>&noread=1&delete=1"
			document.location.href = "../../modules/documents/download_manager.php?doc=report&school_order=<?php echo $school_order ?>&area=genitori&f="+file+"&sess=1&y=<?php echo $_SESSION['__current_year__']->get_ID() ?>&noread=1&delete=1"
			setTimeout('document.location.href = "pagella.php"', 2000);
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
<?php if ($is_active){ ?>
	<div class="welcome">
		<p id="w_head"><?php echo $_SESSION['__current_year__']->to_string() ?> - <?php if ($is_active) echo $_SESSION['__classe__']->to_string() ?></p>
<?php 
if (count($pagelle) > 0) {
	$pagella1q = $pagelle[0];
	$pagella2q = $pagelle[1];

	if ($pagella2q['data_pubblicazione'] < date("Y-m-d") || ($pagella2q['data_pubblicazione'] == date("Y-m-d") && $pagella2q['ora_pubblicazione'] <= date("H:i:s"))){
		$idp = $db->executeCount("SELECT id_pagella FROM rb_pubblicazione_pagelle WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND quadrimestre = 2");
		
?>
		<p class="w_text">
			<a href="../../modules/documents/download_manager.php?doc=report&school_order=<?php echo $school_order ?>&area=genitori&sess=2&noread=0&y=<?php echo $_SESSION['__current_year__']->get_ID() ?>&f=<?php echo $pagella2q['id_file'] ?>&stid=<?php echo $_SESSION['__current_son__'] ?>&parent=<?php echo $_SESSION['__user__']->getUid() ?>&idp=<?php echo $idp ?>">Scheda di valutazione finale</a><br />
		</p>
<?php
	}
	else if ($pagella1q['data_pubblicazione'] < date("Y-m-d") || ($pagella1q['data_pubblicazione'] == date("Y-m-d") && $pagella1q['ora_pubblicazione'] <= date("H:i:s"))){
		$report_manager = new ReportManager($db, $_SESSION['__current_year__']->get_ID(), $school_order);
		$sel_stds = "SELECT cognome, nome, rb_alunni.id_alunno AS alunno, sesso, rb_alunni.id_classe, anno_corso, sezione FROM rb_alunni, rb_classi WHERE rb_alunni.id_classe = rb_classi.id_classe AND id_alunno = ".$_SESSION['__current_son__'];
		$res_stds = $db->executeQuery($sel_stds);
		$student = $res_stds->fetch_assoc();
		$file = $report_manager->createOnFlyReport(1, $student);
		try{
			$id_p = $db->executeCount("SELECT id_pagella FROM rb_pubblicazione_pagelle WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND quadrimestre = 1");
			$insert_read = $db->executeQuery("INSERT INTO rb_lettura_pagelle (id_pubblicazione, alunno, data_lettura, genitore) VALUES ({$id_p}, {$_SESSION['__current_son__']}, NOW(), {$_SESSION['__user__']->getUid()})");
		} catch (MySQLException $ex){
			
		}
		//echo $file;
?>
		<p class="w_text">
			<a href="#" onclick="get('<?php echo $file ?>')">Scheda di valutazione primo quadrimestre</a><br />
		</p>
		<p class="w_text">
			La scheda di valutazione finale sar&agrave; disponibile dalle ore <?php echo substr($pagella2q['ora_pubblicazione'], 0, 5) ?> del giorno <?php echo format_date($pagella2q['data_pubblicazione'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?> 
		</p>
<?php
	}
	else {
		$a = "";
?>
		<p class="w_text">
			La scheda di valutazione del primo quadrimestre sar&agrave; disponibile dalle ore <?php echo substr($pagella1q['ora_pubblicazione'], 0, 5) ?> del giorno <?php echo format_date($pagella1q['data_pubblicazione'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?> 
		</p>
<?php
	}
}
?>
	</div>
<?php
}

if(count($pagelle_old) > 0){
	$anno = 0;
	foreach ($pagelle_old as $pg) {
		$label = "Scheda di valutazione finale";
		if (isset($anno) && $anno != $pg['anno']){
			if ($anno != 0){
				echo "</div>";
			}
?>
	<div class="welcome">
		<p id="w_head">Anno scolastico <?php echo $pg['descrizione'] ?> - classe <?php echo $pg['desc_classe'] ?></p>
		<p class="w_text">
			<a href="../../modules/documents/download_manager.php?doc=report&area=genitori&sess=2&noread=0&y=<?php echo $pg['anno'] ?>&f=<?php echo $pg['id_file'] ?>&stid=<?php echo $_SESSION['__current_son__'] ?>&parent=<?php echo $_SESSION['__user__']->getUid() ?>&idp=<?php echo $pg['id_pubblicazione'] ?>"><?php echo $label ?> </a><br />
			
		</p>
<?php
		}
		$anno = $pg['anno'];
	}
	echo "</div>";
}
?>
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
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
			<?php if ($area == "alunni"): ?>
				<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=alunni"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
			<?php endif; ?>
			<?php if(is_installed("com")){ ?>
				<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=<?php echo $area ?>"><img src="<?php echo $_SESSION['__path_to_root__'] ?>images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
			<?php } ?>
		</div>
		<?php if (isset($_SESSION['__sudoer__'])): ?>
			<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
		<?php endif; ?>
		<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
	</div>
	<?php if ($area == "genitori" && count($_SESSION['__sons__']) > 1){ ?>
		<div id="other_drawer" class="drawer" style="height: 72px; display: none; position: absolute">
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
