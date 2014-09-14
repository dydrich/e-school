<div class="smallbox" id="working">
	<h2 class="menu_head">Menu moduli</h2>
	<p class="menu_label act_icon">Programmazione</p>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="<?php echo $_SESSION['__path_to_reg_home__'] ?>riunione_programmazione.php?rid=0">Nuova riunione</a></li>
		<?php
		$modules = $_SESSION['__user__']->getModules();
		if (count($modules) > 0) {
			foreach ($modules as $k => $module) {
				$provv = array();
				foreach ($module as $cl) {
					$provv[] = $_SESSION['__user__']->getClasses()[$cl]['classe'];
				}
				$link = "Mod. ".join(", ", $provv)."";
				?>
				<li><a href="<?php echo $_SESSION['__path_to_reg_home__'] ?>registro_programmazione.php?module=<?php echo $k ?>"><?php echo $link ?></a></li>
			<?php
			}
		}
		?>
	</ul>
</div>
