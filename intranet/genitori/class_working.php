<?php
/**
 * controllo se il figlio e` attivo o licenziato
 */
$son = $_SESSION['__sons__'][$_SESSION['__current_son__']];
$is_active = $son[3];
?>

<div class="smallbox" id="working">
<h2 class="menu_head">Menu</h2>
	<p class="menu_label class_icon">Registro</p>
	<ul class="menublock" style="" dir="rtl">
		<?php if ($is_active): ?>
		<li><a href="registro.php">Registro di classe</a></li>
		<li><a href="voti.php">Voti</a></li>
		<?php endif; ?>
		<li><a href="pagella.php">Pagella online</a></li>
	</ul>
	<?php if ($is_active): ?>
	<p class="menu_label act_icon">In classe</p>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="cdc.php">Docenti</a></li>
		<!-- <li><a href="classe.php">Alunni</a></li> -->
		<li><a href="orario.php">Orario</a></li>
		<li><a href="compiti.php">Compiti</a></li>
		<li><a href="attivita.php">Attivit&agrave;</a></li>
		<li><a href="lezioni.php">Lezioni</a></li>
	</ul>
	<?php if(is_installed("com")){ ?> 	
    <p class="menu_label com_icon">Comunicazioni</p>
    	<ul class="menublock" style="" dir="rtl">
    		<li><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=genitori">Home</a>&nbsp;&nbsp;&nbsp;</li>
    	</ul>
<?php } ?>
	<?php endif; ?>
</div>
