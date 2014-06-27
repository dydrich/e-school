<div class="smallbox" id="working">
	<h2 class="menu_head">Menu</h2>
	<p class="menu_label class_icon">Nuovo anno</p>
	<ul class="menublock" style="" dir="rtl">
	<?php if ($admin_level == 0): ?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_classes/new_year_classes.php">Attivazione classi </a></li>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_students/load_students.php">Importa alunni</a></li>
	<?php endif; ?>
	<?php
		if((count($_SESSION['__school_level__']) > 1) && $admin_level == 0){
			foreach ($_SESSION['__school_level__'] as $k => $sl){
	?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_students/insert_students.php?school_order=<?php echo $k ?>">Inserisci alunni <?php echo substr($sl, 7) ?></a></li>
	<?php
			}
		}
		else{
	?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_students/insert_students.php?school_order=<?php echo $admin_level ?>">Inserisci alunni velocemente</a></li>
	<?php } ?>
	<?php if($admin_level == 0): ?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>schedule_table.php" id="sched_lnk">Orario</a></li>
	<?php endif; ?>
	</ul>
</div>
