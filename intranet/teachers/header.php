<header id="header">
	<div id="sc_firstrow">
		<img src="<?php echo $_SESSION['__path_to_root__'] ?>css/site_themes/<?php echo getTheme() ?>/images/icona_scuola.gif" style="width: 20px"/>
		<span style="position: relative; bottom: 5px"><?php echo $_SESSION['__config__']['software_name']." ".$_SESSION['__config__']['software_version'] ?> - <?php echo $_SESSION['__current_year__']->to_string() ?></span>
	</div>
	<div id="sc_secondrow">
		<?php if ($is_teacher_index): ?>
		<div id="dashboard">
			<div style="width: 180px; float: right; height: 30px">
				<a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=teachers&page=threads" title="<?php if ($unread > 0) echo 'Hai '.$unread.' messaggi non letti'; else echo 'Leggi i messaggi' ?>">
				<p style="width: 48px; text-align: center; position: relative; top: -13px; font-size: 18px; float: right">
					<i class="fa fa-envelope"></i>
					<?php if ($unread > 0): ?>
						<span class="numberlabel numberlabel-danger"><?php echo $unread ?></span>
					<?php endif; ?>
				</p>
				</a>
				<a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=teachers&page=files" title="Scarica i file">
				<p style="width: 48px; text-align: center; position: relative; top: -13px; font-size: 18px; float: right">
					<i class="fa fa-folder"></i>
					<?php if ($not_downl > 0): ?>
						<span class="numberlabel numberlabel-danger"><?php echo $not_downl ?></span>
					<?php endif; ?>
				</p>
				</a>
				<a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=teachers&page=vedi_circolari" title="<?php if ($unread_count > 0) echo 'Hai '.$unread_count.' circolari non lette'; else echo 'Leggi le circolari' ?>">
				<p style="width: 48px; text-align: center; position: relative; top: -13px; font-size: 18px; float: right">
					<i class="fa fa-file"></i>
					<?php if ($unread_count > 0): ?>
						<span class="numberlabel numberlabel-danger"><?php echo $unread_count ?></span>
					<?php endif; ?>
				</p>
				</a>
			</div>
		</div>
		<?php endif; ?>
		<img src="<?php echo $_SESSION['__path_to_root__'] ?>images/13.png" style="position: relative; top: 1px" /><span style="margin-left: 5px"><?php echo $_SESSION['__user__']->getFullName() ?></span>
	</div>
</header>
