<div class="smallbox" id="working">
	<p class="menu_label class_icon">Scrutini</p>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>index.php?tab=7">Home</a></li>
		<?php
		$level = "secondaria";
		if((count($_SESSION['__school_level__']) > 1) && $admin_level == 0) {
			foreach ($_SESSION['__school_level__'] as $k => $sl) {
				if ($k == 2) {
					$level = "primaria";
				}
				if ($admin_level == $k || $admin_level == 0) {
					if ($k == 3) continue;
					?>
					<li><a href="assignment_table.php?quadrimestre=1&school_order=<?php echo $k ?>">Primo quadrimestre <?php echo $level ?></a></li>
					<li><a href="assignment_table.php?quadrimestre=2&school_order=<?php echo $k ?>">Secondo quadrimestre <?php echo $level ?></a></li>
					<?php
				}
			}
		}
		?>
	</ul>
	<p class="menu_label class_icon">Pagelle</p>
	<ul class="menublock" style="" dir="rtl">
		<?php
		if((count($_SESSION['__school_level__']) > 1) && $admin_level == 0){
			foreach ($_SESSION['__school_level__'] as $k => $sl){
				if($admin_level == $k || $admin_level == 0){
					?>
					<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>parametri_pagella.php?school_order=<?php echo $k ?>" id="cdc_sm"><?php echo $sl ?></a></li>
				<?php
				}
			}
		}
		else {
			?>
			<li><a href="<?php echo $_SESSION['__path_to_mod_home__'] ?>parametri_pagella.php?school_order=<?php echo $admin_level ?>" id="cdc_sm">Gestisci parametri pagella</a></li>
		<?php } ?>
	</ul>
</div>
