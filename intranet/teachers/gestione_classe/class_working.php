<?php 
if(isset($_REQUEST['q'])){
	$q = $_REQUEST['q'];
}
else{
	$q = 0;
}

$is_teacher_in_this_class = $_SESSION['__user__']->isTeacherInClass($_SESSION['__classe__']->get_ID());

?>
<div class="smallbox" id="working"> 
<h2 class="menu_head">Classe <?php print $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></h2>
<p class="menu_label act_icon">Gestione classe</p>
<ul class="menublock" dir="rtl">
	<li><a href="classe.php?cls=<?php print $_SESSION['__classe__']->get_ID() ?>" style="font-size: 11px; font-weight: normal">Home</a></li>
	<li><a href="elenco_alunni.php" style="font-size: 11px; font-weight: normal">Elenco alunni</a></li>
	<!-- <li><a href="dati_classe.php" style="font-size: 11px; font-weight: normal">Dati riassuntivi</a></li> -->
	<li><a href="orario.php" style="font-size: 11px; font-weight: normal">Orario lezioni</a></li>
	<?php if($is_teacher_in_this_class){ ?>
	<li><a href="elenco_attivita.php?all=1" style="font-size: 11px; font-weight: normal">Attivit&agrave;</a></li>
	<li><a href="elenco_compiti.php?all=1" style="font-size: 11px; font-weight: normal">Compiti</a></li>
	<li><a href="../registro_personale/index.php?cls=<?php print $_SESSION['__classe__']->get_ID() ?>&q=<?php print $q ?>" style="font-size: 11px; font-weight: normal">Voti</a></li>
	<?php } ?>
	<?php 
	if(($_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) || ($_SESSION['__user__']->isAdministrator()) || ($_SESSION['__user__']->getUsername() == "rbachis")):
	?>
	<li><a href="cerca_pagella.php" style="font-size: 11px; font-weight: normal">Cerca pagella</a></li>
	<?php endif; ?>
 </ul>
 <?php if(is_installed("com")){ ?>
 <!-- 
<p class="menu_label com_icon">Comunicazioni</p>
 <ul class="menublock" dir="rtl"> 	
  	<li style="padding-left: 20px"><a href="send_mail.php?d=students" style="font-size: 11px; font-weight: normal">Alunni</a></li>
  	<li style="padding-left: 20px"><a href="send_mail.php?d=parents" style="font-size: 11px; font-weight: normal">Genitori</a></li>
</ul>
 -->
<?php } ?>
</div> 