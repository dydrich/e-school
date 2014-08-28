<div class="smallbox" id="working">
	<h2 class="menu_head">Menu</h2>
	<p class="menu_label pers_icon">Utenti</p>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>index.php?tab=2">Home</a></li>
		<?php if($_SESSION['__school_order__'] == 0): ?><li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_users/users.php">Utenti</a></li><?php endif; ?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_users/teachers.php?school_order=<?php echo $_SESSION['__school_order__'] ?>">Docenti</a></li>
		<?php if($_SESSION['__school_order__'] == 0): ?><li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>new_pwd.php">Modifica password utente</a></li><?php endif; ?>
	</ul>
		<?php if((count($_SESSION['__school_level__']) > 1) && $_SESSION['__school_order__'] == 0){
		?>
	<p class="menu_label data_icon">Alunni</p>
	<ul class="menublock" style="" dir="rtl">
		<?php
			foreach ($_SESSION['__school_level__'] as $k => $sl){
		?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_students/alunni.php?school_order=<?php echo $k ?>"><?php echo $sl ?></a></li>
		<?php
			}
		?>
	</ul>
		<?php
		if(is_installed("parents")):
		?>
	<p class="menu_label act_icon">Genitori</p>
	<ul class="menublock" style="" dir="rtl">
		<?php
			if((count($_SESSION['__school_level__']) > 1) && $_SESSION['__school_order__'] == 0){
				foreach ($_SESSION['__school_level__'] as $k => $sl){
		?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_parents/genitori.php?school_order=<?php echo $k ?>"> <?php echo $sl ?></a></li>
		<?php } ?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_parents/genitori_inattivi.php">Genitori inattivi</a></li>
		<?php
			}
		endif;
		?>
	</ul>
		<?php } else{ ?>
	<p class="menu_label data_icon">Alunni</p>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_students/alunni.php?school_order=<?php echo $_SESSION['__school_order__'] ?>">Alunni</a></li>
	</ul>
		<?php if(is_installed("parents")): ?>
	<p class="menu_label act_icon">Genitori</p>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>adm_parents/genitori.php?school_order=<?php echo $_SESSION['__school_order__'] ?>">Genitori</a></li>
		<?php endif; ?>
	</ul>
		<?php } ?>
	</ul>
</div>
