<nav id="navigation">
	<div id="head_label"><p style="margin-top: 5px; vertical-align: top"><?php echo $navigation_label ?></p></div>
	<div class="nav_div"><a href="<?php echo $_SESSION['__path_to_reg_home__'] ?>index.php" style="position: relative; top: 5px">Home</a></div>
	<div class="nav_div"><a href="<?php echo $_SESSION['__path_to_reg_home__'] ?>profile.php" style="position: relative; top: 5px">Profilo</a></div>
	<div class="nav_div"><a href="index.php?id_st=<?php echo $_SESSION['__sp_student__']['alunno'] ?>" style="position: relative; top: 5px">Registro</a></div>
	<div class="nav_div"></div>
	<div class="nav_div"></div>
</nav>