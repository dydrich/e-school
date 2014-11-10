<div class="smallbox" id="working">
	<p class="menu_label act_icon">Profilo personale</p>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="change_password.php" style="text-decoration: none">Modifica password</a></li>
		<li><a href="account.php" style="text-decoration: none">Dati personali</a></li>
		<?php if (27 != $_SESSION['__user__']->getSubject() && 41 != $_SESSION['__user__']->getSubject()): ?>
		<li><a href="schedule.php" style="text-decoration: none">Orario lezioni</a></li>
		<?php endif; ?>
	</ul>
	<p class="menu_label schedule_icon">Didattica</p>
	<ul class="menublock" style="" dir="rtl">
		<?php if (27 != $_SESSION['__user__']->getSubject() && 41 != $_SESSION['__user__']->getSubject()): ?>
		<li><a href="stampa_registro.php" style="text-decoration: none">Stampa registro</a></li>
		<?php else : ?>
			<li><a href="stampa_registro_sostegno.php" style="text-decoration: none">Stampa registro</a></li>
		<?php endif; ?>
		<?php if (27 != $_SESSION['__user__']->getSubject() && 41 != $_SESSION['__user__']->getSubject() && isset($_SESSION['__user_config__']['registro_obiettivi'][0]) && 1 == $_SESSION['__user_config__']['registro_obiettivi'][0]): ?>
		<li><a href="obiettivi.php" style="text-decoration: none">Obiettivi didattici</a></li>
		<?php endif; ?>
	</ul>
	<p class="menu_label data_icon">Configurazioni</p>
	<ul class="menublock" style="" dir="rtl">
		<?php if (27 != $_SESSION['__user__']->getSubject() && 41 != $_SESSION['__user__']->getSubject()): ?>
		<li><a href="grade_type.php" style="text-decoration: none">Tipologia di prove</a></li>
		<li><a href="conf_recordbook.php" style="text-decoration: none">Registro personale</a></li>
		<li><a href="conf_classbook.php" style="text-decoration: none">Registro di classe</a></li>
		<?php endif; ?>
		<li><a href="themes.php?area=teachers" style="text-decoration: none">Tema</a></li>
	</ul>
</div>
