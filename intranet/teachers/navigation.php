<nav id="navigation">
	<div id="head_label"><p style="margin-top: 5px; vertical-align: top"><?php echo $navigation_label ?></p></div>
	<div class="nav_div"><a href="<?php echo $_SESSION['__path_to_reg_home__'] ?>index.php" style="position: relative; top: 5px">&middot; Home</a></div>
	<div class="nav_div"><a href="<?php echo $_SESSION['__path_to_reg_home__'] ?>profile.php" style="position: relative; top: 5px">&middot; Profilo</a></div>
	<div class="nav_div"><?php if(is_installed("docs")){ ?><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/documents/load_module.php?module=docs&area=teachers" style="position: relative; top: 5px">&middot; Documenti</a><?php } ?></div>
	<div class="nav_div"></div>
	<div class="nav_div"></div>
</nav>