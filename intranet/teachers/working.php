<?php 

require_once $_SESSION['__path_to_root__']."lib/ArrayMultiSort.php";

if(date("Y-m-d") <= format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-")){
	$q = 1;
}
else{
	$q = 2;
}
$classi = $_SESSION['__user__']->getClasses();
$msarray = new ArrayMultiSort($classi);
$msarray->setSortFields(array("classe"));
$msarray->sort();
$classi = $msarray->getData();

if (isset($_SESSION['__user_config__']['riepilogo_registro']) && $_SESSION['__user_config__']['riepilogo_registro'][0] == 1) {
	$reg_page = "riepilogo_registro_classe.php";
}
else {
	$reg_page = "registro_classe.php";
}

?>
<div class="smallbox" id="working">
<?php if(is_installed("gest_cls")){ ?>
	<p class="menu_label act_icon">Le tue classi</p>
	<ul class="menublock" style="" dir="rtl">
	<?php 
	if(count($classi) < 4){
    	foreach ($classi as $_classe){
    ?>
			<li><a href="<?php echo $_SESSION['__path_to_reg_home__'] ?>gestione_classe/classe.php?cls=<?php print $_classe['id_classe'] ?>">Classe <?php print $_classe['classe'] ?></a></li>
	<?php 
    	}
	}
	else{
		$idx = 0;
	?>
		<li>
	<?php 
		foreach ($classi as $_classe){
			if(($idx % 3) == 0 && $idx != 0){
				print("</li>\n<li>");
			}
	?>
		<a href="<?php echo $_SESSION['__path_to_reg_home__'] ?>gestione_classe/classe.php?cls=<?php print $_classe['id_classe'] ?>"><?php print $_classe['classe'] ?></a>&nbsp;&nbsp;&nbsp;
	<?php 
			$idx++;	
    	}
    	
    	print("</li>");
    }
    ?>
	</ul>
<?php } ?>
	<p class="menu_label classbook_icon">Registro di classe</p>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="<?php echo $_SESSION['__path_to_reg_home__'] ?>registro_classe/sostituzioni.php" style="text-transform: uppercase">Sostituzioni</a></li>
<?php
	reset($classi);
	if(count($classi) < 4){
    	foreach ($classi as $_classe){
    ?>
			<li><a href="<?php echo $_SESSION['__path_to_reg_home__'] ?>registro_classe/<?php echo $reg_page ?>?data=<?php echo date("Y-m-d") ?>&cls=<?php print $_classe['id_classe'] ?>">Classe <?php print $_classe['classe'] ?></a></li>
	<?php 
    	}
	}
	else{
		$idx = 0;
	?>
		<li>
	<?php 
	foreach ($classi as $_classe){
			if(($idx % 3) == 0 && $idx != 0){
				print("</li>\n<li>");
			}
	?>
		<a href="<?php echo $_SESSION['__path_to_reg_home__'] ?>registro_classe/<?php echo $reg_page ?>?data=<?php echo date("Y-m-d") ?>&cls=<?php print $_classe['id_classe'] ?>"><?php print $_classe['classe'] ?></a>&nbsp;&nbsp;&nbsp;
	<?php 
			$idx++;	
    	}
    	
    	print("</li>");
    }
    ?>
	</ul>
<?php if(is_installed("reg_pers")){ ?>
	<p class="menu_label gradebook_icon">Registro personale</p>
	<ul class="menublock" style="" dir="rtl">
<?php
	reset($classi);
	if(count($classi) < 4){
    	foreach ($classi as $_classe){
			if($_classe['teacher'] == 1 && ($_SESSION['__user__']->getSubject() != 27) && ($_SESSION['__user__']->getSubject() != 41)){
    ?>
			<li><a href="<?php echo $_SESSION['__path_to_reg_home__'] ?>registro_personale/index.php?cls=<?php print $_classe['id_classe'] ?>&q=<?php print $q ?>">Classe <?php print $_classe['classe'] ?></a></li>
	<?php 
			}
			else{
				$sel_alunni = "SELECT id_alunno, cognome, nome FROM rb_alunni, rb_assegnazione_sostegno WHERE alunno = id_alunno AND anno = {$_SESSION['__current_year__']->get_ID()} AND classe = {$_classe['id_classe']} AND docente = {$_SESSION['__user__']->getUid()} ORDER BY cognome, nome";
				$res_alunni = $db->execute($sel_alunni);
				$alunni = array();
				while ($row = $res_alunni->fetch_assoc()){
					$alunni[] = $row;
					$label_link = "Classe {$_classe['classe']}";
					if ($res_alunni->num_rows > 1){
						$label_link = "Classe {$_classe['classe']} - ".$row['cognome'];
					}
?>
			<li><a href="<?php echo $_SESSION['__path_to_reg_home__'] ?>sostegno/index.php?cls=<?php print $_classe['id_classe'] ?>&q=<?php print $q ?>&id_st=<?php echo $row['id_alunno'] ?>"><?php echo $label_link ?></a></li>
			<!-- <li><a href="<?php echo $_SESSION['__path_to_reg_home__'] ?>registro_personale/dettaglio_medie.php?cls=<?php print $_classe['id_classe'] ?>&q=<?php print $q ?>">Classe <?php print $_classe['classe'] ?></a></li> -->
<?php
				}
			}
    	}
	}
	else{
		$idx = 0;
	?>
		<li>
	<?php 
		foreach ($classi as $_classe){
			if(($idx % 3) == 0 && $idx != 0){
				print("</li>\n<li>");
			}
			if($_classe['teacher'] == 1){
	?>
		<a href="<?php echo $_SESSION['__path_to_reg_home__'] ?>registro_personale/index.php?cls=<?php print $_classe['id_classe'] ?>&q=<?php print $q ?>"><?php print $_classe['classe'] ?></a>&nbsp;&nbsp;&nbsp;
	<?php 
			}
			else{
?>
			<a href="<?php echo $_SESSION['__path_to_reg_home__'] ?>registro_personale/dettaglio_medie.php?cls=<?php print $_classe['id_classe'] ?>&q=<?php print $q ?>"><?php print $_classe['classe'] ?></a>&nbsp;&nbsp;&nbsp;
<?php
			}
			$idx++;	
    	}
    	
    	print("</li>");
    }
    ?>
	</ul>
<?php
}
if ($_SESSION['__user__']->getSchoolOrder() == 2) {
?>
	<p class="menu_label act_icon">Programmazione</p>
	<ul class="menublock" style="" dir="rtl">
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
<?php
}
?>
</div>
