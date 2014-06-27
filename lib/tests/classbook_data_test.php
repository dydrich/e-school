<?php

require_once "../start.php";
require_once "../ScheduleModule.php";
require_once "../ClassbookData.php";
require_once "../RBTime.php";

$class_id = 0;
if(isset($_REQUEST['class_id'])){
	$class_id = $_REQUEST['class_id'];
}
if($class_id != 0){
	$query = "SELECT * FROM rb_classi, rb_sedi WHERE rb_classi.sede = id_sede AND id_classe = {$class_id}";
	$_classes = $db->executeQuery($query);
	$_class = $_classes->fetch_assoc();
	$test_class = new Classe($_class, $db);
	$school_year = $_SESSION['__school_year__'][$test_class->getSchoolOrder()];
	$school_year->setSchoolOrder($test_class->getSchoolOrder());
	$classbook_data = new ClassbookData($test_class, $school_year, "AND data <= NOW()", $db);
	$totali = $classbook_data->getClassSummary();
	$presence = $classbook_data->getStudentsSummary();
}

$sel_classe = "SELECT id_classe, anno_corso, sezione FROM rb_classi ORDER BY ordine_di_scuola, sezione, anno_corso";
$res_classes = $db->executeQuery($sel_classe);

$navigation_label = "Area amministrazione: sviluppo";
$admin_level = 0;

/*
 * for admin menu
 */
$_SESSION['__path_to_root__'] = "../../";
// admin area
$_SESSION['__path_to_mod_home__'] = "../../admin/";
$_SESSION['__area_label__'] = "Area amministrazione";

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Admin home page</title>
	<link rel="stylesheet" href="../../css/reg.css" type="text/css" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
</script>
</head>
<body>
<?php include "../../admin/header.php" ?>
<?php include "../../admin/navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "../../admin/dev_menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Prova Classe ClassbookData</div>
        <table style="width: 90%; margin: 20px auto 0 auto; border-collapse: collapse">
            <tr>
                <td colspan="3" style="background-color: rgba(211, 222, 199, 0.7)" class="_bold _center">Test statistiche generali</td>
            </tr>
            <?php 
            while($cls = $res_classes->fetch_assoc()){
            ?>
            <tr class="index_link">
                <td style=" width: 33%"><a href="classbook_data_test.php?class_id=<?php echo $cls['id_classe'] ?>" id="head_lnk">Classe <?php echo $cls['anno_corso'], $cls['sezione'] ?></a></td>
            </tr>
           	<?php 
           	}
           	?>
            <tr>
                <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" style="background-color: rgba(211, 222, 199, 0.7)" class="_bold _center">Dati generali classe <?php if ($class_id != 0) echo $test_class->get_anno(),$test_class->get_sezione() ?></td>
            </tr>
            <tr>
                <td style="width: 33%" class="index_link">
	            <?php if ($class_id != 0): ?>
                Giorni totali: <?php echo $totali['giorni'] ?><br />
                Limite giorni: <?php echo $totali['limite_giorni'] ?>
	            <?php endif; ?>
                </td>
                <td style="width: 33%" class="index_link">
	                <?php if ($class_id != 0): ?>
                Ore totali: <?php echo $totali['ore']->toString(RBTime::$RBTIME_SHORT) ?> (<?php echo $totali['ore']->getTime() ?>)<br />
                Limite ore: <?php echo $totali['limite_ore']->toString(RBTime::$RBTIME_SHORT) ?> (<?php echo $totali['limite_ore']->getTime() ?>)
	                <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" style="background-color: rgba(211, 222, 199, 0.7)" class="_bold _center">Dati alunni classe <?php if ($class_id != 0) echo $test_class->get_anno(),$test_class->get_sezione() ?></td>
            </tr>
            <tr style="font-weight: bold; border-bottom: 1px solid">
            	<td style="width: 33%">Alunno</td>
            	<td style="width: 33%">Giorni (%)</td>
            	<td style="width: 33%">Ore (%)</td>
            </tr>
            <?php foreach ($presence as $row){ 
            	$perc_day = round((($row['absences'] / $totali['giorni']) * 100), 2);
            	$absences = new RBTime(0, 0, 0);
            	$absences->setTime($totali['ore']->getTime() - $row['presence']->getTime());
            	$perc_hour = round((($absences->getTime() / $totali['ore']->getTime()) * 100), 2);
            ?>
            <tr style="font-weight: normal">
            	<td style="width: 33%"><?php echo $row['name'] ?></td>
            	<td style="width: 33%"><?php echo $row['absences'] ?> (<?php echo $perc_day ?>%)</td>
            	<td style="width: 33%"><?php echo $absences->toString(RBTime::$RBTIME_SHORT) ?> (<?php echo $perc_hour ?>%)</td>
            </tr>
            <?php } ?>
            <tr>
                <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
            </tr>
        </table>
    </div>
	<p class="spacer"></p>
	</div>
	<?php include "../../admin/footer.php" ?>
</body>
</html>