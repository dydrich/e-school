<div class="smallbox" id="working">
	<p class="menu_label class_icon">Classi</p>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>index.php?tab=3">Home</a></li>
		<?php
		if(count($_SESSION['__school_level__']) > 1){
			foreach ($_SESSION['__school_level__'] as $k => $sl){
				if ($k == $admin_level || $admin_level == 0){
		?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_classes/classi.php?school_order=<?php echo $k ?>">Classi <?php echo $sl ?></a></li>
		<?php
				}
			}
		}
		?>
		<?php if($admin_level == 1 || $admin_level == 0): ?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_classes/elenco_ripetenti.php">Assegna ripetenti</a></li>
		<?php endif; ?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_classes/alunni_liberi.php">Alunni senza classe</a></li>
		<?php if($admin_level == 2 || $admin_level == 0): ?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_classes/moduli_primaria.php">Moduli scuola primaria</a></li>
		<?php endif; ?>
	</ul>
</div>
