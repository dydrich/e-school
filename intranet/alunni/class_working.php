<?php 
if(date("Y-m-d") < $fine_q){
	$q = 1;
}
else{
	$q = 2;
}
?>
<div class="smallbox" id="working">
<h2 class="menu_head">Menu</h2>
	<p class="menu_label class_icon">Registro</p>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="registro.php">Registro di classe</a></li>
		<li><a href="voti.php">Voti</a></li>
	</ul>
	<p class="menu_label act_icon">In classe</p>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="orario.php">Orario</a></li>
		<li><a href="compiti.php">Compiti</a></li>
		<li><a href="attivita.php">Attivit&agrave;</a></li>
		<li><a href="lezioni.php">Lezioni</a></li>
		<?php if (is_installed("docs")): ?><li><a href="../../modules/documents/load_module.php?module=docs&area=alunni">Documenti</a></li><?php endif; ?>
	</ul>
	<?php if(is_installed("com")){ ?>
    <p style="color: #484848; font-size: 1.1em; text-transform: uppercase; background: url(../../images/9.png) no-repeat; background-position: right top; text-align: right; clear: right; padding-right: 30px">Comunicazioni</p>
    	<ul class="menublock" style="" dir="rtl">
    		<li><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=alunni">Home</a>&nbsp;&nbsp;&nbsp;</li>
    	</ul>
<?php } ?>
</div>