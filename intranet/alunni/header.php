<header id="header">
	<div id="box1">
		<div id="sc_firstrow"><?php echo $_SESSION['__config__']['intestazione_scuola'] ?></div>
		<div id="sc_secondrow"><?php echo $_SESSION['__current_year__']->to_string() ?></div>
	</div>
	<div id="mid"></div>
	<div id="box2">
		<div id="user_firstrow"><?php echo $_SESSION['__user__']->getFullName() ?></div>
		<div id="user_secondrow">Area studenti</div>		
	</div>
	<div id="box3"></div>
	<div id="esc">
		<ul style="list-style-type: disc">
		<li><a href="<?php echo $_SESSION['__path_to_root__'] ?>shared/do_logout.php">Esci</a></li>
			<?php if (isset($_SESSION['__sudoer__'])): ?>
				<li><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back">DeSuDo</a></li>
			<?php endif; ?>
		</ul>
	</div>
</header>