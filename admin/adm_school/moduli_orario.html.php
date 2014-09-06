<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Admin home page</title>
<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
<link rel="stylesheet" href="../../css/general.css" type="text/css" />
<link rel="stylesheet" href="../../css/themes/default.css" type="text/css"/>
<link rel="stylesheet" href="../../css/themes/alphacube.css" type="text/css"/>
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
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Gestione moduli orario</div>
		<table class="admin_table">
            <tr>
                <td style="width: 20%" class="adm_titolo_elenco_first">Modulo</td>
                <td style="width: 40%" class="adm_titolo_elenco">Giorni</td>
                <td style="width: 20%" class="adm_titolo_elenco">Ore</td>
                <td style="width: 20%" class="adm_titolo_elenco_last _center">Mensa</td>
            </tr>
            <tr class="admin_row_before_text">
                <td colspan="4"></td>
            </tr>
        </thead>
        <tbody>
            <?php
            $giorni = array(1 => "LUN", 2 => "MAR", 3 => "MER", 4 => "GIO", 5 => "VEN", 6 => "SAB");
            $use_module = true;
            foreach ($modules as $k => $module){
				$no_less_days = $module->getNoLessonDays();
				$dd = array();
				foreach ($giorni as $t => $g){
					if (!in_array($t, $no_less_days)){
						$dd[] = $g;
					}
				}
				if ($module->getNumberOfDays() < 1){
					$use_module = false;
				}
				$string_days = join(", ", $dd);
            ?>
            <tr style="border-bottom: 1px solid #CCCCCC">
                <td style="width: 20%"><a href="dettaglio_modulo.php?idm=<?php echo $k ?>">Modulo #<?php echo $k ?></a></td>
                <td style="width: 40%"><?php if ($use_module) echo $module->getNumberOfDays(),": ".$string_days; else echo $data[$k]['giorni'] ?></td>
                <td style="width: 20%"><?php if ($use_module) echo $module->getClassDuration()->toString(RBTime::$RBTIME_SHORT)." (".$module->getNumberOfHours()." di lezione)"; else echo $data[$k]['ore_settimanali'].":00" ?></td>
                <td style="width: 20%; text-align: center"><?php echo ($module->hasCanteen()) ?  "SI" : "NO" ?></td>
            </tr>
           	<?php 
           	}
           	?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr class="admin_menu">
                <td colspan="4">
                	<a href="dettaglio_modulo.php?id=0" id="add" class="nav_link_first">Nuovo modulo</a>|
                    <a href="../index.php" class="nav_link_last">Torna al menu</a>
                </td>
            </tr>
        </tfoot>
        </table>
        </div>
	<p class="spacer"></p>
	</div>
	<?php include "../footer.php" ?>
</div>
</body>
</html>
