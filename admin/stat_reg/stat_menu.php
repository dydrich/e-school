<div class="smallbox" id="working">
	<p class="menu_label class_icon">Statistiche registro</p>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>index.php?tab=8">Home</a></li>
	</ul>
	<p class="menu_label class_icon">Genitori</p>
	<ul class="menublock" style="" dir="rtl">
<?php
if((count($_SESSION['__school_level__']) > 1)){
	if( $admin_level == 0){
?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>stat_reg/stat_reg_parents.php">Genitori</a></li>
<?php
	}
	foreach ($_SESSION['__school_level__'] as $k => $sl){
		if($admin_level == $k || $admin_level == 0){
?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>stat_reg/stat_reg_parents.php?school_order=<?php echo $k ?>">Genitori <?php echo $sl ?></a></li>
		<?php
		}
	}
} else{ ?>
	<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>stat_reg/stat_reg_parents.php?school_order=<?php echo $_SESSION['__only_school_level__'] ?>">Genitori</a></li>
<?php } ?>
	</ul>
	<p class="menu_label class_icon">Download</p>
	<ul class="menublock" style="" dir="rtl">
<?php
if((count($_SESSION['__school_level__']) > 1)){
	if( $admin_level == 0){
?>
	<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>stat_reg/stat_reg_reports.php">Pagelle</a></li>
	<?php
	}
	foreach ($_SESSION['__school_level__'] as $k => $sl){
		if($admin_level == $k || $admin_level == 0){
?>
	<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>stat_reg/stat_reg_reports.php?school_order=<?php echo $k ?>">Pagelle <?php echo $sl ?></a></li>
<?php
		}
	}
} else{ ?>
	<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>stat_reg/stat_reg_reports.php?school_order=<?php echo $_SESSION['__only_school_level__'] ?>">Pagelle</a></li>
<?php } ?>
	</ul>
</div>
