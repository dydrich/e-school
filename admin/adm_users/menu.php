<div class="smallbox" id="working">
	<h2 class="menu_head">Menu</h2>
	<p class="menu_label class_icon">Utenti</p>
	<ul class="menublock" style="" dir="rtl">
		<?php if($_SESSION['__school_order__'] == 0): ?><li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_users/users.php">Utenti</a></li><?php endif; ?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_users/teachers.php?school_order=<?php echo $_SESSION['__school_order__'] ?>">Docenti</a></li>
		<?php if((count($_SESSION['__school_level__']) > 1) && $_SESSION['__school_order__'] == 0){
			foreach ($_SESSION['__school_level__'] as $k => $sl){
		?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_students/alunni.php?school_order=<?php echo $k ?>">Alunni <?php echo $sl ?></a></li>
		<?php } ?>
		<?php
		if(is_installed("parents")):
			if((count($_SESSION['__school_level__']) > 1) && $_SESSION['__school_order__'] == 0){
				foreach ($_SESSION['__school_level__'] as $k => $sl){
		?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_parents/genitori.php?school_order=<?php echo $k ?>">Genitori <?php echo $sl ?></a></li>
		<?php } ?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_parents/genitori_inattivi.php">Genitori inattivi</a></li>
		<?php
			}
		endif; ?>
		<?php } else{ ?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_students/alunni.php?school_order=<?php echo $_SESSION['__school_order__'] ?>">Alunni</a></li>
		<?php if(is_installed("parents")): ?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_parents/genitori.php?school_order=<?php echo $_SESSION['__school_order__'] ?>">Genitori</a></li>
		<?php endif; ?>
		<?php } ?>
		<?php if($_SESSION['__school_order__'] == 0){ ?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>new_pwd.php">Modifica password utente</a></li>
		<?php } ?>
	</ul>
</div>