<?php

require_once "../start.php";
require_once "../ScheduleModule.php";
require_once "../RBTime.php";

ini_set("DISPLAY_ERRORS", "1");

$mod_id = 0;
if(isset($_REQUEST['mod'])){
	$mod_id = $_REQUEST['mod'];
}
$module = new ScheduleModule($db, $mod_id);

$sel_modules = "SELECT id_modulo FROM rb_moduli_orario";
$res_modules = $db->executeQuery($sel_modules);

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
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" />
</head>
<body>
<?php include "../../admin/header.php" ?>
<?php include "../../admin/navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "../../admin/dev_menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Prova Classe ScheduleModule</div>
        <table style="width: 90%; margin: 20px auto 0 auto; border-collapse: collapse">
            <tr>
                <td colspan="6" style="background-color: rgba(211, 222, 199, 0.7)" class="_bold _center" colspan="6">Test Moduli</td>
            </tr>
            <?php 
            while($mod = $res_modules->fetch_assoc()){
            ?>
            <tr class="index_link">
                <td style=" width: 32%" colspan="2"><a href="schedule_module_test.php?mod=<?php echo $mod['id_modulo'] ?>" id="head_lnk">Modulo <?php echo $mod['id_modulo'] ?></a></td>
                <td style="color: #003366" colspan="4"> 
                    <a href="schedule_module_test.php?mod=<?php echo $mod['id_modulo'] ?>" id="head_lnk">Prova il modulo <?php echo $mod['id_modulo'] ?></a>
                </td>
            </tr>
           	<?php 
           	}
           	?>
            <tr>
                <td colspan="6">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td colspan="6" style="background-color: rgba(211, 222, 199, 0.7)" class="_bold _center" colspan="6">Dati generali modulo <?php echo $mod_id ?></td>
            </tr>
            <tr>
                <td colspan="6" class="index_link">
                Numero di giorni: <?php echo $module->getNumberOfDays() ?><br />
                Numero di ore totali: <?php echo $module->getClassDuration()->getTime() / 3600 ?>
                </td>
            </tr>
            <tr>
                <td colspan="6">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr style="border-bottom: 1px solid #003366">
            <?php foreach ($tags as $k => $tag){ ?>
            	<td style="width: 16%; font-weight: bold"><?php echo $tag ?></td>
           <?php } ?>
            </tr>
            <tr style="">
            <?php foreach ($tags as $k => $tag){ ?>            
            	<td style="width: 16%; font-weight: bold">
            	<?php 
            	$day = $module->getDay($k);
            	if ($day != null){
            	?>
            	Ingresso: <span style="font-weight: normal"><?php echo $day->getEnterTime()->toString(RBTime::$RBTIME_SHORT) ?></span><br />
            	<?php if($day->hasCanteen()){ 
            		$less_start = new RBTime(0, 0, 0);
            		$less_start->setTime($day->getCanteenStart()->getTime() + $day->getCanteenDuration()->getTime());
            	?>
            	Mensa: <span style="font-weight: normal"><?php echo $day->getCanteenStart()->toString(RBTime::$RBTIME_LONG) ?></span><br />
            	Ripresa: <span style="font-weight: normal"><?php echo $less_start->toString(RBTime::$RBTIME_LONG) ?></span><br />
            	<?php 
            	}
            	?>
            	Uscita: <span style="font-weight: normal"><?php echo $day->getExitTime()->toString(RBTime::$RBTIME_LONG) ?></span><br />
            	Durata: <span style="font-weight: normal"><?php echo $day->getClassDuration()->toString(RBTime::$RBTIME_SHORT) ?></span>
            	<?php 
				} 
				else{
			?>
				Non ci sono lezioni
			<?php } ?>
            	</td>
            	<?php } ?>        
            </tr>
            <tr style="border-top: 1px solid #003366">
            <?php foreach ($tags as $k => $tag){ ?>            
            	<td style="width: 16%; font-weight: bold">
            	<?php 
            	$day = $module->getDay($k);
            	if ($day != null){
					$num_h = $day->getNumberOfHours();
					$h_start = $day->getLessonsStartTime();
					for($i = 0; $i < $num_h; $i++){
            	?>
            	<?php echo $i + 1 ?> ora: <?php echo $h_start[$i + 1]->toString() ?><br />
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
            <tr class="admin_void">
                <td colspan="6">&nbsp;&nbsp;&nbsp;</td>
            </tr>

        </table>
    </div>
	<p class="spacer"></p>
	</div>
<?php include "../../admin/footer.php" ?>
</body>
</html>
