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
var get = function(file){
	//document.location.href = "../../lib/download_manager.php?dw_type=report&f="+file+"&sess=1&y=<?php echo $_SESSION['__current_year__']->get_ID() ?>&noread=1&delete=1"
	document.location.href = "../../modules/documents/download_manager.php?doc=report&school_order=<?php echo $school_order ?>&area=genitori&f="+file+"&sess=1&y=<?php echo $_SESSION['__current_year__']->get_ID() ?>&noread=1&delete=1"
	setTimeout('document.location.href = "pagella.php"', 2000);
};
</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "sons_menu.php" ?>
<?php include "class_working.php" ?>
</div>
<div id="left_col">
	<div class="group_head">
		Area genitori: schede di valutazione e pagelle
	</div>
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
</body>
</html>
