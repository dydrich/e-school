<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Gestione classe</title>
<link rel="stylesheet" href="../../../css/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/page.js"></script>
</head> 
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "class_working.php" ?>
</div>
<div id="left_col">
	<h2 class="_center"><?php print "Classe ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione()." - ".$_SESSION['__current_year__']->to_string() ?></h2>
	<?php
	if($vacance){
	?>
	<div id="welcome" style="">
		<p id="w_head" style="background: url(../../../images/70.png) no-repeat;">BUONE VACANZE</p>
		<p class="w_text" style="text-transform: uppercase; margin-bottom: 0">Ci rivediamo a settembre</p>
	</div>
	<?php 
	}
	else {
	?>
	<div id="welcome" style="">
		<p id="w_head" style="background: url(../../../images/70.png) no-repeat;"><?php echo $tod ?></p>
		<p class="w_text" style="text-transform: uppercase; margin-bottom: 0">Compiti</p>
		<ul style="margin: 2px 0 2px 0">
		<?php 
		if($res_today_hw->num_rows < 1){
		?>
			<li style="margin: 0 0 0 10px;">Nessun compito &egrave; stato ancora assegnato</li>
		<?php 
		}
		else{
			while($hw = $res_today_hw->fetch_assoc()){
		?>
			<li style="margin: 0 0 0 10px;"><?php print $hw['mat'].":: ".$hw['descrizione'] ?></li>
		<?php 
			}
		}
		?>
		</ul>
		<p class="w_text" style="text-transform: uppercase; margin-bottom: 0">Attivit&agrave;</p>
		<ul style="margin: 2px 0 2px 0">
		<?php 
		if($res_today_act->num_rows < 1){
		?>
			<li style="margin: 0 0 0 10px;">Nessuna attivit&agrave; prevista</li>
		<?php 
		}
		else{
			while($act = $res_today_act->fetch_assoc()){
		?>
			<li style="margin: 0 0 0 10px;"><?php print $act['mat'].":: ".$act['descrizione'] ?></li>
		<?php 
			}
		}
		?>
		</ul>
	</div>
	<div id="welcome" style="border-top: 1px solid rgba(211, 222, 199, 0.6)">
		<p id="w_head" style="background: url(../../../images/70.png) no-repeat;"><?php echo $tom ?></p>
		<p class="w_text" style="text-transform: uppercase; margin-bottom: 0">Compiti</p>
		<ul style="margin: 2px 0 2px 0">
		<?php 
		if($res_tomorrow_hw->num_rows < 1){
		?>
			<li style="margin: 0 0 0 10px;">Nessun compito &egrave; stato ancora assegnato</li>
		<?php 
		}
		else{
			while($hw = $res_tomorrow_hw->fetch_assoc()){
		?>
			<li style="margin: 0 0 0 10px;"><?php print $hw['mat'].":: ".$hw['descrizione'] ?></li>
		<?php 
			}
		}
		?>
		</ul>
		<p class="w_text" style="text-transform: uppercase; margin-bottom: 0">Attivit&agrave;</p>
		<ul style="margin: 2px 0 2px 0">
		<?php 
		if($res_tomorrow_act->num_rows < 1){
		?>
			<li style="margin: 0 0 0 10px;">Nessuna attivit&agrave; prevista</li>
		<?php 
		}
		else{
			while($act = $res_tomorrow_act->fetch_assoc()){
		?>
			<li style="margin: 0 0 0 10px;"><?php print $act['mat'].":: ".$act['descrizione'] ?></li>
		<?php 
			}
		}
		?>
		</ul>
	</div>
	<div id="welcome" style="border-top: 1px solid rgba(211, 222, 199, 0.6)">
		<p id="w_head" style="background: url(../../../images/70.png) no-repeat;"><?php echo $post_tm ?></p>
		<p class="w_text" style="text-transform: uppercase; margin-bottom: 0">Compiti</p>
		<ul style="margin: 2px 0 2px 0">
		<?php 
		if($res_day3_hw->num_rows < 1){
		?>
			<li style="margin: 0 0 0 10px;">Nessun compito &egrave; stato ancora assegnato</li>
		<?php 
		}
		else{
			while($hw = $res_day3_hw->fetch_assoc()){
		?>
			<li style="margin: 0 0 0 10px;"><?php print $hw['mat'].":: ".$hw['descrizione'] ?></li>
		<?php 
			}
		}
		?>
		</ul>
		<p class="w_text" style="text-transform: uppercase; margin-bottom: 0">Attivit&agrave;</p>
		<ul style="margin: 2px 0 2px 0">
		<?php 
		if($res_day3_act->num_rows < 1){
		?>
			<li style="margin: 0 0 0 10px;">Nessuna attivit&agrave; prevista</li>
		<?php 
		}
		else{
			while($act = $res_day3_act->fetch_assoc()){
		?>
			<li style="margin: 0 0 0 10px;"><?php print $act['mat'].":: ".$act['descrizione'] ?></li>
		<?php 
			}
		}
		?>
		</ul>
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