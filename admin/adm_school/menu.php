<div class="smallbox" id="working">
	<h2 class="menu_head">Menu</h2>
	<p class="menu_label class_icon">Scuola</p>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>index.php">Home</a></li>
		<?php if($admin_level == 0): ?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_school/sedi.php">Sedi</a></li>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_school/moduli_orario.php">Moduli orario</a></li>
		<?php endif; ?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_school/materie.php">Materie</a></li>
		<?php
		if((count($_SESSION['__school_level__']) > 1)){
			if( $admin_level == 0){
		?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>year.php">Gestione date anno scolastico</a></li>
		<?php
		}
		foreach ($_SESSION['__school_level__'] as $k => $sl){
			if ($k == $admin_level || $admin_level == 0){
		?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>year_data.php?school_order=<?php echo $k ?>">A. S. <?php echo $sl ?></a></li>
		<?php
				}
			}
		} else{ ?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>year.php">Gestione anno in corso</a></li>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>year_data.php?school_order=<?php echo $_SESSION['__only_school_level__'] ?>"><?php echo $_SESSION['__current_year__']->to_string() ?></a></li>
		<?php } ?>
	</ul>
</div>
