<div class="smallbox" id="working">
	<p class="menu_label class_icon">Gestione esami a. s. <?php echo $_SESSION['__current_year__']->get_descrizione() ?></p>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="<?php echo $_SESSION['__config__']['root_site']."/intranet/manager/"; ?>amministrazione_esami.php">Dati amministrativi</a></li>
        <li><a href="<?php echo $_SESSION['__config__']['root_site']."/intranet/manager/"; ?>commissioni.php">Commissione d'esame</a></li>
	</ul>
</div>
<?php
