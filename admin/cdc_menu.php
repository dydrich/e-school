<div class="smallbox" id="working">
	<h2 class="menu_head">Menu</h2>
	<p class="menu_label class_icon">CDC</p>
	<ul class="menublock" style="" dir="rtl">
		<?php
		if((count($_SESSION['__school_level__']) > 1) && $admin_level == 0){
			foreach ($_SESSION['__school_level__'] as $k => $sl){
				if($admin_level == $k || $admin_level == 0){
		?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>cdc_state.php?school_order=<?php echo $k ?>" id="cdc_sm">CDC <?php echo $sl ?></a></li>
		<?php
				}
			}
		}
		else {
		?>
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>cdc_state.php?school_order=<?php echo $admin_level ?>" id="cdc_sm">Gestisci CDC</a></li>
		<?php } ?>
	</ul>
</div>
