<div class="smallbox" id="working">
	<p class="menu_label class_icon">La tua scuola</p>
	<?php
	if (isset($_SESSION['__school_order__'])){
	?>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="<?php echo $_SESSION['__config__']['root_site']."/intranet/manager/"; ?>elenco_docenti.php">Docenti</a></li>
		<li><a href="<?php echo $_SESSION['__config__']['root_site']."/intranet/manager/"; ?>supplenze.php">Supplenze</a></li>
		<li><a href="../../admin/adm_students/alunni.php?area_from=SEG">Alunni</a></li>
		<li><a href="<?php echo $_SESSION['__config__']['root_site']."/intranet/manager/"; ?>sostegno.php">Sostegno</a></li>
		<li><a href="<?php echo $_SESSION['__config__']['root_site']."/intranet/manager/"; ?>elenco_classi.php">Classi</a></li>
		<li><a href="<?php echo $_SESSION['__config__']['root_site']."/intranet/manager/"; ?>pagelle.php">Pagelle online</a></li>
		<li><a href="<?php echo $_SESSION['__config__']['root_site']."/intranet/manager/"; ?>gestione_scrutini.php">Gestione scrutini</a></li>
		<li><a href="<?php echo $_SESSION['__config__']['root_site']."/intranet/manager/"; ?>pagellini.php">Gestione pagellini</a></li>
		<?php if ($_SESSION['__school_order__'] == 1): ?>
		<li><a href="<?php echo $_SESSION['__config__']['root_site']."/intranet/manager/"; ?>colloqui.php">Gestione colloqui</a></li>
		<?php endif; ?>
	</ul>
	<p class="menu_label act_icon">Registro di classe</p>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="<?php echo $_SESSION['__config__']['root_site']."/intranet/manager/"; ?>registro.php?do=cls">Riepilogo classi</a></li>
		<li><a href="<?php echo $_SESSION['__config__']['root_site']."/intranet/manager/"; ?>registro.php?do=abs">Alunni a rischio</a></li>
		<li><a href="<?php echo $_SESSION['__config__']['root_site']."/intranet/manager/"; ?>stampa_registri.php">Registri PDF</a></li>
	</ul>
	<?php 
	}
	else {
	?>
	<ul class="menublock" style="" dir="rtl">
	<?php 
		foreach ($_SESSION['__school_level__'] as $k => $sl){
	?>
		<li><a href="#" onclick="select_level('<?php echo $_SESSION['__path_to_root__'] ?>', '<?php echo basename($_SERVER['PHP_SELF']) ?>', <?php echo $k ?>)" id="level_<?php echo $k ?>" class="school_level"><?php echo $sl ?></a></li>
	<?php 
		}
	?>
	</ul>
	<?php
	}
	?>
	
</div>
