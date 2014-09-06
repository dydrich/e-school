<?php

require_once "../start.php";
require_once "../SchoolYearManager.php";

check_session();
check_permission(ADM_PERM);

ini_set("display_errors", "1");

$symanager = new SchoolYearManager($db);
$id = 0;
$error = "";

if (isset($_REQUEST['action'])) {
	switch ($_REQUEST['action']) {
		case "new":
			try{
				$symanager->startTransaction();
				$id = $symanager->createNewYear("2013-09-01", "2014-08-31");
			} catch (MySQLException $ex){
				$error = $ex->getQuery()."<br />".$ex->getMessage();
				$symanager->doRollback();
				$id = 0;
			}
			break;
		case "new_it":
			try{
				$db->executeUpdate("BEGIN");
				$id = $symanager->createNewYear("01/09/2013", "31/08/2014");
				$db->executeUpdate("ROLLBACK");
			} catch (MySQLException $ex){
				$error = $ex->getQuery()."<br />".$ex->getMessage();
				$db->executeUpdate("ROLLBACK");
				$id = 0;
			}
			break;
		case "reinsert":
			$clmanager->init();
			$clmanager->reinsert();
			break;
		case "delete_class":
			$clmanager->init();
			$clmanager->deleteClass(43);
			break;
		case "insert_class":
			$clmanager->init();
			$clmanager->insertClass(43);
			break;
		case "reinsert_class":
			$clmanager->init();
			$clmanager->reinsertClass(43);
			break;
		case "delete_student":
			$clmanager->init();
			$clmanager->deleteStudent(4217);
			break;
		case "insert_student":
			$clmanager->init();
			$clmanager->insertStudent(4217);
			break;
		case "reinsert_student":
			$clmanager->init();
			$clmanager->reinsertStudent(4217);
			break;
		case "delete_day":
			$clmanager->init();
			$clmanager->deleteDay('2012-03-27');
			break;
		case "insert_day":
			$clmanager->init();
			$clmanager->insertDay('2012-03-27');
			break;
		case "reinsert_day":
			$clmanager->init();
			$clmanager->reinsertDay('2012-03-27');
			break;
		case "check":
			$clmanager->init();
			$print = $clmanager->checkIntegrity(false);
			break;
		case "correct":
			$clmanager->init();
			$print = $clmanager->checkIntegrity(true);
			break;
		case "delete_holydays":
			$clmanager->init();
			$print = $clmanager->deleteHolydays();
			break;
	}
}

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
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
var id = <?php echo $id ?>;
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
		<div class="group_head">Prova Classe SchoolYearManager</div>
        <table style="width: 90%; margin: 20px auto 0 auto; border-collapse: collapse" class="admin_table">
            <tr>
                <td colspan="2" style="background-color: rgba(211, 222, 199, 0.7)" class="_bold _center">Gestione anni scolastici</td>
            </tr>
            <tr class="index_link">
                <td style=" width: 30%"><a href="school_year_manager_test.php?action=new" id="head_lnk">Inserisci nuovo anno (date SQL)</a></td>
                <td style="">
                    <a href="school_year_manager_test.php?action=new" id="head_lnk">Inserisci nuovo anno (date SQL)</a>
                </td>
            </tr>
            <tr class="index_link">
                <td style=" width: 30%"><a href="school_year_manager_test.php?action=new_it" id="head_lnk">Inserisci nuovo anno (date IT)</a></td>
                <td style="">
                    <a href="school_year_manager_test.php?action=new_it" id="head_lnk">Inserisci nuovo anno (date IT)</a>
                </td>
            </tr>
            <tr class="index_link">
                <td style=" width: 30%"><a href="school_year_manager_test.php?action=delete_class" id="head_lnk">Cancella classe</a></td>
                <td style="">
                    <a href="school_year_manager_test.php?action=delete_class" id="head_lnk">Cancella classe</a>
                </td>
            </tr>
            <tr class="index_link">
                <td style=" width: 30%"><a href="school_year_manager_test.php?action=reinsert_class" id="head_lnk">Reinserisci classe</a></td>
                <td style="">
                    <a href="school_year_manager_test.php?action=reinsert_class" id="head_lnk">Reinserisci classe</a>
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" style="background-color: rgba(211, 222, 199, 0.7)" class="_bold _center">Gestione studenti</td>
            </tr>
            <tr class="index_link">
                <td style=" width: 30%"><a href="school_year_manager_test.php?action=insert_student" id="head_lnk">Inserisci studente</a></td>
                <td style="">
                    <a href="school_year_manager_test.php?action=insert_student" id="head_lnk">Inserisci studente</a>
                </td>
            </tr>
            <tr class="index_link">
                <td style=" width: 30%"><a href="school_year_manager_test.php?action=delete_student" id="head_lnk">Cancella studente</a></td>
                <td style="">
                    <a href="school_year_manager_test.php?action=delete_student" id="head_lnk">Cancella studente</a>
                </td>
            </tr>
            <tr class="index_link">
                <td style=" width: 30%"><a href="school_year_manager_test.php?action=reinsert_student" id="head_lnk">Reinserisci studente</a></td>
                <td style="">
                    <a href="school_year_manager_test.php?action=reinsert_student" id="head_lnk">Reinserisci studente</a>
                </td>
            </tr>
            <tr class="admin_void">
                <td colspan="2">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr class="admin_void">
                <td colspan="2">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" style="height: 50px; border: 1px solid #BBBBBB">
                	<div style="text-align: center; width: 100%; margin-bottom: 20px" id="canvas">
                		Id ultimo anno: <?php echo $id ?>
                		<p><?php echo $error ?></p>
                	</div>
                </td>
            </tr>
        </table>
    </div>
	<p class="spacer"></p>
	</div>
<?php include "../../admin/footer.php" ?>
</body>
</html>
