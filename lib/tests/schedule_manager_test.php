<?php

require_once "../start.php";
require_once "../ScheduleModule.php";
require_once "../ScheduleManager.php";
require_once "../RBTime.php";

ini_set("DISPLAY_ERRORS", "1");

$classi = array();
$sel_classes = "SELECT * FROM rb_classi ORDER BY ordine_di_scuola, sezione, anno_corso";
$res_classes = $db->executeQuery($sel_classes);
while($cl = $res_classes->fetch_assoc()){
	$classi[$cl['id_classe']] = $cl;
}

$class_id = 1;
if(isset($_REQUEST['class_id'])){
	$class_id = $_REQUEST['class_id'];
}
$my_class = new Classe($classi[$class_id], $db);
$module = $my_class->get_modulo_orario();
$schedule_manager = new ScheduleManager($db, $_SESSION['__current_year__']->get_ID());

$orario = array();
$sel_orario = "SELECT * FROM rb_orario WHERE anno = {$_SESSION['__current_year__']->get_ID()} AND classe = {$class_id} ORDER BY giorno, ora";
$res_orario = $db->execute($sel_orario);
while($h = $res_orario->fetch_assoc()){
	$orario[$h['giorno']][$h['ora']] = $h['materia'];
}

$materie = array();
$sel_m = "SELECT * FROM rb_materie";
$res_m = $db->execute($sel_m);
while($m = $res_m->fetch_assoc()){
	$materie[$m['id_materia']] = $m['materia'];
}

$tags = array(1 => "LUN", 2 => "MAR", 3 => "MER", 4 => "GIO", 5 => "VEN", 6 => "SAB");

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
	<link rel="stylesheet" href="../../css/site_themes/blue_red/reg.css" type="text/css" />
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
		<div class="group_head">Prova Classe ScheduleManager</div>
        <table style="width: 90%; margin: 20px auto 0 auto; border-collapse: collapse">
            <tr>
                <td colspan="6" class="group_head">Test Orario di classe</td>
            </tr>
            <?php
            $res_classes->data_seek(0);
            while($cls = $res_classes->fetch_assoc()){
            ?>
            <tr class="index_link">
                <td style=" width: 32%" colspan="2"><a href="schedule_manager_test.php?class_id=<?php echo $cls['id_classe'] ?>" id="head_lnk">Classe <?php echo $cls['anno_corso'].$cls['sezione'] ?></a></td>
                <td style="color: #003366" colspan="4"> 
                    <a href="schedule_manager_test.php?class_id=<?php echo $cls['id_classe'] ?>" id="head_lnk">Prova la classe <?php echo $cls['id_classe'] ?></a>
                </td>
            </tr>
           	<?php 
           	}
           	?>
            <tr>
                <td colspan="6">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr style="border-bottom: 1px solid #003366">
            <?php foreach ($tags as $k => $tag){ ?>
            	<td style="width: 16%; font-weight: bold"><?php echo $tag ?></td>
           <?php } ?>
            </tr>
            <tr style="">
            <?php
            reset($tags); 
            foreach ($tags as $k => $tag){ ?>            
            	<td style="width: 16%; font-weight: bold">
            	<?php 
            	$day = $module->getDay($k);
            	if ($day != null){
					$or = $orario[$k];
					foreach ($or as $j => $hr){
            	?>
      				<p><?php echo $j ?> ora: <?php echo $hr ?></p>
            	<?php
            		}
				} 
				else{
			?>
				Non ci sono lezioni
			<?php } ?>
            	</td>
            	<?php } ?>        
            </tr>

        </table>
    </div>
	<p class="spacer"></p>
	</div>
<?php include "../../admin/footer.php" ?>
</body>
</html>
