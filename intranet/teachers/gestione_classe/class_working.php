<?php 
if(isset($_REQUEST['q'])){
	$q = $_REQUEST['q'];
}
else{
	$q = 0;
}

$anni_corso_classe = array();

$year = $_SESSION['__current_year__']->get_ID();

$sel_anni = "SELECT id_anno, descrizione FROM rb_anni WHERE id_anno < $year ORDER BY id_anno DESC";
try{
	$res_anni = $db->executeQuery($sel_anni);
} catch(MySQLException $ex){
	$ex->redirect();
}
while ($row = $res_anni->fetch_assoc()) {
	$anni_corso_classe[] = $row;
}

$is_teacher_in_this_class = $_SESSION['__user__']->isTeacherInClass($_SESSION['__classe__']->get_ID());

?>
    <div class="smallbox" id="working">
    <h2 class="menu_head">Classe <?php print $_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione() ?></h2>
    <p class="menu_label act_icon">Gestione classe</p>
    <ul class="menublock" dir="rtl">
        <li><a href="classe.php?cls=<?php print $_SESSION['__classe__']->get_ID() ?>" style="font-weight: normal">Home</a></li>
        <li><a href="elenco_alunni.php" style="font-weight: normal">Elenco alunni</a></li>
        <!-- <li><a href="dati_classe.php" style="font-size: 11px; font-weight: normal">Dati riassuntivi</a></li> -->
        <li><a href="orario.php" style="font-weight: normal">Orario lezioni</a></li>
        <?php if($is_teacher_in_this_class){ ?>
        <li><a href="elenco_attivita.php?all=1" style="font-weight: normal">Attivit&agrave;</a></li>
        <li><a href="elenco_compiti.php?all=1" style="font-weight: normal">Compiti</a></li>
        <li><a href="../registro_personale/index.php?cls=<?php print $_SESSION['__classe__']->get_ID() ?>&q=<?php print $q ?>" style="font-weight: normal">Voti</a></li>
        <li><a href="relazioni.php?all=0" style="font-weight: normal">Relazioni</a></li>
        <?php } ?>
        <?php
        if(($_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) || ($_SESSION['__user__']->isAdministrator()) || ($_SESSION['__user__']->getUsername() == "rbachis")):
        ?>
        <li><a href="cerca_pagella.php" style="font-weight: normal">Cerca pagella</a></li>
        <?php endif; ?>
        <?php
        if ($_SESSION['__classe__']->getSchoolOrder() == 1) {
            if(($_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) || ($_SESSION['__user__']->isAdministrator()) || ($_SESSION['__user__']->getUsername() == "rbachis")) { ?>
                <li><a href="archivio_pagellini.php">Segnalazioni</a></li>
                <?php
            }
        }
        ?>
        <li><a href="laboratori.php" style="font-weight: normal">Prenota laboratorio</a></li>
        <?php if ($_SESSION['__classe__']->get_anno() == 3 && is_installed('exams')): ?>
        <li><a href="../../../modules/exams/load_module.php?module=exams&page=front" style="font-weight: normal">Esame conclusivo primo ciclo</a></li>
        <?php endif; ?>
     </ul>
    <p class="menu_label act_icon">Archivio anni precedenti</p>
    <ul class="menublock" dir="rtl">
        <?php
		if ($_SESSION['__classe__']->getSchoolOrder() == 1) {
			if ($_SESSION['__classe__']->get_anno() == 2) {
				?>
                <li><a href="archivio_scrutini.php?y=<?php echo $anni_corso_classe[0]['id_anno'] ?>" style="font-weight: normal">Scrutini</a></li>
                <li><a href="archivio_relazioni.php?y=<?php echo $anni_corso_classe[0]['id_anno'] ?>" style="font-weight: normal">Relazioni</a></li>
                <li><a href="archivio_pagelle.php?y=<?php echo $anni_corso_classe[0]['id_anno'] ?>" style="font-weight: normal">Pagelle</a></li>
				<?php
			} else if ($_SESSION['__classe__']->get_anno() == 3) {
				?>
                <li><a href="archivio_scrutini.php" style="font-weight: normal">Scrutini</a></li>
                <li><a href="archivio_relazioni.php" style="font-weight: normal">Relazioni</a></li>
                <li><a href="archivio_pagelle.php" style="font-weight: normal">Pagelle</a></li>
				<?php
			}
		}
        ?>
    </ul>
 <?php if(is_installed("com")){ ?>
 <?php } ?>
</div> 
