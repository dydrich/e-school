<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Admin home page</title>
<link href="../css/site_themes/blue_red/reg.css" rel="stylesheet" />
<link href="../css/general.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/themes/default.css" type="text/css"/>
<link rel="stylesheet" href="../css/themes/alphacube.css" type="text/css"/>
<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/scriptaculous.js"></script>
<script type="text/javascript" src="../js/controls.js"></script>
<script type="text/javascript" src="../js/window.js"></script>
<script type="text/javascript" src="../js/window_effects.js"></script>
<script type="text/javascript" src="../js/page.js"></script>
<script type="text/javascript">

</script>
<style>
#wait_label{
	width: 200px;
	height: 40px;
	text-align: center;
	background-color: #000000; 
	border: 1px solid #CCCCCC; 
	border-radius: 8px 8px 8px 8px;
	color: white;
	font-weight: bold;
	vertical-align: middle;
}

div.overlay{
    background-image: url(../images/overlay.png);
    position: absolute;
    top: 0px;
    left: 0px;
    z-index: 90;
    width: 100%;
    height: 100%;
}
</style>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "stat_menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">
			Statistiche registro elettronico <?php echo $title_label ?>
		</div>
        <table style="margin: 20px auto 0 auto;" class="admin_table">
            <tr>
                <td colspan="2" class="_bold _center title_row">Genitori</td>
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
                <td colspan="2"  class="_bold _center title_row">Pagelle</td>
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
            <tr class="admin_menu">
                <td style="width: 50%"></td>
                <td style="text-align: right">
	                <a href="<?php print $_SESSION['__config__']['root_site'] ?>" class="standard_link">Torna all'Home Page</a>
                </td>
            </tr>
            <tr class="admin_void">
                <td colspan="2">&nbsp;&nbsp;&nbsp;</td>
            </tr>
        </table>
    </div>
    <div class="overlay" id="over1" style="display: none">
        <div id="wait_label" style="position: absolute; display: none; padding-top: 25px">Caricamento dati in corso</div>
    </div>
	<p class="spacer"></p>
	</div>
<?php include "footer.php" ?>
</body>
</html>
