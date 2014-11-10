<div class="smallbox" id="working">
	<p class="menu_label class_icon">La tua scuola</p>
	<?php
	if (isset($_SESSION['__school_order__'])){
	?>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="elenco_docenti.php">Docenti</a></li>
		<li><a href="elenco_alunni.php">Alunni</a></li>
		<li><a href="elenco_classi.php">Classi</a></li>
		<li><a href="pagelle.php">Pagelle online</a></li>
	</ul>
	<p class="menu_label act_icon">Registro di classe</p>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="registro.php?do=cls">Riepilogo classi</a></li>
		<li><a href="registro.php?do=abs">Alunni a rischio</a></li>
		<li><a href="verifica_registro.php">Verifica registro</a></li>
	</ul>
	<?php 
	}
	else {
	?>
	<ul class="menublock" style="" dir="rtl">
	<?php 
		foreach ($_SESSION['__school_level__'] as $k => $sl){
	?>
		<li><a href="#" onclick="select_level('<?php echo $_SESSION['__path_to_root__'] ?>', '<?php echo basename($_SERVER['PHP_SELF']) ?>', <?php echo $k ?>)" id="level_<?php echo $k ?>" class="school_level"><?php echo $sl ?></a></li>
	<?php 
		}
	?>
	</ul>
	<?php
	}
	?>
</div>
