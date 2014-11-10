<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Admin home page</title>
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
		});
	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "stat_menu.php" ?>
	</div>
	<div id="left_col">
		<table style="margin: 0 auto 0 auto;" class="admin_table">
            <tr>
                <td colspan="2" class="_bold _center accent_decoration">Genitori</td>
            </tr>
            <?php 
            if((count($_SESSION['__school_level__']) > 1)){ 
            	if( $admin_level == 0){
            ?>
            <tr class="admin_row_small">
                <td ><a href="stat_reg/stat_reg_parents.php">Statistiche genitori</a></td>
                <td style="width: 50%">
                    <a href="stat_reg/stat_reg_parents.php">Statistiche dei genitori iscritti al registro</a>
                </td>
            </tr>
            <?php
            	}
            	foreach ($_SESSION['__school_level__'] as $k => $sl){
					if($admin_level == $k || $admin_level == 0){
            ?>
            <tr class="admin_row_small">
            	<td style="width: 50%"><a href="stat_reg/stat_reg_parents.php?school_order=<?php echo $k ?>">Statistiche genitori <?php echo $sl ?></a></td>
                <td style="">
                    <a href="stat_reg/stat_reg_parents.php?school_order=<?php echo $k ?>">Statistiche account genitori per la <?php echo $sl ?></a>
                </td>
            </tr>
            <?php
            		}
            	} 
			} else{ ?>
            <tr class="admin_row_small">
            	<td style="width: 30%; color: #003366"><a href="stat_reg/stat_reg_parents.php?school_order=<?php echo $_SESSION['__only_school_level__'] ?>">Statistiche genitori</a></td>
                <td style="color: #003366">
                    <a href="stat_reg/stat_reg_parents.php?school_order=<?php echo $_SESSION['__only_school_level__'] ?>">Statistiche dei genitori iscritti al registro </a>
                </td>
            </tr>
            <?php } ?>
            <tr class="admin_void">
            	<td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2"  class="_bold _center accent_decoration">Pagelle</td>
            </tr>
            <?php 
            if((count($_SESSION['__school_level__']) > 1)){ 
            	if( $admin_level == 0){
            ?>
            <tr>
                <td style="width: 30%; color: #003366"><a href="stat_reg/stat_reg_reports.php">Dowload pagelle</a></td>
                <td style="color: #003366">
                    <a href="stat_reg/stat_reg_reports.php">Statistiche di download pagelle</a>
                </td>
            </tr>
            <?php
            	}
            	foreach ($_SESSION['__school_level__'] as $k => $sl){
					if($admin_level == $k || $admin_level == 0){
            ?>
            <tr>
            	<td style="width: 30%; color: #003366"><a href="stat_reg/stat_reg_reports.php?school_order=<?php echo $k ?>">Download pagelle <?php echo $sl ?></a></td>
                <td style="color: #003366">
                    <a href="stat_reg/stat_reg_reports.php?school_order=<?php echo $k ?>">Statistiche download pagelle per la <?php echo $sl ?></a>
                </td>
            </tr>
            <?php
            		}
            	} 
			} else{ ?>
            <tr>
            	<td style="width: 30%; color: #003366"><a href="stat_reg/stat_reg_reports.php?school_order=<?php echo $_SESSION['__only_school_level__'] ?>">Download pagelle</a></td>
                <td style="color: #003366">
                    <a href="stat_reg/stat_reg_reports.php?school_order=<?php echo $_SESSION['__only_school_level__'] ?>">Statistiche download pagelle </a>
                </td>
            </tr>
            <?php } ?>
            <tr>
            	<td colspan="2">&nbsp;</td>
            </tr>
            <tr class="admin_void">
                <td colspan="2">&nbsp;&nbsp;&nbsp;</td>
            </tr>
        </table>
    </div>
	<p class="spacer"></p>
	</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="../index.php"><img src="../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="index.php"><img src="../images/31.png" style="margin-right: 10px; position: relative; top: 5%" />Admin</a></div>
		<div class="drawer_link"><a href="http://www.istitutoiglesiasserraperdosa.it"><img src="../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../shared/do_logout.php"><img src="../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
