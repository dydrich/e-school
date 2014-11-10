<div class="smallbox" id="working">
	<p class="menu_label class_icon">Sviluppo</p>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>index.php?tab=9">Home</a></li>
<?php if($admin_level == 0): ?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>modules.php">Modifica moduli installati</a></li>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>env.php" id="env_lnk">Variabili</a></li>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>check_perms.php" id="">Verifica permessi utente</a></li>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>tests.php" id="">Test classi</a></li>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>scegli_utente.php" id="">SuDo</a></li>
<?php endif; ?>
	</ul>
</div>
