<div class="nav">
		<ul>	
			<!-- MENU -->
			<li class="selected"><a href="../index.php">Home</a></li>
			<li><a href="../teachers.php">Docenti</a></li>
			<li><a href="../students.php">Alunni</a></li>
			<?php 
			$today = date("Y-m-d");
			$sel_y = "SELECT * FROM anni ORDER BY id_anno DESC LIMIT 1";
			$res_y = $db->executeQuery($sel_y);
			$new_year = new AnnoScolastico($res_y->fetch_assoc());
			//if($_SESSION['__user__']->getUid() == 1 && ($today > format_date($_SESSION['__current_year__']->get_data_termine_lezioni(), IT_DATE_STYLE, SQL_DATE_STYLE, "-") && $today < (format_date($new_year->get_data_apertura(), IT_DATE_STYLE, SQL_DATE_STYLE, "-")))){
			if($today < format_date($_SESSION['__current_year__']->get_data_inizio_lezioni(), IT_DATE_STYLE, SQL_DATE_STYLE, "-")){
			?>
			<li><a href="index.php">Nuove classi </a></li>
			<?php } ?>
			<li><a href="../profile.php">Profilo</a></li>
			<li><a href="../docs.php">Documenti</a></li>
			<li class="end"><a href="/do_logout.php">Esci</a></li>
			<!-- END MENU -->
		</ul>
		<p style="float: right; font-size: 11px; color: white; margin-right: 10px; padding-top: 10px">Benvenuto <?php print $_SESSION['__user__']->getFullName() ?> [<?php print $_SESSION['__role__'] ?>]</p>
	</div>