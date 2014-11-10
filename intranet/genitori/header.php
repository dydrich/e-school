<header id="header">
	<div id="sc_firstrow">
		<img src="<?php echo $_SESSION['__path_to_root__'] ?>/css/site_themes/<?php echo getTheme() ?>/images/icona_scuola.gif" style="width: 20px"/>
		<span style="position: relative; bottom: 5px"><?php echo $_SESSION['__config__']['software_name']." ".$_SESSION['__config__']['software_version'] ?> - <?php echo $_SESSION['__current_year__']->to_string() ?></span>
	</div>
	<div id="sc_secondrow">
		<img src="<?php echo $_SESSION['__path_to_root__'] ?>images/13.png"/><span style="margin-left: 5px"><?php echo $_SESSION['__user__']->getFullName() ?></span>
	</div>
</header>
