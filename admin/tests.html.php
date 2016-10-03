<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Test classes</title>
	<link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../js/page.js"></script>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "dev_menu.php" ?>
	</div>
	<div id="left_col">
        <table style="width: 90%; margin: 0 auto 0 auto; border-collapse: collapse" class="admin_table">
            <tr class="index_link">
                <td style=" width: 30%"><a href="../lib/tests/classbook_manager_test.php">ClassbookManager</a></td>
                <td style="color: #003366">
                    <a href="../lib/tests/classbook_manager_test.php">Gestione del registro di classe</a>
                </td>
            </tr>
            <tr class="index_link">
                <td style=" width: 30%"><a href="../lib/tests/school_year_manager_test.php">SchoolYearManager</a></td>
                <td style="color: #003366">
                    <a href="../lib/tests/school_year_manager_test.php">Gestione degli anni scolastici</a>
                </td>
            </tr>
            <tr class="index_link">
                <td style=" width: 30%"><a href="../lib/tests/schedule_module_test.php">ScheduleModule</a></td>
                <td style="color: #003366">
                    <a href="../lib/tests/schedule_module_test.php">Gestione degli moduli orario</a>
                </td>
            </tr>
            <tr class="index_link">
                <td style=" width: 30%"><a href="../lib/tests/schedule_manager_test.php">ScheduleManager</a></td>
                <td style="color: #003366">
                    <a href="../lib/tests/schedule_manager_test.php">Gestione degli orari</a>
                </td>
            </tr>
            <tr class="index_link">
                <td style=" width: 30%"><a href="../lib/tests/classbook_data_test.php">ClassbookData</a></td>
                <td style="color: #003366">
                    <a href="../lib/tests/classbook_data_test.php">Gestione delle statistiche generali orario</a>
                </td>
            </tr>
        </table>
    </div>
	<p class="spacer"></p>
	</div>
<?php include "footer.php" ?>
</body>
</html>
